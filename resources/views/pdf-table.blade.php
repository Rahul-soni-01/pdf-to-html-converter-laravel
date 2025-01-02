<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Data Table</title>
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

    <table border="1">
        <thead>
            <tr>
                <th>Column 1</th>
                <th>Column 2</th>
                <th>Column 3</th>
                <!-- Add more columns as necessary -->
            </tr>
        </thead>
        <tbody>
           
        </tbody>
    </table>
    @foreach ($tableData as $row)
    <div>
        @foreach ($row as $column)
            <td>{{ $column }}</td>
        @endforeach
    </tr>
@endforeach
    
</body>
</html>
