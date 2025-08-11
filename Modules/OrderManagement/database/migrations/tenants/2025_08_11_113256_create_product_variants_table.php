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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->float('base_price');
            $table->float('b2b_price')->comment('price for wholeseller or bulk buyer');
            $table->float('b2c_price')->comment('price for individual or retail customer');
            $table->integer('available_stock');
            $table->string('batch_no')->nullable();
            $table->string('lot_no')->nullable();

            $table->string('keyword')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
