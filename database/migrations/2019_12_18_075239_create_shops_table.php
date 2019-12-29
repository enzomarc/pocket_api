<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('business_name');
            $table->longText('business_description')->nullable();
            $table->string('address')->nullable();
            $table->string('business_email')->unique()->nullable();
            $table->string('business_phone', 50)->unique()->nullable();
            $table->string('support_email')->nullable();
            $table->string('business_website')->nullable();
            $table->string('currency', 6)->default('XAF');
            $table->string('logo')->nullable();
            $table->boolean('active')->default(false);
            $table->json('socials')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
    }
}
