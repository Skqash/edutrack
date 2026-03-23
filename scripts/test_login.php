<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Auth;

// Setup session driver to array so Auth can store session data in CLI
config(['session.driver' => 'array']);

$email = 'admin@example.com';
$password = 'password123';

$result = Auth::attempt(['email' => $email, 'password' => $password]);

var_dump([ 'attempt' => $result, 'user' => Auth::user()?->only(['id','email','role']) ]);
