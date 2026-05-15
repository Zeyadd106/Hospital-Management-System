    <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('health_screening_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('health_screening_bookings', 'screening_id')) {
                $table->renameColumn('screening_id', 'health_screening_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('health_screening_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('health_screening_bookings', 'health_screening_id')) {
                $table->renameColumn('health_screening_id', 'screening_id');
            }
        });
    }
};
