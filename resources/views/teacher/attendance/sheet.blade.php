<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Attendance Sheet - {{ $class->class_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            padding: 10mm;
            background-color: #f5f5f5;
        }

        .controls {
            display: none;
            background: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            gap: 15px;
        }

        .controls.visible {
            display: flex;
        }

        .control-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .control-group label {
            font-weight: bold;
            min-width: 80px;
        }

        .control-group input,
        .control-group select {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 12px;
        }

        .print-button {
            margin-bottom: 15px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .print-button:hover {
            background-color: #0056b3;
        }

        .page {
            max-width: 8.5in;
            width: 100%;
            height: 11in;
            margin: 0 auto;
            padding: 20px;
            background: white;
            page-break-after: always;
            border: 1px solid #ccc;
            position: relative;
        }

        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .logo-section {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 5px;
        }

        .logo {
            width: 50px;
            height: 50px;
            margin-right: 10px;
            object-fit: contain;
        }

        .university-name {
            font-weight: bold;
            font-size: 13px;
            line-height: 1.3;
        }

        .university-location {
            font-size: 10px;
            color: #555;
        }

        .page-title {
            font-weight: bold;
            font-size: 12px;
            margin-top: 3px;
        }

        /* Info Section */
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 10px;
            flex-wrap: wrap;
        }

        .info-row {
            display: flex;
            gap: 5px;
            flex: 1;
            min-width: 200px;
        }

        .info-label {
            font-weight: bold;
            min-width: 60px;
        }

        .info-value {
            flex: 1;
            border-bottom: 1px solid #000;
            padding: 0 3px;
        }

        .date-field {
            min-width: 80px;
        }

        /* Table Section */
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9px;
        }

        .attendance-table thead {
            background-color: #f0f0f0;
            border: 1px solid #000;
        }

        .attendance-table th {
            border: 1px solid #000;
            padding: 5px 2px;
            text-align: left;
            font-weight: bold;
            height: 20px;
        }

        .attendance-table td {
            border: 1px solid #000;
            padding: 4px 2px;
            min-height: 50px;
            vertical-align: top;
            position: relative;
        }

        .row-number {
            width: 25px;
            text-align: center;
            font-weight: bold;
        }

        .student-name {
            width: auto;
            text-align: left;
            overflow: hidden;
        }

        .signature-space {
            width: 80px;
            border: none;
            position: relative;
            text-align: center;
            font-size: 8px;
            color: #999;
        }

        .signature-space img {
            max-width: 70px;
            max-height: 40px;
            object-fit: contain;
        }

        /* Two Column Layout */
        .table-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }

        .table-column {
            flex: 1;
        }

        .table-column table {
            width: 100%;
        }

        /* Remarks Section */
        .remarks-section {
            margin-top: 10px;
            border-top: 1px solid #000;
            padding-top: 5px;
            font-size: 9px;
        }

        .remarks-label {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .remarks-lines {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .remark-line {
            border-bottom: 1px solid #000;
            height: 12px;
        }

        /* Footer */
        .footer {
            margin-top: 8px;
            padding-top: 5px;
            border-top: 1px solid #999;
            font-size: 8px;
            color: #666;
            display: flex;
            justify-content: space-between;
        }

        .doc-control {
            text-align: left;
        }

        .effective-date {
            text-align: right;
        }

        /* Print Styles */
        @media print {
            body {
                padding: 0;
                margin: 0;
                background: white;
            }

            .no-print {
                display: none;
            }

            .page {
                border: none;
                page-break-after: always;
                margin: 0;
                padding: 15mm;
            }
        }
    </style>
</head>

<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button class="print-button" onclick="toggleControls()">
            <i class="fas fa-cog"></i> Select Date & Term
        </button>
        <button class="print-button" onclick="window.print()" style="background-color: #28a745;">
            <i class="fas fa-print"></i> Print Attendance Sheet
        </button>
    </div>

    {{-- Date/Term Controls --}}
    <div class="controls" id="controls">
        <div class="control-group">
            <label for="sheetDate">Select Date:</label>
            <input type="date" id="sheetDate" value="{{ $date }}" max="{{ now()->format('Y-m-d') }}">
        </div>
        <div class="control-group">
            <label for="sheetTerm">Select Term:</label>
            <select id="sheetTerm">
                <option value="Midterm" {{ $term === 'Midterm' ? 'selected' : '' }}>Midterm</option>
                <option value="Final" {{ $term === 'Final' ? 'selected' : '' }}>Final</option>
            </select>
        </div>
        <div class="control-group">
            <button class="print-button" onclick="loadSheet()" style="background-color: #17a2b8;">
                Load Sheet
            </button>
        </div>
    </div>

    @php
        // Sort students alphabetically by last name, then first name
        $students = $students->sortBy([
            ['last_name', 'asc'],
            ['first_name', 'asc']
        ])->values();
        
        $totalStudents = count($students);
        
        // Always show 25 rows in first section, 25 in second (if needed)
        $studentsPerRow = 25;
        $rowsPerColumn = 13; // 13 rows per column (13 + 12 = 25)
        
        // Split students into first 25 and next 25
        $firstRowStudents = $students->slice(0, $studentsPerRow);
        $secondRowStudents = $students->slice($studentsPerRow, $studentsPerRow);
    @endphp

    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <img src="/images/CPSU.png" alt="CPSU Logo" class="logo">
                <div>
                    <div class="university-name">CENTRAL PHILIPPINES STATE UNIVERSITY</div>
                    <div class="university-location">Kabankalan, Negros Occidental</div>
                </div>
            </div>
            <div class="page-title">CLASS ATTENDANCE SHEET</div>
        </div>

        <!-- Course Info -->
        <div class="info-section">
            <div class="info-row">
                <div class="info-label">Subject:</div>
                <div class="info-value" style="min-width: 200px;">{{ $class->subject->subject_name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Course:</div>
                <div class="info-value date-field">{{ $class->course->program_name ?? 'N/A' }}</div>
            </div>
        </div>

        <div class="info-section">
            <div class="info-row">
                <div class="info-label">Date:</div>
                <div class="info-value date-field">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Term:</div>
                <div class="info-value date-field"><strong>{{ $term }}</strong></div>
            </div>
        </div>

        <div class="info-section">
            <div class="info-row">
                <div class="info-label">Time Schedule:</div>
                <div class="info-value" style="min-width: 150px;">{{ $class->schedule ?? '__________' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Faculty in-charge:</div>
                <div class="info-value">{{ $class->teacher->name ?? '__________' }}</div>
            </div>
        </div>

        <!-- Attendance Table - First Row (1-25) -->
        <div class="table-wrapper">
            <!-- Left Column (1-13) -->
            <div class="table-column">
                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th class="row-number">No.</th>
                            <th class="student-name">Name<br>(Last, First, Middle Initial)</th>
                            <th class="signature-space">E-Signature</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 1; $i <= 13; $i++)
                            @php
                                $student = $firstRowStudents->get($i - 1);
                                $attendance = $student ? ($attendanceRecords[$student->id] ?? null) : null;
                            @endphp
                            <tr>
                                <td class="row-number">{{ $i }}</td>
                                <td class="student-name">
                                    @if ($student)
                                        {{ strtoupper($student->last_name ?? '') }},
                                        {{ ucfirst($student->first_name ?? '') }}
                                        @if ($student->middle_name)
                                            {{ strtoupper(substr($student->middle_name, 0, 1)) }}.
                                        @endif
                                    @endif
                                </td>
                                <td class="signature-space">
                                    @if ($attendance && $attendance->e_signature)
                                        <img src="{{ $attendance->e_signature }}" alt="Signature">
                                    @elseif ($attendance)
                                        <span style="color: #ccc;">(No signature)</span>
                                    @endif
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            <!-- Right Column (14-25) -->
            <div class="table-column">
                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th class="row-number">No.</th>
                            <th class="student-name">Name<br>(Last, First, Middle Initial)</th>
                            <th class="signature-space">E-Signature</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 14; $i <= 25; $i++)
                            @php
                                $student = $firstRowStudents->get($i - 1);
                                $attendance = $student ? ($attendanceRecords[$student->id] ?? null) : null;
                            @endphp
                            <tr>
                                <td class="row-number">{{ $i }}</td>
                                <td class="student-name">
                                    @if ($student)
                                        {{ strtoupper($student->last_name ?? '') }},
                                        {{ ucfirst($student->first_name ?? '') }}
                                        @if ($student->middle_name)
                                            {{ strtoupper(substr($student->middle_name, 0, 1)) }}.
                                        @endif
                                    @endif
                                </td>
                                <td class="signature-space">
                                    @if ($attendance && $attendance->e_signature)
                                        <img src="{{ $attendance->e_signature }}" alt="Signature">
                                    @elseif ($attendance)
                                        <span style="color: #ccc;">(No signature)</span>
                                    @endif
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Second Row (26-50) - Only show if there are more than 25 students -->
        @if ($secondRowStudents->count() > 0)
            <div class="table-wrapper" style="margin-top: 20px;">
                <!-- Left Column (26-38) -->
                <div class="table-column">
                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th class="row-number">No.</th>
                                <th class="student-name">Name<br>(Last, First, Middle Initial)</th>
                                <th class="signature-space">E-Signature</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 26; $i <= 38; $i++)
                                @php
                                    $student = $secondRowStudents->get($i - 26);
                                    $attendance = $student ? ($attendanceRecords[$student->id] ?? null) : null;
                                @endphp
                                <tr>
                                    <td class="row-number">{{ $i }}</td>
                                    <td class="student-name">
                                        @if ($student)
                                            {{ strtoupper($student->last_name ?? '') }},
                                            {{ ucfirst($student->first_name ?? '') }}
                                            @if ($student->middle_name)
                                                {{ strtoupper(substr($student->middle_name, 0, 1)) }}.
                                            @endif
                                        @endif
                                    </td>
                                    <td class="signature-space">
                                        @if ($attendance && $attendance->e_signature)
                                            <img src="{{ $attendance->e_signature }}" alt="Signature">
                                        @elseif ($attendance)
                                            <span style="color: #ccc;">(No signature)</span>
                                        @endif
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>

                <!-- Right Column (39-50) -->
                <div class="table-column">
                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th class="row-number">No.</th>
                                <th class="student-name">Name<br>(Last, First, Middle Initial)</th>
                                <th class="signature-space">E-Signature</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 39; $i <= 50; $i++)
                                @php
                                    $student = $secondRowStudents->get($i - 26);
                                    $attendance = $student ? ($attendanceRecords[$student->id] ?? null) : null;
                                @endphp
                                <tr>
                                    <td class="row-number">{{ $i }}</td>
                                    <td class="student-name">
                                        @if ($student)
                                            {{ strtoupper($student->last_name ?? '') }},
                                            {{ ucfirst($student->first_name ?? '') }}
                                            @if ($student->middle_name)
                                                {{ strtoupper(substr($student->middle_name, 0, 1)) }}.
                                            @endif
                                        @endif
                                    </td>
                                    <td class="signature-space">
                                        @if ($attendance && $attendance->e_signature)
                                            <img src="{{ $attendance->e_signature }}" alt="Signature">
                                        @elseif ($attendance)
                                            <span style="color: #ccc;">(No signature)</span>
                                        @endif
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Remarks Section -->
        <div class="remarks-section">
            <div class="remarks-label">Remarks/Notes:</div>
            <div class="remarks-lines">
                <div class="remark-line"></div>
                <div class="remark-line"></div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="doc-control">Doc Control Code: CPSU-F-VPAA-13 | Class: {{ $class->class_name }}</div>
            <div class="effective-date">Date: {{ \Carbon\Carbon::parse($date)->format('M d, Y') }} | Page No. 1 of 1
            </div>
        </div>
    </div>

    <script>
        function toggleControls() {
            const controls = document.getElementById('controls');
            controls.classList.toggle('visible');
        }

        function loadSheet() {
            const date = document.getElementById('sheetDate').value;
            const term = document.getElementById('sheetTerm').value;
            const classId = '{{ $class->id }}';

            // Redirect to the same sheet with new parameters
            window.location.href = `{{ route('teacher.attendance.sheet', ['classId' => 'CLASS_ID']) }}`.replace('CLASS_ID',
                classId) + `?date=${date}&term=${term}`;
        }
    </script>
</body>

</html>
