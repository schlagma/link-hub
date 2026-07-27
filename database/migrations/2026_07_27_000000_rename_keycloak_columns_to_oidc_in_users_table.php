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
            $table->renameColumn('keycloak_token', 'oidc_token');
            $table->renameColumn('keycloak_refresh_token', 'oidc_refresh_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('oidc_token', 'keycloak_token');
            $table->renameColumn('oidc_refresh_token', 'keycloak_refresh_token');
        });
    }
};
