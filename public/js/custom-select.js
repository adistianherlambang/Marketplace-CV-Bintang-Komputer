class CustomSelect {
    constructor(originalSelect) {
        if (originalSelect.classList.contains('custom-select-hidden')) return;

        this.select = originalSelect;
        this.wrapper = null;
        this.trigger = null;
        this.dropdown = null;
        this.searchInput = null;
        this.optionsList = null;
        this.noResults = null;
        this.highlightedIndex = -1;
        this.isOpen = false;
        this.observer = null;

        this.init();
    }

    init() {
        // 1. Hide original select accessibly
        this.select.classList.add('custom-select-hidden');

        // 2. Create wrapper
        this.wrapper = document.createElement('div');
        this.wrapper.className = 'custom-select-container';
        this.wrapper.__customSelect = this;

        // Copy select classes (like filter-select-sm, etc.) to keep layout styling
        this.select.classList.forEach(cls => {
            if (cls !== 'tom-select' && cls !== 'custom-select-hidden' && cls !== 'form-control') {
                this.wrapper.classList.add(cls);
            }
        });

        // Copy specific inline styles if any
        if (this.select.style.width) {
            this.wrapper.style.width = this.select.style.width;
        }

        // Insert wrapper in DOM before select, and move select inside wrapper
        this.select.parentNode.insertBefore(this.wrapper, this.select);
        this.wrapper.appendChild(this.select);

        // 3. Create Trigger
        this.trigger = document.createElement('div');
        this.trigger.className = 'custom-select-trigger';
        this.trigger.setAttribute('tabindex', '0');

        const triggerText = document.createElement('span');
        triggerText.className = 'custom-select-text';
        this.trigger.appendChild(triggerText);

        const triggerArrow = document.createElement('span');
        triggerArrow.className = 'custom-select-arrow';
        triggerArrow.innerHTML = '<i class="fa-solid fa-chevron-down"></i>';
        this.trigger.appendChild(triggerArrow);

        this.wrapper.appendChild(this.trigger);

        // 4. Create Dropdown panel
        this.dropdown = document.createElement('div');
        this.dropdown.className = 'custom-select-dropdown';
        this.dropdown.style.display = 'none';

        // Search input wrapper
        const searchWrapper = document.createElement('div');
        searchWrapper.className = 'custom-select-search-wrapper';

        const searchIcon = document.createElement('i');
        searchIcon.className = 'fa-solid fa-magnifying-glass custom-select-search-icon';
        searchWrapper.appendChild(searchIcon);

        this.searchInput = document.createElement('input');
        this.searchInput.type = 'text';
        this.searchInput.className = 'custom-select-search-input';
        this.searchInput.placeholder = 'Cari...';
        this.searchInput.setAttribute('autocomplete', 'off');
        searchWrapper.appendChild(this.searchInput);

        this.dropdown.appendChild(searchWrapper);

        // Options list
        this.optionsList = document.createElement('div');
        this.optionsList.className = 'custom-select-options-list';
        this.dropdown.appendChild(this.optionsList);

        // No results element
        this.noResults = document.createElement('div');
        this.noResults.className = 'custom-select-no-results';
        this.noResults.innerText = 'Data tidak ditemukan';
        this.noResults.style.display = 'none';
        this.optionsList.appendChild(this.noResults);

        this.wrapper.appendChild(this.dropdown);

        // 5. Populate options
        this.renderOptions();

        // 6. Set up event listeners
        this.setupListeners();

        // 7. Observe mutations on original select (to handle dynamic option changes)
        this.setupObserver();

        // 8. Update initial trigger state
        this.updateTriggerText();
        this.updateDisabledState();
    }

    renderOptions() {
        // Clear old options (except the "no results" div)
        const optionEls = this.optionsList.querySelectorAll('.custom-select-option');
        optionEls.forEach(el => el.remove());

        const options = Array.from(this.select.options);

        options.forEach((opt, idx) => {
            // Skip options with both empty value and empty text
            if (opt.value === "" && opt.text.trim() === "") {
                return;
            }

            const optEl = document.createElement('div');
            optEl.className = 'custom-select-option';
            optEl.dataset.value = opt.value;
            optEl.dataset.index = idx;
            optEl.innerText = opt.text;

            if (opt.selected) {
                optEl.classList.add('selected');
            }

            if (opt.disabled) {
                optEl.classList.add('disabled');
            }

            optEl.addEventListener('click', (e) => {
                e.stopPropagation();
                if (opt.disabled) return;
                this.selectOption(opt.value, idx);
            });

            this.optionsList.insertBefore(optEl, this.noResults);
        });

        this.highlightedIndex = -1;
        this.filterOptions();
    }

    updateTriggerText() {
        const selectedOption = this.select.options[this.select.selectedIndex];
        const textEl = this.trigger.querySelector('.custom-select-text');

        if (selectedOption && selectedOption.value !== "") {
            textEl.innerText = selectedOption.text;
            textEl.classList.remove('placeholder');
        } else {
            // Show placeholder if any, or default text
            const firstOpt = this.select.options[0];
            const placeholderText = firstOpt && firstOpt.value === "" ? firstOpt.text : 'Pilih opsi...';
            textEl.innerText = placeholderText;
            textEl.classList.add('placeholder');
        }
    }

    updateSelectedClass() {
        const optionEls = this.optionsList.querySelectorAll('.custom-select-option');
        const selectedIndex = this.select.selectedIndex;

        optionEls.forEach(el => {
            const idx = parseInt(el.dataset.index);
            if (idx === selectedIndex) {
                el.classList.add('selected');
            } else {
                el.classList.remove('selected');
            }
        });
    }

    updateDisabledState() {
        if (this.select.disabled) {
            this.wrapper.classList.add('disabled');
            this.trigger.removeAttribute('tabindex');
            this.close();
        } else {
            this.wrapper.classList.remove('disabled');
            this.trigger.setAttribute('tabindex', '0');
        }
    }

    setupObserver() {
        this.observer = new MutationObserver((mutations) => {
            let shouldRender = false;
            let checkDisabled = false;
            let shouldUpdateSelection = false;

            mutations.forEach(mutation => {
                if (mutation.type === 'childList') {
                    shouldRender = true;
                } else if (mutation.type === 'attributes') {
                    if (mutation.attributeName === 'disabled') {
                        checkDisabled = true;
                    } else if (mutation.attributeName === 'selected') {
                        shouldUpdateSelection = true;
                    }
                }
            });

            if (shouldRender) {
                this.renderOptions();
                this.updateTriggerText();
            } else if (shouldUpdateSelection) {
                this.updateTriggerText();
                this.updateSelectedClass();
            }
            
            if (checkDisabled) {
                this.updateDisabledState();
            }
        });

        this.observer.observe(this.select, {
            childList: true,
            attributes: true,
            subtree: true
        });

        // Intercept programmatic value & selectedIndex modifications to sync custom select UI
        const self = this;
        const originalValueDescriptor = Object.getOwnPropertyDescriptor(HTMLSelectElement.prototype, 'value');
        const originalIndexDescriptor = Object.getOwnPropertyDescriptor(HTMLSelectElement.prototype, 'selectedIndex');

        Object.defineProperty(this.select, 'value', {
            get() {
                return originalValueDescriptor.get.call(this);
            },
            set(val) {
                originalValueDescriptor.set.call(this, val);
                self.updateTriggerText();
                self.updateSelectedClass();
            },
            configurable: true
        });

        Object.defineProperty(this.select, 'selectedIndex', {
            get() {
                return originalIndexDescriptor.get.call(this);
            },
            set(val) {
                originalIndexDescriptor.set.call(this, val);
                self.updateTriggerText();
                self.updateSelectedClass();
            },
            configurable: true
        });
    }

    setupListeners() {
        // Toggle on trigger click
        this.trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            if (this.select.disabled) return;
            if (this.isOpen) {
                this.close();
            } else {
                this.open();
            }
        });

        // Keyboard to open dropdown from trigger
        this.trigger.addEventListener('keydown', (e) => {
            if (this.select.disabled) return;

            if (e.key === 'ArrowDown' || e.key === 'ArrowUp' || e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.open();
            }
        });

        // Search input key events
        this.searchInput.addEventListener('input', () => {
            this.filterOptions();
        });

        this.searchInput.addEventListener('keydown', (e) => {
            const visibleOptions = Array.from(this.optionsList.querySelectorAll('.custom-select-option:not(.disabled)'))
                .filter(el => el.style.display !== 'none');

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (visibleOptions.length === 0) return;

                this.highlightedIndex++;
                if (this.highlightedIndex >= visibleOptions.length) {
                    this.highlightedIndex = 0;
                }
                this.updateHighlight(visibleOptions);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (visibleOptions.length === 0) return;

                this.highlightedIndex--;
                if (this.highlightedIndex < 0) {
                    this.highlightedIndex = visibleOptions.length - 1;
                }
                this.updateHighlight(visibleOptions);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (visibleOptions.length === 0) return;

                if (this.highlightedIndex >= 0 && this.highlightedIndex < visibleOptions.length) {
                    const el = visibleOptions[this.highlightedIndex];
                    this.selectOption(el.dataset.value, parseInt(el.dataset.index));
                } else if (visibleOptions.length > 0) {
                    // Default to first match if enter is pressed but none highlighted
                    const el = visibleOptions[0];
                    this.selectOption(el.dataset.value, parseInt(el.dataset.index));
                }
            } else if (e.key === 'Escape') {
                e.preventDefault();
                this.close();
                this.trigger.focus();
            } else if (e.key === 'Tab') {
                this.close();
            }
        });
    }

    open() {
        if (this.select.disabled || this.isOpen) return;

        // Close other custom selects first
        document.querySelectorAll('.custom-select-container.open').forEach(container => {
            if (container !== this.wrapper && container.__customSelect) {
                container.__customSelect.close();
            }
        });

        this.isOpen = true;
        this.wrapper.classList.add('open');
        this.dropdown.style.display = 'block';

        this.searchInput.value = '';
        this.filterOptions();

        // Focus search input
        setTimeout(() => {
            this.searchInput.focus();
        }, 50);

        this.highlightSelectedOption();
    }

    close() {
        if (!this.isOpen) return;
        this.isOpen = false;
        this.wrapper.classList.remove('open');
        this.dropdown.style.display = 'none';
        this.highlightedIndex = -1;
        this.clearHighlights();
    }

    selectOption(value, index) {
        // Update original select
        this.select.selectedIndex = index;

        // Dispatch change & input events
        const changeEvent = new Event('change', { bubbles: true });
        const inputEvent = new Event('input', { bubbles: true });
        this.select.dispatchEvent(changeEvent);
        this.select.dispatchEvent(inputEvent);

        this.updateTriggerText();
        this.updateSelectedClass();
        this.close();
        this.trigger.focus();
    }

    filterOptions() {
        const query = this.searchInput.value.toLowerCase().trim();
        const optionEls = this.optionsList.querySelectorAll('.custom-select-option');
        let matchCount = 0;

        optionEls.forEach(el => {
            const text = el.innerText.toLowerCase();
            if (text.includes(query)) {
                el.style.display = 'block';
                matchCount++;
            } else {
                el.style.display = 'none';
                el.classList.remove('highlighted');
            }
        });

        if (matchCount === 0) {
            this.noResults.style.display = 'block';
        } else {
            this.noResults.style.display = 'none';
        }

        // Auto-highlight the first matched option during typing
        this.highlightedIndex = -1;
        const visibleOptions = Array.from(this.optionsList.querySelectorAll('.custom-select-option:not(.disabled)'))
            .filter(el => el.style.display !== 'none');
        if (visibleOptions.length > 0) {
            this.highlightedIndex = 0;
            this.updateHighlight(visibleOptions);
        }
    }

    highlightSelectedOption() {
        const selectedIndex = this.select.selectedIndex;
        const optionEls = Array.from(this.optionsList.querySelectorAll('.custom-select-option:not(.disabled)'));

        const selectedEl = optionEls.find(el => parseInt(el.dataset.index) === selectedIndex);
        if (selectedEl) {
            selectedEl.classList.add('highlighted');
            this.highlightedIndex = optionEls.indexOf(selectedEl);
            this.scrollToHighlighted(selectedEl);
        }
    }

    clearHighlights() {
        this.optionsList.querySelectorAll('.custom-select-option').forEach(el => {
            el.classList.remove('highlighted');
        });
    }

    updateHighlight(visibleOptions) {
        this.clearHighlights();
        if (this.highlightedIndex >= 0 && this.highlightedIndex < visibleOptions.length) {
            const el = visibleOptions[this.highlightedIndex];
            el.classList.add('highlighted');
            this.scrollToHighlighted(el);
        }
    }

    scrollToHighlighted(el) {
        const target = el || this.optionsList.querySelector('.custom-select-option.highlighted');
        if (!target) return;

        const containerHeight = this.optionsList.clientHeight;
        const containerScrollTop = this.optionsList.scrollTop;
        const targetTop = target.offsetTop;
        const targetHeight = target.clientHeight;

        if (targetTop < containerScrollTop) {
            this.optionsList.scrollTop = targetTop;
        } else if (targetTop + targetHeight > containerScrollTop + containerHeight) {
            this.optionsList.scrollTop = targetTop + targetHeight - containerHeight;
        }
    }
}

// Global click handler to close open custom selects on click outside
document.addEventListener('click', (e) => {
    document.querySelectorAll('.custom-select-container.open').forEach(container => {
        if (!container.contains(e.target)) {
            if (container.__customSelect) {
                container.__customSelect.close();
            }
        }
    });
});

// Function to automatically convert standard selects
function initCustomSelects(root = document) {
    // Exclude selects that might have been processed, or are within hidden templates if appropriate
    root.querySelectorAll('select:not(.custom-select-hidden)').forEach(select => {
        new CustomSelect(select);
    });
}

// Document load initializer
document.addEventListener('DOMContentLoaded', () => {
    initCustomSelects();

    // Setup mutation observer on document to auto-initialize newly injected select tags (e.g. by Alpine)
    const bodyObserver = new MutationObserver((mutations) => {
        mutations.forEach(mutation => {
            mutation.addedNodes.forEach(node => {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    if (node.tagName === 'SELECT' && !node.classList.contains('custom-select-hidden')) {
                        new CustomSelect(node);
                    } else {
                        initCustomSelects(node);
                    }
                }
            });
        });
    });

    bodyObserver.observe(document.body, {
        childList: true,
        subtree: true
    });
});
