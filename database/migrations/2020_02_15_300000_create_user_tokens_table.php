<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUserTokensTable extends Migration
{

    /**
     *
     * @var string $table
     */
    private $tableName = 'user_tokens';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->unsignedInteger('user_id')->index();
            $table->string('secret', 36)->unique();
            $table->unsignedTinyInteger('type');
            $table->timestamp('created_at');

            $table->foreign('user_id', DB::getTablePrefix() . 'fk_user_tokens_users_idx')
                ->references('id')
                ->on('users')
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
