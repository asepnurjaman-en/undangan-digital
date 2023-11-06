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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->string('title', 110);
            $table->string('slug', 110);
            $table->string('file', 110)->nullable();
            $table->enum('file_type', ['image', 'video', 'audio', 'pdf'])->default('image');
            $table->text('file_preview')->nullable();
            $table->longtext('preset');
            $table->enum('publish', ['publish', 'draft', 'archive'])->default('draft');
            $table->bigInteger('template_id')->index()->nullable()->unsigned();
			$table->foreign('template_id')->references('id')->on('templates')->onUpdate('cascade')->onDelete('cascade');
            $table->string('ip_addr', 30)->nullable();
            $table->bigInteger('user_id')->index()->nullable()->unsigned();
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
