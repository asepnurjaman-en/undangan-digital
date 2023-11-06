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
        Schema::create('account_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('amount')->default(0);
            $table->date('date');
            $table->longText('content');
            $table->string('status', 50)->default('PENDING');
            $table->string('payment_link', 250);
            $table->string('payment_code', 250);
            $table->bigInteger('package_id')->index()->nullable()->unsigned();
			$table->foreign('package_id')->references('id')->on('packages')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('account_invoices');
    }
};
