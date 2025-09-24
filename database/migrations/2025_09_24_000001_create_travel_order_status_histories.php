<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('travel_order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('travel_order_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action'); // approve, reject, update_status
            $table->string('from_status')->nullable();
            $table->string('to_status')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device')->nullable();
            $table->string('browser')->nullable();
            $table->json('location')->nullable(); // {lat,lng,accuracy}
            $table->json('client_meta')->nullable(); // any extra client info
            $table->timestamps();

            $table->index('travel_order_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('travel_order_status_histories');
    }
};
