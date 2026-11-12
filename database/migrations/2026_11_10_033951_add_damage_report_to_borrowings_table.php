<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->boolean('is_damaged')->default(false)->after('status');
            $table->text('damage_description')->nullable()->after('is_damaged');
            $table->string('damage_photo')->nullable()->after('damage_description');
            $table->timestamp('damage_reported_at')->nullable()->after('damage_photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn(['is_damaged', 'damage_description', 'damage_photo', 'damage_reported_at']);
        });
    }
};
