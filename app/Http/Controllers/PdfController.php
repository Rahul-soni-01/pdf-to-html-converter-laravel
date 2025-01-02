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
}
