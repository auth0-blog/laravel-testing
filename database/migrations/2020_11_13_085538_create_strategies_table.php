<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStrategiesTable extends Migration {

    public function up() {

        Schema::create(
            'strategies',
            function (Blueprint $table) {

                $table->id();
                $table->string('type');
                $table->integer('tenure');
                $table->decimal('yield');
                $table->decimal('relief');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            }
        );
    }

    public function down() {

        Schema::dropIfExists('strategies');
    }
}
