<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('subscriptions', function (Blueprint $table) {
        $table->id();

        $table->foreignId('customer_id')
            ->constrained()
            ->onDelete('cascade');

        $table->foreignId('service_id')
            ->constrained()
            ->onDelete('cascade');

        $table->date('start_date');
        $table->date('end_date');

        $table->enum('status', [
            'active',
            'inactive',
            'trial',
            'isolir',
            'dismantle'
        ]);

        $table->timestamps();
    });
}
};
