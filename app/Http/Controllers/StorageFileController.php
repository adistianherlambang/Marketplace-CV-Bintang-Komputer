<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StorageFileController extends Controller
{
    /**
     * Layani file publik di direktori storage secara langsung melalui Laravel.
     * Berfungsi sebagai solusi andal jika web server (Apache/cPanel/Shared Hosting)
     * tidak mendukung atau memblokir symbolic link (symlink) dengan error 403 Forbidden.
     */
    public function show(string $path): BinaryFileResponse
    {
        // 1. Cegah Directory Traversal Attack
        if (
            str_contains($path, '..') ||
            str_contains($path, "\0") ||
            str_starts_with($path, '/') ||
            str_starts_with($path, '\\')
        ) {
            abort(403, 'Akses ke direktori tidak diizinkan.');
        }

        // 2. Daftar kandidat lokasi penyimpanan file
        $candidates = [
            storage_path('app/public/' . $path),
            base_path('storage/app/public/' . $path),
            public_path('storage/' . $path),
            base_path('../core/storage/app/public/' . $path),
        ];

        $targetFile = null;
        foreach ($candidates as $candidate) {
            if ($candidate && is_file($candidate)) {
                $targetFile = $candidate;
                break;
            }
        }

        if (!$targetFile) {
            abort(404, 'File tidak ditemukan.');
        }

        $realPath = realpath($targetFile);
        if (!$realPath || !is_file($realPath)) {
            abort(404, 'File tidak ditemukan.');
        }

        // 3. Kembalikan file dengan header caching yang optimal
        return response()->file($realPath, [
            'Cache-Control' => 'public, max-age=86400, stale-while-revalidate=604800',
        ]);
    }
}
