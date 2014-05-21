<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::dropIfExists('taxations');
		Schema::create('taxations', function($table)
		{
			//
			$table->increments('id');
			$table->integer('user_id');
			$table->string('tax_name');
			$table->string('tax_slug');
			$table->mediumText('tax_description');
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
		Schema::dropIfExists('taxations');
	}

}
