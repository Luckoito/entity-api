<?php

use App\Models\Instance;
use App\Models\Property;
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
        Schema::create('instance_data', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Property::class)->constrained('properties')->onDelete('cascade');
            $table->foreignIdFor(Instance::class)->constrained('instances')->onDelete('cascade');
            $table->string('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instance_data');
    }
};
