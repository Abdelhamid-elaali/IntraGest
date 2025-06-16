<?php

$url = 'http://127.0.0.1:8000/api/criteria?category=geographical';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

echo "HTTP Status: $httpCode\n";
echo "Response: " . json_encode(json_decode($response, true), JSON_PRETTY_PRINT) . "\n";
