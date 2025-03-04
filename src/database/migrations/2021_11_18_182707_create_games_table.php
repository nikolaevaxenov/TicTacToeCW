<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Game;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->uuid('id')->default(Game::raw('(UUID())'));;
            $table->timestamps();
            $table->string('field', 9)->default("111111111");
            $table->string('player1')->nullable();
            $table->string('player2')->nullable();
            $table->string('mode');
            $table->boolean('isOpenGame')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
