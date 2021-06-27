<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinkJourneyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_journey', function (Blueprint $table) {
            $table->id();
            $table->string('link_url');
            $table->enum('link_type', ['product', 'category', 'static-page', 'checkout', 'homepage']);
            $table->integer('customer_id', false,true);
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
        Schema::dropIfExists('link_journey');
    }
}
