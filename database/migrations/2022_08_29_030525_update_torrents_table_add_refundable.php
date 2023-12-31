<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('torrents', function (Blueprint $table): void {
            $table->boolean('refundable')->after('doubleup')
                ->default(0);
        });
    }
};
