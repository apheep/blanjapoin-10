<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migration ini mengubah struktur link_gmap dari single link ke multiple links
     * - Mengubah kolom link_gmap menjadi JSON array
     * - Menambah kolom link_gmaps_radius untuk menyimpan radius per location (JSON)
     */
    public function up(): void
    {
        Schema::table('merchants', function (Blueprint $table) {
            // Menambah kolom baru untuk menyimpan multiple gmaps links dengan radius masing-masing
            // Format: [{"link": "url", "radius": 500}, {"link": "url", "radius": 1000}]
            $table->json('link_gmaps')->nullable()->after('link_gmap')->comment('Multiple Google Maps links with their radius');
        });

        // Migrate data dari link_gmap ke link_gmaps jika ada
        // Setiap merchant yang memiliki link_gmap single akan dikonversi ke format array
        $merchants = DB::table('merchants')->whereNotNull('link_gmap')->get();
        
        foreach ($merchants as $merchant) {
            $linkGmaps = [
                [
                    'link' => $merchant->link_gmap,
                    'radius' => $merchant->radius ?? null
                ]
            ];
            
            DB::table('merchants')
                ->where('id', $merchant->id)
                ->update(['link_gmaps' => json_encode($linkGmaps)]);
        }

        // Update fillable di model jika perlu
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('merchants', function (Blueprint $table) {
            $table->dropColumn('link_gmaps');
        });
    }
};
