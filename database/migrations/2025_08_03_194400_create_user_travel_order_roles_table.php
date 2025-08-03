<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user_travel_order_roles', function (Blueprint $table) {
            $table->id();
            $table->string('user_email');
            $table->unsignedBigInteger('travel_order_role_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_email')
                  ->references('email')
                  ->on('users')
                  ->onDelete('cascade');
                  
            $table->foreign('travel_order_role_id')
                  ->references('id')
                  ->on('travel_order_roles')
                  ->onDelete('cascade');
                  
            // Ensure a user can only have one role
            $table->unique(['user_email', 'travel_order_role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('user_travel_order_roles');
    }
};
