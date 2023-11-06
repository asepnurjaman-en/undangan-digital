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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->json('content')->nullable();
            $table->string('file', 110)->nullable();
            $table->enum('actived', ['0', '1'])->default('0');
            $table->enum('guestbook', ['0', '1'])->default('0');
            $table->bigInteger('package_id')->index()->nullable()->unsigned();
			$table->foreign('package_id')->references('id')->on('packages')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('invitation_id')->index()->nullable()->unsigned();
			$table->foreign('invitation_id')->references('id')->on('invitations')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('accounts');
    }
};
