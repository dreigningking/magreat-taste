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
        Schema::create('shipment_routes', function (Blueprint $table) {
            $table->id();
            $table->string('shipper_name'); // Name of the shipper
            $table->string('route_name');
            $table->string('slug')->nullable();
            $table->unsignedBigInteger('location_id'); //ikorodu
            $table->unsignedBigInteger('destination_city_id'); //ikeja
            $table->string('base_price')->nullable();  // Base price for the route e.g 1000
            $table->text('notes')->nullable();
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_routes');
    }
};
