<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add missing columns if they don't exist
        if (!Schema::hasColumn('medicines', 'generic_name')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->string('generic_name')->after('name');
            });
        }

        if (!Schema::hasColumn('medicines', 'strength')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->string('strength')->after('generic_name');
            });
        }

        if (!Schema::hasColumn('medicines', 'form')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->string('form')->after('strength');
            });
        }

        if (!Schema::hasColumn('medicines', 'description')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->string('description')->nullable()->after('form');
            });
        }

        if (!Schema::hasColumn('medicines', 'price')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->decimal('price', 10, 2)->nullable()->after('description');
            });
        }

        if (!Schema::hasColumn('medicines', 'stock')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->integer('stock')->default(0)->nullable()->after('price');
            });
        }

        if (!Schema::hasColumn('medicines', 'category')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->string('category')->nullable()->after('stock');
            });
        }

        if (!Schema::hasColumn('medicines', 'manufacturer')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->string('manufacturer')->nullable()->after('category');
            });
        }

        if (!Schema::hasColumn('medicines', 'expiry_date')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->date('expiry_date')->nullable()->after('manufacturer');
            });
        }

        if (!Schema::hasColumn('medicines', 'status')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->boolean('status')->default(true)->nullable()->after('expiry_date');
            });
        }

        // Add foreign key if it doesn't exist
        if (!Schema::hasColumn('medicines', 'pharmacy_id')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->foreignId('pharmacy_id')->constrained('pharmacies')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        // Drop foreign key if it exists
        if (Schema::hasColumn('medicines', 'pharmacy_id')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->dropForeign(['pharmacy_id']);
                $table->dropColumn('pharmacy_id');
            });
        }

        // Drop columns if they exist
        if (Schema::hasColumn('medicines', 'generic_name')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->dropColumn('generic_name');
            });
        }

        if (Schema::hasColumn('medicines', 'strength')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->dropColumn('strength');
            });
        }

        if (Schema::hasColumn('medicines', 'form')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->dropColumn('form');
            });
        }

        if (Schema::hasColumn('medicines', 'description')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }

        if (Schema::hasColumn('medicines', 'price')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->dropColumn('price');
            });
        }

        if (Schema::hasColumn('medicines', 'stock')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->dropColumn('stock');
            });
        }

        if (Schema::hasColumn('medicines', 'category')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }

        if (Schema::hasColumn('medicines', 'manufacturer')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->dropColumn('manufacturer');
            });
        }

        if (Schema::hasColumn('medicines', 'expiry_date')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->dropColumn('expiry_date');
            });
        }

        if (Schema::hasColumn('medicines', 'status')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
