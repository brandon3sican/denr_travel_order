<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('travel_order_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('travel_order_number')->unique();
            $table->foreignId('travel_order_id')->constrained('travel_orders')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('travel_order_numbers');
    }
};
