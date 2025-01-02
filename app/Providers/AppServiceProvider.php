<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Spatie\PdfToText\Pdf;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        $binaryPath = env('PDF_BINARY_PATH');

        // Ensure that the binary path is set and valid
        if (file_exists($binaryPath)) {
            // Pdf::setPdfBinaryPath($binaryPath);
        } else {
            throw new \Exception('pdftotext executable not found at the specified path');
        }
    }
}
