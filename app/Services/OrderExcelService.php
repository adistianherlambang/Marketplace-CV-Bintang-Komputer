<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderExcelService
{
    /**
     * Export collection of Orders to an Excel (.xlsx) file stream
     *
     * @param iterable<\App\Models\Order> $orders
     */
    public function exportOrders(iterable $orders, string $title = 'Laporan Pembelian', string $filename = 'laporan-pembelian.xlsx'): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(substr($title, 0, 31));

        // 1. Company Kop & Document Title
        $sheet->setCellValue('A1', 'CV. BINTANG JAYA KOMPUTER');
        $sheet->setCellValue('A2', strtoupper($title));
        $sheet->setCellValue('A3', 'Dicetak pada: ' . now()->format('d/m/Y H:i') . ' WIB');

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(9)->getColor()->setRGB('64748B');

        // 2. Table Column Headers
        $headers = [
            'A5' => 'No.',
            'B5' => 'Nomor Pesanan',
            'C5' => 'Tanggal Pembelian',
            'D5' => 'Nama Pelanggan',
            'E5' => 'Nama Produk',
            'F5' => 'Jumlah',
            'G5' => 'Harga Produk (Rp)',
            'H5' => 'Subtotal (Rp)',
            'I5' => 'Total Pembayaran (Rp)',
            'J5' => 'Metode Pembayaran',
            'K5' => 'Status Pesanan',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        // Style Table Header (Row 5)
        $headerRange = 'A5:K5';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E293B'], // Dark slate
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension(5)->setRowHeight(26);

        // 3. Populate Rows
        $row = 6;
        $no = 1;
        $totalQuantity = 0;
        $totalGrandAmount = 0;

        foreach ($orders as $order) {
            $st = strtolower(trim($order->status));
            $isCancelled = in_array($st, ['batal', 'dibatalkan']);
            $statusDisplay = $isCancelled ? 'Dibatalkan' : ucfirst($order->status);

            $orderDate = $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-';
            $customerName = $order->customer_display_name;
            $paymentMethod = $order->payment_method ?: ($order->payments->first()->payment_method ?? 'Transfer BNI');
            $totalAmount = (float) $order->total_amount;

            if (!$isCancelled) {
                $totalGrandAmount += $totalAmount;
            }

            $items = $order->items;
            if ($items && $items->count() > 0) {
                foreach ($items as $index => $item) {
                    $sheet->setCellValue('A' . $row, $index === 0 ? $no : '');
                    $sheet->setCellValue('B' . $row, $order->invoice_number);
                    $sheet->setCellValue('C' . $row, $orderDate);
                    $sheet->setCellValue('D' . $row, $customerName);
                    $sheet->setCellValue('E' . $row, $item->item_name ?: optional($item->product)->name);
                    $sheet->setCellValue('F' . $row, (int) $item->quantity);
                    $sheet->setCellValue('G' . $row, (float) $item->price);
                    $sheet->setCellValue('H' . $row, (float) ($item->subtotal ?: ($item->price * $item->quantity)));
                    $sheet->setCellValue('I' . $row, $index === 0 ? $totalAmount : '');
                    $sheet->setCellValue('J' . $row, $paymentMethod);
                    $sheet->setCellValue('K' . $row, $statusDisplay);

                    // Alignments
                    $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle('H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle('I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle('J' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('K' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    // Number formats
                    $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('#,##0');
                    $sheet->getStyle('H' . $row)->getNumberFormat()->setFormatCode('#,##0');
                    if ($index === 0) {
                        $sheet->getStyle('I' . $row)->getNumberFormat()->setFormatCode('#,##0');
                    }

                    // Cancelled styling
                    if ($isCancelled) {
                        $sheet->getStyle('K' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('DC2626'))->setBold(true);
                        $sheet->getStyle("A{$row}:K{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FEF2F2');
                    }

                    $totalQuantity += (int) $item->quantity;
                    $row++;
                }
            } else {
                $sheet->setCellValue('A' . $row, $no);
                $sheet->setCellValue('B' . $row, $order->invoice_number);
                $sheet->setCellValue('C' . $row, $orderDate);
                $sheet->setCellValue('D' . $row, $customerName);
                $sheet->setCellValue('E' . $row, '-');
                $sheet->setCellValue('F' . $row, 0);
                $sheet->setCellValue('G' . $row, 0);
                $sheet->setCellValue('H' . $row, 0);
                $sheet->setCellValue('I' . $row, $totalAmount);
                $sheet->setCellValue('J' . $row, $paymentMethod);
                $sheet->setCellValue('K' . $row, $statusDisplay);

                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('I' . $row)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('J' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('K' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                if ($isCancelled) {
                    $sheet->getStyle('K' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('DC2626'))->setBold(true);
                    $sheet->getStyle("A{$row}:K{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FEF2F2');
                }

                $row++;
            }

            $no++;
        }

        // 4. Summary Row
        $lastDataRow = $row - 1;
        if ($lastDataRow >= 6) {
            $sheet->setCellValue('A' . $row, 'TOTAL TRANSAKSI VALID');
            $sheet->mergeCells("A{$row}:E{$row}");
            $sheet->setCellValue('F' . $row, $totalQuantity);
            $sheet->setCellValue('I' . $row, $totalGrandAmount);

            $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F1F5F9'],
                ],
            ]);
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('I' . $row)->getNumberFormat()->setFormatCode('#,##0');

            // Borders for table
            $tableRange = "A5:K{$row}";
            $sheet->getStyle($tableRange)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CBD5E1'],
                    ],
                ],
            ]);
        }

        // 5. Auto-fit column widths
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
