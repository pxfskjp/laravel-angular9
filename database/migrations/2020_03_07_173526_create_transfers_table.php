<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    /**
     *
     * @var string $tableName
     */
    private $tableName = 'transfers';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('hardware_id')->index();
            $table->tinyInteger('type');
            $table->date('date');
            $table->string('remarks');

            $table->foreign('user_id', DB::getTablePrefix() . 'fk_transfers_users_idx')
                ->references('id')
                ->on('users');
            $table->foreign('hardware_id', DB::getTablePrefix() . 'fk_transfers_hardwares_idx')
                ->references('id')
                ->on('hardwares');
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
