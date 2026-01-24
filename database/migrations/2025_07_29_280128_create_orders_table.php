<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('delivery_type')->default('pickup');
            $table->date('delivery_date')->nullable();
            $table->time('delivery_time')->nullable();
            $table->text('address')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->text('instructions')->nullable();
            $table->unsignedBigInteger('shipment_route_id')->nullable();
            $table->string('shipment_fee')->default(0.00);
            $table->string('vat_amount')->default(0.00);
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->string('refund_amount')->default(0.00);
            $table->timestamps();
            $table->foreign('shipment_route_id')->references('id')->on('shipment_routes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
