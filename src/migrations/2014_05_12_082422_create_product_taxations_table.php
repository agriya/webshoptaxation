<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTaxationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::dropIfExists('product_taxations');
		Schema::create('product_taxations', function($table)
		{
			//
			$table->increments('id');
			$table->integer('taxation_id');
			$table->integer('product_id');
			$table->integer('user_id');
			$table->float('tax_fee');
			$table->enum('fee_type', array('percentage','flat'));
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
		//
		Schema::dropIfExists('product_taxations');
	}

}
