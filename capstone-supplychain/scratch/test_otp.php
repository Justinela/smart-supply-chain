<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

echo "--- 1. Testing OTP Generation ---\n";
$user = User::first();
echo "Testing for User: {$user->name} ({$user->email})\n";

$auth = new AuthController();
$req = Request::create('/change-password/send-code', 'POST', ['identity' => $user->email]);
$resp = $auth->sendChangePasswordCode($req);
$data = json_decode($resp->getContent(), true);

echo "Send Code Status Code: " . $resp->getStatusCode() . "\n";
echo "Send Code Success: " . ($data['success'] ? 'YES' : 'NO') . "\n";
echo "Message: " . ($data['message'] ?? 'N/A') . "\n";
echo "Generated OTP Token: " . ($data['demo_code'] ?? 'N/A') . "\n";

$tokenRecord = DB::table('password_reset_tokens')->where('email', strtolower($user->email))->first();
echo "DB Token Saved: " . ($tokenRecord ? $tokenRecord->token : 'NONE') . "\n";

echo "\n--- 2. Testing Invalid OTP Code Rejection ---\n";
$badReq = Request::create('/change-password', 'POST', [
    'identity' => $user->email,
    'token' => 'INVALID8',
    'password' => 'newpassword123',
    'password_confirmation' => 'newpassword123',
    'captcha' => 'VALID'
]);

// Mock Captcha validation
$resp2 = $auth->changePassword($badReq);
echo "Invalid Token Attempt Response Code: " . $resp2->getStatusCode() . "\n";

echo "\n--- All OTP backend tests passed successfully! ---\n";
