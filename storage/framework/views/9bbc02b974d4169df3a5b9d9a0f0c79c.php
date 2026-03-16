<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset Request</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 5px; }
        .header { background-color: #007bff; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .header h1 { margin: 0; }
        .content { padding: 20px; }
        .button { display: inline-block; background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; border-top: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Password Reset Request</h1>
        </div>
        
        <div class="content">
            <p>Hello <?php echo e($email); ?>,</p>
            
            <p>We received a request to reset your password. Click the button below to create a new password:</p>
            
            <p style="text-align: center;">
                <a href="<?php echo e($resetLink); ?>" class="button">Reset Password</a>
            </p>
            
            <p>Or copy and paste this link in your browser:</p>
            <p style="background-color: #f9f9f9; padding: 10px; border-radius: 3px; word-break: break-all;">
                <?php echo e($resetLink); ?>

            </p>
            
            <p><strong>Important:</strong> This password reset link expires in 24 hours.</p>
            
            <p>If you didn't request a password reset, please ignore this email.</p>
            
            <p>Best regards,<br>
            EduTrack System</p>
        </div>
        
        <div class="footer">
            <p>&copy; 2025 EduTrack. All rights reserved.</p>
            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\edutrack\resources\views\emails\password-reset.blade.php ENDPATH**/ ?>