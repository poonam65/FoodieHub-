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
            // $table->foreignId('user_id')->constrained()->onDelete('set null');
           $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('delivery_address');
            $table->decimal('subtotal',8,2);
            $table->decimal('delivery_fee',8,2)->default(50.00);
            $table->decimal('total',8,2);
            $table->enum('status',['pending' ,'confirmed','preparing','out_for_delivery','delivered','cancelled'])->default('pending');
            $table->text('note')->nullable();
          $table->timestamps();
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
