<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUserHardwaresTable extends Migration
{
    /**
     *
     * @var string $tableName
     */
    private $tableName = 'user_hardwares';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->unsignedInteger('user_id')->unique();
            $table->unsignedInteger('hardware_id')->unique();

            $table->foreign('user_id', DB::getTablePrefix() . 'fk_user_hardwares_users_idx')
                ->references('id')
                ->on('users');
            $table->foreign('hardware_id', DB::getTablePrefix() . 'fk_user_hardwares_hardware_idx')
                ->references('id')
                ->on('hardwares')
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
