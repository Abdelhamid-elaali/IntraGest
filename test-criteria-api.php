<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illwarem\Laravel\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::create(
        '/api/criteria?category=geographical', 'GET'
    )
);

echo "HTTP Status: " . $response->getStatusCode() . "\n";
echo "Response: " . $response->getContent() . "\n";
