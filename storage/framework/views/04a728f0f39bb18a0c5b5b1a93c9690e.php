<?php
    // Base64 encode the logo for PDF compatibility
    $logoPath = public_path('images/cpsu-logo.jpg');
    $logoBase64 = '';
    if (file_exists($logoPath)) {
        $logoData = file_get_contents($logoPath);
        $logoBase64 = 'data:image/jpeg;base64,' . base64_encode($logoData);
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Certificate of Records - <?php echo e($student->user->name); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #333;
            line-height: 1.6;
            background: white;
            position: relative;
        }

        .container {
            width: 100%;
            max-width: 8.5in;
            margin: 0 auto;
            padding: 0.5in;
            background: white;
            position: relative;
        }

        /* WATERMARK */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(26, 84, 144, 0.05);
            font-weight: bold;
            z-index: -1;
            pointer-events: none;
        }

        /* HEADER */
        .header {
            text-align: center;
            border-bottom: 3px solid #1a5490;
            padding-bottom: 30px;
            margin-bottom: 30px;
            position: relative;
        }

        .logo-container {
            margin-bottom: 20px;
            text-align: center;
        }

        .logo {
            width: 100px;
            height: 100px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }

        .institution-name {
            font-size: 20px;
            font-weight: bold;
            color: #1a5490;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .institution-address {
            font-size: 11px;
            color: #555;
            margin-bottom: 10px;
            font-style: italic;
        }

        .certificate-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin: 20px 0 10px 0;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .certificate-subtitle {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
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

        /* SIGNATURE SECTION */
        .signature-section {
            margin-top: 40px;
            border-top: 2px solid #1a5490;
            padding-top: 20px;
        }

        .signature-grid {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }

        .signature-item {
            flex: 1;
            text-align: center;
        }

        .signature-label {
            font-size: 11px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            height: 40px;
            margin-bottom: 5px;
        }

        .signature-name {
            font-size: 12px;
            font-weight: bold;
            color: #1a5490;
            margin-bottom: 3px;
        }

        .signature-title {
            font-size: 10px;
            color: #666;
            font-style: italic;
        }

        .signature-date {
            font-size: 12px;
            font-weight: bold;
            color: #333;
            padding: 10px;
            border: 1px solid #999;
            background-color: #f5f5f5;
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
    <div class="watermark">CPSU</div>
    <div class="container">
        <!-- HEADER -->
        <div class="header">
            <div class="logo-container">
                <img src="<?php echo e($logoBase64); ?>" alt="CPSU Logo" class="logo">
            </div>
            <div class="institution-name">CENTRAL PHILIPPINES STATE UNIVERSITY</div>
            <div class="institution-address">College of Engineering and Information Technology</div>
            <div class="certificate-title">Certificate of Records (CRC)</div>
            <div class="certificate-subtitle">Official Academic Record</div>
        </div>

        <!-- STUDENT INFORMATION -->
        <div class="info-section">
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Student Name</div>
                    <div class="info-value"><?php echo e($student->user->name ?? 'N/A'); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Student ID</div>
                    <div class="info-value"><?php echo e($student->student_id ?? 'N/A'); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Class/Section</div>
                    <div class="info-value"><?php echo e($class->class_name ?? 'N/A'); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Academic Year</div>
                    <div class="info-value"><?php echo e(date('Y')); ?></div>
                </div>
            </div>
        </div>

        <!-- GRADES TABLE -->
        <?php if($grades->count() > 0): ?>
            <table class="grades-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th style="width: 20%;">Midterm</th>
                        <th style="width: 20%;">Finals</th>
                        <th style="width: 20%;">Total Average</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($grade->subject->subject_name ?? 'N/A'); ?></td>
                            <td style="text-align: center;">
                                <?php if($grade->midterm_exam): ?>
                                    <?php echo e(number_format($grade->midterm_exam, 2)); ?>

                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php if($grade->final_exam): ?>
                                    <?php echo e(number_format($grade->final_exam, 2)); ?>

                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php if($grade->grade_point): ?>
                                    <strong><?php echo e(number_format($grade->grade_point, 2)); ?></strong>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="grades-table">
                <div class="no-data">No grade records found for this student.</div>
            </div>
        <?php endif; ?>

        <!-- SUMMARY SECTION -->
        <div class="summary-section">
            <div class="summary-label">Summary of Grades</div>
            <div class="summary-grid">
                <div class="summary-row">
                    <div class="summary-item">
                        <div class="summary-item-label">Average Midterm</div>
                        <div class="summary-item-value">
                            <?php if($midtermAvg > 0): ?>
                                <?php echo e(number_format($midtermAvg, 2)); ?>

                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-item-label">Average Finals</div>
                        <div class="summary-item-value">
                            <?php if($finalAvg > 0): ?>
                                <?php echo e(number_format($finalAvg, 2)); ?>

                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="summary-row">
                    <div class="summary-item" style="grid-column: 1 / -1;">
                        <div class="summary-item-label">Total Average Grade Point</div>
                        <div class="summary-item-value" style="font-size: 16px; color: #667eea;">
                            <?php if($totalAverage > 0): ?>
                                <?php echo e(number_format($totalAverage, 2)); ?>

                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SIGNATURE SECTION -->
        <div class="signature-section">
            <div class="signature-grid">
                <div class="signature-item">
                    <div class="signature-label">Prepared by:</div>
                    <div class="signature-line"></div>
                    <div class="signature-name">Academic Records Officer</div>
                    <div class="signature-title">Office of the Registrar</div>
                </div>
                <div class="signature-item">
                    <div class="signature-label">Certified correct by:</div>
                    <div class="signature-line"></div>
                    <div class="signature-name">University Registrar</div>
                    <div class="signature-title">Central Philippines State University</div>
                </div>
                <div class="signature-item">
                    <div class="signature-label">Date Issued:</div>
                    <div class="signature-date"><?php echo e(date('F j, Y')); ?></div>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div class="footer-text"><strong>OFFICIAL ACADEMIC RECORD</strong> - This document is a certified true copy of the student's academic record.</div>
            <div class="footer-text">Central Philippines State University • College of Engineering and Information Technology</div>
            <div class="footer-text">Generated on: <?php echo e(date('F d, Y g:i A')); ?> • Reference #: CRC-<?php echo e($student->student_id ?? '0000'); ?>-<?php echo e(date('Y-m')); ?></div>
            <div class="footer-text" style="margin-top: 10px; font-size: 9px; color: #999;">
                <em>This document is valid only with the official seal and signature of the University Registrar.</em>
            </div>
        </div>
    </div>
</body>

</html>
<?php /**PATH C:\laragon\www\edutrack\resources\views\admin\grades\pdf-student.blade.php ENDPATH**/ ?>