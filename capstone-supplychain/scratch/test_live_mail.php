<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

echo "--- Testing Live Gmail SMTP Delivery ---\n";
echo "Mailer: " . config('mail.default') . "\n";
echo "Host: " . config('mail.mailers.smtp.host') . "\n";
echo "Username: " . config('mail.mailers.smtp.username') . "\n";

try {
    Mail::raw("🔒 Hello! This is a live test OTP security email sent from your Smart Supply Chain System.\n\nYour Verification Code is: 88776655\n\nBest regards,\nSmart Supply Chain Security Team", function ($message) {
        $message->to('jjtlozano15@gmail.com')->subject('🔒 Security Verification Code - Live Test');
    });
    echo "SUCCESS: Live email sent successfully to jjtlozano15@gmail.com!\n";
} catch (\Throwable $e) {
    echo "ERROR: Mail dispatch failed with message:\n" . $e->getMessage() . "\n";
}
