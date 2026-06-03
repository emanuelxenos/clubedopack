<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$packs = App\Models\Pack::all();
foreach ($packs as $pack) {
    echo "Pack {$pack->id}: {$pack->media()->count()} media\n";
}
