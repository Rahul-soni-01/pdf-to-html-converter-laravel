<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Data Table</title>
    <!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

<!-- jQuery (required by DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>PDF Data in Table Format</h1>

    <div id="pdfContent">
        {!! $htmlTable !!}
    </div>
    <script>
        $(document).ready(function() {
            $('#bankStatementTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                responsive: true,
            });
        });
    </script>
</body>
</html>
