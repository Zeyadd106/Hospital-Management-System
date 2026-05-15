<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoctorProfileColumns extends Migration
{
    public function up()
    {
        Schema::table('doctor_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('doctor_profiles', 'specialty')) {
                $table->string('specialty')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('doctor_profiles', 'bio')) {
                $table->text('bio')->nullable()->after('specialty');
            }
            if (!Schema::hasColumn('doctor_profiles', 'is_available')) {
                $table->boolean('is_available')->nullable()->default(true)->after('bio');
            }
            if (!Schema::hasColumn('doctor_profiles', 'experience_years')) {
                $table->integer('experience_years')->nullable()->default(0)->after('is_available');
            }
            if (!Schema::hasColumn('doctor_profiles', 'consultation_fee')) {
                $table->decimal('consultation_fee', 10, 2)->nullable()->default(0)->after('experience_years');
            }
        });
    }

    public function down()
    {
        Schema::table('doctor_profiles', function (Blueprint $table) {
            $table->dropColumn(['specialty', 'bio', 'is_available', 'experience_years', 'consultation_fee']);
        });
    }
}
