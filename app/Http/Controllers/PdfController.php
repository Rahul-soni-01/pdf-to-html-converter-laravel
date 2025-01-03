<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\PdfToText\Pdf;
use \PhpParser\ParserFactory;
use Smalot\PdfParser\Parser;



class PdfController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'pdf' => 'required|mimes:pdf|max:2048',
        ]);

        // Store the uploaded PDF file
        $pdfPath = $request->file('pdf')->store('pdfs');

        // Get the absolute path
        $absolutePdfPath = storage_path('app/' . $pdfPath);

        // Check if the file exists
        if (!file_exists($absolutePdfPath)) {
            return response()->json(['error' => 'File not found at: ' . $absolutePdfPath], 404);
        }

        // Use Smalot PDF Parser to extract text
        $pdfParser = new Parser();
        $pdf = $pdfParser->parseFile($absolutePdfPath);
        $text = $pdf->getText();

        // Example: Filter out unwanted header and footer lines
        $lines = explode("\n", $text);
        $tableData = [];
        $header = ['Sr No', 'Date', 'Remarks', 'Debit', 'Credit', 'Balance'];

        $isTableStarted = false; // Flag to track when the table starts

        foreach ($lines as $line) {
            // Clean up each line (remove leading/trailing spaces)
            $line = trim($line);

            // Skip lines that are part of the header (this can be adjusted as needed)
            if (!$isTableStarted) {
                // Check if the table is starting based on a keyword (like 'Sr No')
                if (strpos($line, 'Sr No') !== false) {
                    $isTableStarted = true; // Start processing table data
                }
                continue; // Skip non-table lines (like logo, header text, etc.)
            }

            // Split the line into columns (assuming the data is separated by spaces)
            $columns = preg_split('/\s+/', $line);

            // Check if the line has enough columns for a valid row (at least 5 columns)
            if (count($columns) >= 5) {
                // Add the row to tableData
                $tableData[] = $columns;
            }
        }

        return view('pdf-table', ['tableData' => $tableData, 'header' => $header]);
    }

    public function html(Request $request)
    {
        $request->validate([
            'pdf' => 'required|mimes:pdf|max:2048',
        ]);

        $pdfPath = $request->file('pdf')->store('pdfs');

        $absolutePdfPath = storage_path('app/' . $pdfPath);

        if (!file_exists($absolutePdfPath)) {
            return response()->json(['error' => 'File not found at: ' . $absolutePdfPath], 404);
        }

        $pdfParser = new Parser();
        $pdf = $pdfParser->parseFile($absolutePdfPath);
        $text = $pdf->getText();
        // Process text to create structured HTML
        $lines = explode("\n", $text);
        $htmlRows = '';

        foreach ($lines as $line) {
            // Clean the line
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            // Split line into columns (adjust splitting logic for your PDF's format)
            $columns = preg_split('/\s{2,}/', $line); // Splitting by at least 2 spaces

            // Construct a table row
            $htmlRows .= '<tr>';
            foreach ($columns as $column) {
                $htmlRows .= '<td style="padding:5px; border:1px solid #ddd;">' . htmlspecialchars($column) . '</td>';
            }
            $htmlRows .= '</tr>';
        }

        // Wrap rows in a table
        $htmlTable = '<table style="border-collapse:collapse; width:100%;">' . $htmlRows . '</table>';

        // Return the HTML view with the generated table
        return view('pdf-html', ['htmlTable' => $htmlTable]);
    }
}
