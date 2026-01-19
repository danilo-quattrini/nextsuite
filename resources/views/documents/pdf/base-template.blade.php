{{-- resources/views/documents/pdf/base-template.blade.php --}}
        <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $settings['title'] ?? 'Document' }}</title>
        <style>
            /* Base styles - always included */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'DejaVu Sans', sans-serif;
                font-size: {{ $settings['font_size'] ?? '12pt' }};
                line-height: 1.6;
                color: #333;
            }

            .page {
                padding: {{ $settings['margins']['top'] ?? 20 }}mm
                         {{ $settings['margins']['right'] ?? 15 }}mm
                         {{ $settings['margins']['bottom'] ?? 20 }}mm
                         {{ $settings['margins']['left'] ?? 15 }}mm;
            }

            .section {
                margin-bottom: 20px;
                page-break-inside: avoid;
            }

            .section-title {
                font-size: 16pt;
                font-weight: bold;
                margin-bottom: 10px;
                color: #2c3e50;
                border-bottom: 2px solid #3498db;
                padding-bottom: 5px;
            }

            /* Table styles */
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 10px 0;
            }

            table th,
            table td {
                padding: 8px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            table th {
                background-color: #f8f9fa;
                font-weight: bold;
            }

            /* List styles */
            ul, ol {
                margin-left: 20px;
                margin-top: 5px;
            }

            li {
                margin-bottom: 5px;
            }

            /* Grid layouts */
            .two-column {
                display: table;
                width: 100%;
            }

            .column {
                display: table-cell;
                width: 50%;
                padding-right: 10px;
            }

            .field-label {
                font-weight: bold;
                color: #555;
            }

            .field-value {
                margin-bottom: 8px;
            }

            /* Custom template CSS */
            @if(isset($settings['custom_css']))
                {!! $settings['custom_css'] !!}
            @endif
        </style>
    </head>
    <body class="page">

    {{-- Render all sections --}}
    @foreach($sections as $section)
        <div class="section">
            {!! $section !!}
        </div>
    @endforeach


    </body>
</html>