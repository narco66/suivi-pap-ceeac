<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$exitCode = $kernel->call('migrate', ['--force' => true]);

echo "\nMigration terminée avec le code : " . $exitCode . "\n";



