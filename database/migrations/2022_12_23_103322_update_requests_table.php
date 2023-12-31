<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('requests', function (Blueprint $table): void {
            $table->unsignedInteger('torrent_id')->nullable()->after('filled_hash');

            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnUpdate()->cascadeOnDelete();
        });

        DB::table('requests')
            ->join('torrents', 'requests.filled_hash', 'torrents.info_hash')
            ->update([
                'torrent_id'          => DB::raw('torrents.id'),
                'requests.updated_at' => DB::raw('requests.updated_at'),
            ]);

        Schema::table('requests', function (Blueprint $table): void {
            $table->dropIndex('filled_hash');
            $table->dropColumn('filled_hash');
        });
    }
};
