<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrokerDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broker_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('bidding_property_address')->nullable();
            $table->string('name')->nullable();
            $table->string('cell_num')->nullable();
            $table->string('brokerage_name')->nullable();
            $table->string('brokerage_address')->nullable();
            $table->string('buyer_1_name')->nullable();
            $table->string('buyer_2_name')->nullable();
            $table->string('offer_to_purchase')->nullable();
            $table->string('proof_of_fund')->nullable();
            $table->string('mls_copy')->nullable();
            $table->string('seller_disclosure')->nullable();
            $table->string('other_document_1')->nullable();
            $table->string('other_document_2')->nullable();
            $table->string('other_document_3')->nullable();
            $table->string('other_document_4')->nullable();
            $table->string('property')->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('broker_details');
    }
}
