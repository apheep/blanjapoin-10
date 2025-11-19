<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Keyword;
use Illuminate\Support\Facades\Storage;

// Get keywords with merchant
$keywords = Keyword::with('merchant')->where('merchant_key', 1)->first();

if ($keywords && $keywords->merchant) {
    echo "Merchant: " . $keywords->merchant->nama_merchant . "\n";
    echo "Logo: " . $keywords->merchant->logo_merchant . "\n";
    echo "Logo exists in storage: " . (Storage::disk('public')->exists($keywords->merchant->logo_merchant) ? 'YES' : 'NO') . "\n";
} else {
    echo "No keyword found\n";
}

$merchant = \App\Models\Merchant::first();
if ($merchant) {
    echo "\nFirst Merchant: " . $merchant->nama_merchant . "\n";
    echo "Logo field: " . ($merchant->logo_merchant ?? 'NULL') . "\n";
}
