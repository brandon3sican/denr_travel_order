<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('user_email'); // FK to users.email
            $table->unsignedBigInteger('travel_order_id');
            $table->unsignedBigInteger('status_id'); // FK to travel_status
            $table->enum('type', ['Approved','Disapproved','Cancelled']);
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->foreign('user_email')->references('email')->on('users')->onDelete('cascade');
            $table->foreign('travel_order_id')->references('id')->on('travel_orders')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('travel_order_status')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('notifications');
    }
};
