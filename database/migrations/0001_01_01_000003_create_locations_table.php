<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('country_id'); 
            $table->unsignedBigInteger('state_id');
            $table->unsignedBigInteger('city_id');
            $table->string('address');
            $table->boolean('status')->default(1);
            $table->timestamps();
            
        });

        $locations = [
            ['name' => 'Ebute Ikorodu Branch', 'country_id' => 161, 'state_id' => 306,'city_id'=> 76851,'address'=> '21, Irewunmi Badru Street','status'=> 1],
        ];

            foreach ($locations as $location) {
                DB::table('locations')->insert($location);
            }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
