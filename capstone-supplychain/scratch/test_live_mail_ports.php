<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

echo "--- Testing Full sendChangePasswordCode Endpoint with Live Gmail SMTP ---\n";

$auth = new AuthController();
$req = Request::create('/change-password/send-code', 'POST', ['identity' => 'jjtlozano15@gmail.com']);
$resp = $auth->sendChangePasswordCode($req);

echo "HTTP Status Code: " . $resp->getStatusCode() . "\n";
echo "Response Body: " . $resp->getContent() . "\n";
