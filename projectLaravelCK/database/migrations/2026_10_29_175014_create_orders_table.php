<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('product_id')
                ->constrained('products')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 12, 2);

            $table->string('recipient_name', 100);
            $table->string('recipient_phone', 20);
            $table->string('shipping_address', 255);
            $table->text('note')->nullable();

            $table->enum('status', [
                'pending',
                'confirmed',
                'shipping',
                'completed',
                'cancelled'
            ])->default('pending');

            $table->enum('payment_method', ['cod'])->default('cod');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
