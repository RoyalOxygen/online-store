<?php

$basePath = dirname(__DIR__);
$envPath = $basePath . DIRECTORY_SEPARATOR . '.env';
$examplePath = $basePath . DIRECTORY_SEPARATOR . '.env.example';

$directories = [
    $basePath . '/storage/app/public',
    $basePath . '/storage/framework/cache/data',
    $basePath . '/storage/framework/sessions',
    $basePath . '/storage/framework/views',
    $basePath . '/storage/logs',
    $basePath . '/bootstrap/cache',
];

foreach ($directories as $directory) {
    if (!is_dir($directory)) {
        @mkdir($directory, 0777, true);
    }
}

if (!is_file($envPath) && is_file($examplePath)) {
    @copy($examplePath, $envPath);
}

if (!is_file($envPath)) {
    return;
}

$envContents = (string) file_get_contents($envPath);
$envUpdated = false;

if (!preg_match('/^APP_KEY=\s*\S+/m', $envContents)) {
    $generatedKey = 'base64:' . base64_encode(random_bytes(32));
    if (preg_match('/^APP_KEY=.*$/m', $envContents)) {
        $envContents = preg_replace('/^APP_KEY=.*$/m', 'APP_KEY=' . $generatedKey, $envContents, 1);
    } else {
        $envContents .= PHP_EOL . 'APP_KEY=' . $generatedKey . PHP_EOL;
    }
    $envUpdated = true;
}

if (preg_match('/^APP_ENV=\s*local\s*$/m', $envContents) && preg_match('/^APP_DEBUG=\s*false\s*$/mi', $envContents)) {
    $envContents = preg_replace('/^APP_DEBUG=.*$/mi', 'APP_DEBUG=true', $envContents, 1);
    $envUpdated = true;
}

if ($envUpdated) {
    @file_put_contents($envPath, $envContents);
}
