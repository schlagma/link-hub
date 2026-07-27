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
        Schema::table('users', function (Blueprint $table) {
            // The stable, IdP-guaranteed-unique subject identifier (OIDC "sub" claim).
            // Nullable/unique so existing rows can be backfilled on their next login.
            $table->string('oidc_sub')->nullable()->unique()->after('username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('oidc_sub');
        });
    }
};
