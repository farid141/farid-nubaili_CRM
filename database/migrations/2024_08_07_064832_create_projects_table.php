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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->text('desc');
            $table->date('start_date');
            $table->unsignedBigInteger('total')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected']);
            $table->foreignId('lead_id')->references('id')->on('leads')->cascadeOnDelete();
            $table->foreignId('manager_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('sales_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
