<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido', function (Blueprint $table) {
            $table->increments('id_pedido');
            $table->integer('id_producto');
            $table->string('proveedor', 100)->nullable();
            $table->unsignedInteger('cantidad');
            $table->string('estado', 30)->default('pendiente');
            $table->date('fecha_pedido');
            $table->date('fecha_recepcion')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('id_producto')
                ->references('id')
                ->on('product')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido');
    }
};