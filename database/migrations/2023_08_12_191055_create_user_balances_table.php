<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBalancesTable extends Migration
{
    public function up()
    {
        Schema::create('user_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->decimal('balance', 10, 2);
            $table->timestamps();

            $table->unique(['user_id']);
            $table->index(['balance']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_balances');
    }
}
