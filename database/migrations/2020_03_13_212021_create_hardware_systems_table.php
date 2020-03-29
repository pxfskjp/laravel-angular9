<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateHardwareSystemsTable extends Migration
{
    /**
     *
     * @var string $tableName
     */
    private $tableName = 'hardware_systems';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->unsignedInteger('hardware_id')->unique();
            $table->unsignedInteger('system_id')->index();

            $table->foreign('hardware_id', DB::getTablePrefix() . 'fk_hardware_systems_hardware_idx')
                ->references('id')
                ->on('hardwares')
                ->onDelete('CASCADE');

            $table->foreign('system_id', DB::getTablePrefix() . 'fk_hardware_systems_system_idx')
                ->references('id')
                ->on('systems')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
