<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            if (!Schema::hasColumn('cars', 'views_count')) {
                $table->unsignedBigInteger('views_count')->default(0)->after('status');
            }
            if (!Schema::hasColumn('cars', 'whatsapp_clicks_count')) {
                $table->unsignedBigInteger('whatsapp_clicks_count')->default(0)->after('views_count');
            }
            if (!Schema::hasColumn('cars', 'phone_clicks_count')) {
                $table->unsignedBigInteger('phone_clicks_count')->default(0)->after('whatsapp_clicks_count');
            }
            if (!Schema::hasColumn('cars', 'shares_count')) {
                $table->unsignedBigInteger('shares_count')->default(0)->after('phone_clicks_count');
            }
            if (!Schema::hasColumn('cars', 'old_price')) {
                $table->decimal('old_price', 12, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('cars', 'sold_price')) {
                $table->decimal('sold_price', 12, 2)->nullable()->after('old_price');
            }
            if (!Schema::hasColumn('cars', 'sold_at')) {
                $table->timestamp('sold_at')->nullable()->after('sold_price');
            }
            if (!Schema::hasColumn('cars', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('status');
            }
        });

        // Expand status enum to support 'sold' and 'archived'
        $driver = DB::getDriverName();
        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("ALTER TABLE cars MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'sold', 'archived') NOT NULL DEFAULT 'pending'");
        } elseif ($driver === 'pgsql') {
            DB::statement("ALTER TABLE cars DROP CONSTRAINT IF EXISTS cars_status_check");
            DB::statement("ALTER TABLE cars ADD CONSTRAINT cars_status_check CHECK (status IN ('pending', 'approved', 'rejected', 'sold', 'archived'))");
        }

        Schema::table('sellers', function (Blueprint $table) {
            if (!Schema::hasColumn('sellers', 'views_count')) {
                $table->unsignedBigInteger('views_count')->default(0)->after('tier');
            }
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
            if (!Schema::hasColumn('notifications', 'seller_id')) {
                $table->unsignedBigInteger('seller_id')->nullable()->after('user_id')->index();
            }
            if (!Schema::hasColumn('notifications', 'type')) {
                $table->string('type', 50)->nullable()->after('seller_id');
            }
            if (!Schema::hasColumn('notifications', 'data')) {
                $table->json('data')->nullable()->after('body');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $columns = [
                'views_count',
                'whatsapp_clicks_count',
                'phone_clicks_count',
                'shares_count',
                'old_price',
                'sold_price',
                'sold_at',
                'rejection_reason',
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('cars', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('sellers', function (Blueprint $table) {
            if (Schema::hasColumn('sellers', 'views_count')) {
                $table->dropColumn('views_count');
            }
        });

        Schema::table('notifications', function (Blueprint $table) {
            foreach (['seller_id', 'type', 'data'] as $col) {
                if (Schema::hasColumn('notifications', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

