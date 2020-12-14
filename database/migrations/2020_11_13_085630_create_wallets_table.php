<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration {

    public function up() {

        Schema::create(
            'wallets',
            function (Blueprint $table) {

                $table->id();
                $table->decimal('balance');
                $table->foreignId('user_id')->constrained();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            }
        );
    }

    public function down() {

        Schema::dropIfExists('wallets');
    }
}
