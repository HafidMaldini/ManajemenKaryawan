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
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manager_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('karyawan_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->date('deadline');
            $table->enum('priority', ['Easy', 'Medium', 'Hard']);
            $table->date('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['Assigned', 'On Progress', 'Submited', 'Revised', 'Approved', 'On Hold']);
            $table->timestamps();
        });   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
