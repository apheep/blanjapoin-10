<?php
// Quick test script untuk check gmaps API
// Pastikan database sudah terkoneksi dengan benar

require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\Merchant;

// Get first merchant with link_gmaps
$merchant = Merchant::whereNotNull('link_gmaps')->first();

if (!$merchant) {
    echo "❌ No merchant with link_gmaps found. Creating test data...\n";
    $merchant = Merchant::first();
    if (!$merchant) {
        echo "❌ No merchants found at all. Please check database.\n";
        exit;
    }
}

echo "✅ Found merchant: " . $merchant->name . " (ID: " . $merchant->id . ")\n";
echo "\n📦 link_gmaps data:\n";
var_dump($merchant->link_gmaps);

echo "\n🔄 Calling getGmapsLocations():\n";
$locations = $merchant->getGmapsLocations();
var_dump($locations);

echo "\n✅ Test complete!\n";
?>
