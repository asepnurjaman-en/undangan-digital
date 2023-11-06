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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['wishes', 'gift', 'present'])->default('wishes');
            $table->longText('content')->nullable();
            $table->bigInteger('invitation_id')->index()->nullable()->unsigned();
			$table->foreign('invitation_id')->references('id')->on('invitations')->onUpdate('cascade')->onDelete('cascade');
            $table->string('ip_addr', 30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
