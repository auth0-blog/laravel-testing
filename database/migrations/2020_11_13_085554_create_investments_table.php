<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestmentsTable extends Migration {

    public function up() {

        Schema::create(
            'investments',
            function (Blueprint $table) {

                $table->id();
                $table->foreignId('user_id')->constrained();
                $table->foreignId('strategy_id')->constrained();
                $table->boolean('successful');
                $table->decimal('amount');
                $table->decimal('returns');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            }
        );
    }

    public function down() {

        Schema::dropIfExists('investments');
    }
}
