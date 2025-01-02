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
    
        $pdfPath = $request->file('pdf')->store('pdfs');
        $absolutePdfPath = storage_path('app/' . $pdfPath);
        // dd($absolutePdfPath);
    
        // Check if the file exists
        if (!file_exists($absolutePdfPath)) {
            return response()->json(['error' => 'File not found!'], 404);
        }
    
        // Use Smalot PDF Parser
        $pdfParser = new \Smalot\PdfParser\Parser();
        $pdf = $pdfParser->parseFile($absolutePdfPath);
        $text = $pdf->getText();
    
        // Example: Parse the text and convert it to HTML table
        $lines = explode("\n", $text);
        $tableData = [];
    
        foreach ($lines as $line) {
            $columns = preg_split('/\s+/', $line);
            if (count($columns) > 1) {
                $tableData[] = $columns;
            }
        }

        // Return the extracted text to a view
        return view('pdf-table', compact('tableData'));
    }
}
