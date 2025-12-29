<?php
$_SERVER['APP_ENV'] = 'local';
$_SERVER['APP_DEBUG'] = '1';

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST INSERT CLICK HISTORY ===\n\n";

try {
    $inserted = \Illuminate\Support\Facades\DB::table('click_history')->insert([
        'merchant_id' => 1,
        'keyword_id' => 'TEST_KEYWORD',
        'clicked_at' => \Carbon\Carbon::now('Asia/Jakarta'),
        'ip_address' => '127.0.0.1',
        'device_id' => 'test-device-' . time(),
        'user_agent' => 'Test Browser',
        'referer' => 'http://localhost',
    ]);
    
    if ($inserted) {
        echo "✅ Click berhasil INSERT ke database\n\n";
        
        // Check hasil insert
        $latest = \Illuminate\Support\Facades\DB::table('click_history')
            ->where('keyword_id', 'TEST_KEYWORD')
            ->latest('clicked_at')
            ->first();
        
        echo "Latest Click:\n";
        echo "- ID: {$latest->id}\n";
        echo "- Merchant: {$latest->merchant_id}\n";
        echo "- Keyword: {$latest->keyword_id}\n";
        echo "- At: {$latest->clicked_at}\n";
        echo "- Device: {$latest->device_id}\n";
    } else {
        echo "❌ Click INSERT gagal\n";
    }
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack Trace:\n" . $e->getTraceAsString() . "\n";
}
