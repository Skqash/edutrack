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
    <title>Final Grades - <?php echo e($student->user->name); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: white;
        }

        .container {
            max-width: 8.5in;
            margin: 0 auto;
            padding: 20px;
        }

        /* WATERMARK */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(26, 84, 144, 0.1);
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
            font-size: 14px;
            color: #555;
            margin-bottom: 15px;
        }

        .certificate-title {
            font-size: 24px;
            font-weight: bold;
            color: #1a5490;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .certificate-subtitle {
            font-size: 16px;
            color: #666;
            font-style: italic;
        }

        .term-indicator {
            background: #fff3e0;
            color: #f57c00;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            border: 2px solid #f57c00;
        }

        /* STUDENT INFORMATION */
        .info-section {
            margin-bottom: 20px;
        }

        .info-grid {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 10px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-column {
            flex: 1;
            text-align: center;
        }

        .info-label {
            font-weight: bold;
            color: #495057;
            white-space: nowrap;
            font-size: 11px;
            margin-bottom: 5px;
        }

        .info-value {
            color: #212529;
            font-weight: 500;
            font-size: 12px;
        }

        /* GRADES TABLE */
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .grades-table th {
            background: #1a5490;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            border: 1px solid #1a5490;
        }

        .grades-table td {
            padding: 10px 8px;
            border: 1px solid #dee2e6;
            font-size: 11px;
        }

        .grades-table tr:nth-child(even) {
            background: #f8f9fa;
        }

        .grades-table tr:hover {
            background: #fff3e0;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }

        /* FOOTER */
        .footer {
            margin-top: 20px;
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
            margin-top: 20px;
            border-top: 2px solid #1a5490;
            padding-top: 15px;
        }

        .signature-grid {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .signature-box {
            text-align: center;
            min-width: 200px;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            margin-bottom: 5px;
            height: 40px;
        }

        .signature-title {
            font-size: 11px;
            color: #666;
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
            <div class="certificate-title">Final Grade Report</div>
            <div class="certificate-subtitle">Official Academic Record - Finals</div>
        </div>

        <!-- TERM INDICATOR -->
        <div class="term-indicator">
            FINAL GRADES - <?php echo e(date('Y')); ?> Academic Year
        </div>

        <!-- STUDENT INFORMATION -->
        <div class="info-section">
            <div class="info-grid">
                <!-- First Row: Labels -->
                <div class="info-row">
                    <div class="info-column">
                        <div class="info-label">Student Name</div>
                    </div>
                    <div class="info-column">
                        <div class="info-label">Student ID</div>
                    </div>
                    <div class="info-column">
                        <div class="info-label">Class/Section</div>
                    </div>
                    <div class="info-column">
                        <div class="info-label">Academic Year</div>
                    </div>
                </div>
                <!-- Second Row: Values -->
                <div class="info-row">
                    <div class="info-column">
                        <div class="info-value"><?php echo e($student->user->name ?? 'N/A'); ?></div>
                    </div>
                    <div class="info-column">
                        <div class="info-value"><?php echo e($student->student_id ?? 'N/A'); ?></div>
                    </div>
                    <div class="info-column">
                        <div class="info-value"><?php echo e($class->class_name ?? 'N/A'); ?></div>
                    </div>
                    <div class="info-column">
                        <div class="info-value"><?php echo e(date('Y')); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FINAL GRADES TABLE -->
        <?php if($grades->count() > 0): ?>
            <table class="grades-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th style="width: 25%;">Subject Code</th>
                        <th style="width: 25%;">Final Grade</th>
                        <th style="width: 15%;">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($grade->subject->subject_name ?? 'N/A'); ?></td>
                            <td><?php echo e($grade->subject->subject_code ?? 'N/A'); ?></td>
                            <td style="text-align: center; font-weight: bold;">
                                <?php if($grade->final_exam): ?>
                                    <?php if($grade->final_exam >= 75): ?>
                                        <span style="color: #28a745;"><?php echo e(number_format($grade->final_exam, 2)); ?></span>
                                    <?php else: ?>
                                        <span style="color: #dc3545;"><?php echo e(number_format($grade->final_exam, 2)); ?></span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span style="color: #6c757d;">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php if($grade->final_exam): ?>
                                    <?php if($grade->final_exam >= 75): ?>
                                        <span class="badge" style="background: #28a745; color: white;">PASSED</span>
                                    <?php else: ?>
                                        <span class="badge" style="background: #dc3545; color: white;">FAILED</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge" style="background: #6c757d; color: white;">NO GRADE</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="grades-table">
                <div class="no-data">No final grade records found for this student.</div>
            </div>
        <?php endif; ?>

        <!-- FOOTER -->
        <div class="footer">
            <div class="footer-text">CENTRAL PHILIPPINES STATE UNIVERSITY</div>
            <div class="footer-text">College of Engineering and Information Technology</div>
            <div class="footer-text">Generated on: <?php echo e(date('F j, Y g:i A')); ?></div>
            <div class="footer-text">This is an official final grade report. For verification, please contact the Registrar's Office.</div>
        </div>

        <!-- SIGNATURE SECTION -->
        <div class="signature-section">
            <div class="signature-grid">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-title">Instructor's Signature</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-title">Department Head</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-title">Registrar's Signature</div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<?php /**PATH C:\laragon\www\edutrack\resources\views\admin\grades\pdf-finals.blade.php ENDPATH**/ ?>