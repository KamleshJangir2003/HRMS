<?php

// Simple test to generate salary manually
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Artisan;

// Set current directory to Laravel app
chdir(__DIR__);

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Generating salary for current month...\n";

// Run the salary generation command
Artisan::call('salary:generate-monthly');

echo "Done! Check your salary page.\n";