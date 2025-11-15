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
            $table->string('username')->nullable()->unique()->after('email');
            $table->string('role')->default('teacher')->after('username');
            $table->string('short_name')->nullable()->after('role');
            $table->string('organization_name')->nullable()->after('short_name');
            $table->string('avatar_url')->nullable()->after('organization_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'role',
                'short_name',
                'organization_name',
                'avatar_url',
            ]);
        });
    }
};
