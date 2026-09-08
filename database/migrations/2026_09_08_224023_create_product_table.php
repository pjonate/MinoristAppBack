<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo', 100)->nullable();
            $table->string('categoria', 100);
            $table->text('descripcion');
            $table->string('proveedor', 100);
            $table->decimal('precio', 10, 2);
            $table->integer('stock');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->enum('unidad_venta', ['unidad', 'gramo'])->default('unidad');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};