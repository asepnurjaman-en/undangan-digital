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
        Schema::create('invitation_guest_arriveds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('invitation_guest_id')->index()->nullable()->unsigned();
			$table->foreign('invitation_guest_id')->references('id')->on('invitation_guests')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('invitation_guest_souvenir_id')->index()->nullable()->unsigned();
			$table->foreign('invitation_guest_souvenir_id')->references('id')->on('invitation_guest_souvenirs')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('stock')->default(0);
            $table->bigInteger('invitation_id')->index()->nullable()->unsigned();
			$table->foreign('invitation_id')->references('id')->on('invitations')->onUpdate('cascade')->onDelete('cascade');
            $table->string('ip_addr', 30)->nullable();
            $table->bigInteger('user_id')->index()->nullable()->unsigned();
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitation_guest_arriveds');
    }
};
