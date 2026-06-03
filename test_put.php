<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::create(
        '/dashboard/packs/1', // assuming pack id 1 exists
        'POST',
        [
            '_method' => 'PUT',
            'title' => 'Test',
            'category_id' => 1,
            'price' => 10,
        ],
        [],
        [
            'cover_image' => new \Illuminate\Http\UploadedFile(
                __DIR__.'/public/icon.png',
                'icon.png',
                'image/png',
                null,
                true
            )
        ]
    )
);

echo $response->getContent();
