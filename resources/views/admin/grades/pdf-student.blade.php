<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Certificate of Records - {{ $student->user->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            background: white;
        }

        .container {
            width: 100%;
            max-width: 8.5in;
            margin: 0 auto;
            padding: 0.5in;
            background: white;
        }

        /* HEADER */
        .header {
            text-align: center;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .institution-name {
            font-size: 18px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 5px;
        }

        .certificate-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin: 15px 0 10px 0;
            text-decoration: underline;
        }

        /* STUDENT INFO SECTION */
        .info-section {
            margin-bottom: 25px;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            width: 35%;
            padding: 8px;
            font-weight: bold;
            border: 1px solid #999;
            background-color: #f0f0f0;
        }

        .info-value {
            display: table-cell;
            width: 65%;
            padding: 8px;
            border: 1px solid #999;
            border-left: none;
        }

        /* GRADES TABLE */
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .grades-table th {
            background-color: #333;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 12px;
            font-weight: bold;
            border: 1px solid #333;
        }

        .grades-table td {
            padding: 10px 12px;
            border: 1px solid #999;
            font-size: 11px;
        }

        .grades-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .grades-table .no-data {
            text-align: center;
            color: #999;
            font-style: italic;
        }

        /* SUMMARY SECTION */
        .summary-section {
            margin-top: 25px;
            border-top: 2px solid #333;
            padding-top: 15px;
        }

        .summary-label {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .summary-row {
            display: table-row;
        }

        .summary-item {
            display: table-cell;
            width: 50%;
            padding: 10px;
            border: 1px solid #999;
            text-align: center;
            background-color: #f5f5f5;
        }

        .summary-item-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }

        .summary-item-value {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-top: 5px;
        }

        /* FOOTER */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #999;
            padding-top: 15px;
        }

        .footer-text {
            margin: 5px 0;
        }

        /* PAGE BREAK */
        @page {
            size: 8.5in 11in;
            margin: 0.5in;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .container {
                max-width: 100%;
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- HEADER -->
        <div class="header">
            <div class="institution-name">CEBU PACIFIC SHIPPING UNIVERSITY</div>
            <div class="certificate-title">CERTIFICATE OF RECORDS (CRC)</div>
        </div>

        <!-- STUDENT INFORMATION -->
        <div class="info-section">
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Student Name</div>
                    <div class="info-value">{{ $student->user->name ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Student ID</div>
                    <div class="info-value">{{ $student->student_id ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Class/Section</div>
                    <div class="info-value">{{ $class->class_name ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Academic Year</div>
                    <div class="info-value">{{ date('Y') }}</div>
                </div>
            </div>
        </div>

        <!-- GRADES TABLE -->
        @if ($grades->count() > 0)
            <table class="grades-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th style="width: 15%;">Midterm</th>
                        <th style="width: 15%;">Finals</th>
                        <th style="width: 20%;">Total Average</th>
                        <th>Teacher</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($grades as $grade)
                        <tr>
                            <td>{{ $grade->subject->subject_name ?? 'N/A' }}</td>
                            <td style="text-align: center;">
                                @if ($grade->midterm_exam)
                                    {{ number_format($grade->midterm_exam, 2) }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if ($grade->final_exam)
                                    {{ number_format($grade->final_exam, 2) }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if ($grade->grade_point)
                                    <strong>{{ number_format($grade->grade_point, 2) }}</strong>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $grade->teacher->user->name ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="grades-table">
                <div class="no-data">No grade records found for this student.</div>
            </div>
        @endif

        <!-- SUMMARY SECTION -->
        <div class="summary-section">
            <div class="summary-label">Summary of Grades</div>
            <div class="summary-grid">
                <div class="summary-row">
                    <div class="summary-item">
                        <div class="summary-item-label">Average Midterm</div>
                        <div class="summary-item-value">
                            @if ($midtermAvg > 0)
                                {{ number_format($midtermAvg, 2) }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-item-label">Average Finals</div>
                        <div class="summary-item-value">
                            @if ($finalAvg > 0)
                                {{ number_format($finalAvg, 2) }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                </div>
                <div class="summary-row">
                    <div class="summary-item" style="grid-column: 1 / -1;">
                        <div class="summary-item-label">Total Average Grade Point</div>
                        <div class="summary-item-value" style="font-size: 16px; color: #667eea;">
                            @if ($totalAverage > 0)
                                {{ number_format($totalAverage, 2) }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div class="footer-text">This is an official academic record of {{ config('app.name') }}.</div>
            <div class="footer-text">Generated on {{ date('F d, Y') }}</div>
        </div>
    </div>
</body>

</html>
