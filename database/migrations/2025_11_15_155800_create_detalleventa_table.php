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
        Schema::create('detalle_venta', function (Blueprint $table) {
            $table->id();

            $table->integer('id_venta');
            $table->foreign('id_venta')->references('id_sale')->on('sale')->onDelete('cascade');

            $table->integer('id_producto');
            $table->foreign('id_producto')->references('id')->on('product')->onDelete('cascade');

            $table->integer('cantidad');
            $table->decimal('subtotal', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_venta');
    }
};
