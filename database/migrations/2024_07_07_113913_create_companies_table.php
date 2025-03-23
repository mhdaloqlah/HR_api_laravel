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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('companyName',255);
            $table->text('image')->nullable();
            $table->string('license_number',255)->nullable();
            $table->date('license_release')->nullable();
            $table->date('license_expiry')->nullable();
            $table->string('phone1',255)->nullable();
            $table->string('phone2',255)->nullable();
            $table->string('fax',255)->nullable();
            $table->string('email',255)->nullable();
            $table->string('website',255)->nullable();
            $table->string('about',2000)->nullable();
            $table->string('location',255)->nullable();
            $table->string('address',255)->nullable();
            $table->string('facebook',255)->nullable();
            $table->string('twitter',255)->nullable();
            $table->string('linkden',255)->nullable();
            $table->string('skype',255)->nullable();
            $table->string('whatsapp',255)->nullable();
            $table->string('instegram',255)->nullable();
            $table->boolean('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
