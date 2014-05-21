<h1>WebshopTaxation</h1>
This package is to manage the taxation fee for the countries and product in the site. You can manage taxatopm fees easily by calling some easy function

<h1>Instatllation</h1>

1. Install the package by add the following line in composer.json of your root directory

	"require": {
		...
		...
		"agriya/webshoptaxation": "dev"

	},

	And then run "composer update"

2, After the package loaded add this line to app/conifg/app.php in the 'providers' array as like follows

	'providers' => array(
		...
		...
		'Agriya\Webshoptaxation\WebshoptaxationServiceProvider',
	}

3. After that run the following migrations commands from your root directory to create the tables in ur database.
This will create you two tables

	For published package
		php artisan migrate --package=Agriya/Webshoptaxation

	For workbench package
		php artisan migrate --bench=Agriya/Webshoptaxation

	Note: run these commands needs to be run from your root directory (where the composer.json has placed)

Thats it of installation. :)




<h1>Usage</h1>
<hr>
<h3>Shipments list</h3>
Webshoptaxation::Taxations()->getTaxations([array $array, string $return_type]);

Parameters
----------
$array(required)	Array can have either 'id' or 'user_id' as element. 
					If 'id' is passed, then the specific taxatiion detail will be returned. 
					If 'user_id' is passed, then all taxatiion details of the specified user will be returned. 

$return(optional)	This can be either 'list' or 'all'. (default is 'list')

					'list'	This will return the result as "id" and "tax name" combination
					'firs'	This will return first result with all fields from taxations table
					'all' 	This will return all the fields from taxations table for the given condition

Example
-------
	Webshoptaxation::Taxations()->getTaxations(array('user_id'=>1), 'all');

		This will return all taxations details of the user id 1 from taxations table 

	Webshoptaxation::Taxations()->getTaxations(array('user_id'=>1), 'list');

		This will return the list ('id' => 'tax_name') combination for all taxations details for the user id 1 from taxations table 

	Webshoptaxation::Taxations()->getTaxations(array('id'=>10), 'first');	

		This will return single taxations details for the id 10 from taxations table 


<h3>Add Taxation Details</h3>

		$inputs = array(
			'user_id' 	=> 1,
			'tax_name' 	=> 'VAT',
			'tax_description' 	=> 'Value added tax',
			'tax_fee' 	=> '14.5',
			'fee_type'	=> 'percentage',
		);
		$taxatonid = Webshoptaxation::Taxations()->addTaxation($inputs);


<h3>Update Taxation Details</h3>

		$inputs = array(
			'tax_name' 	=> 'VAT',
			'tax_description' 	=> 'Value Added Tax',
			'tax_fee' 	=> '10',
			'fee_type'	=> 'flat',
		);

		$taxatonid = Webshoptaxation::Taxations()->updateTaxation(1, $inputs);


<h3>Delete Taxation Details</h3>

		$taxatonid = Webshoptaxation::Taxations()->deleteTaxation(1);






<h1>Product Taxation Fees</h1>


<h3>Get Product Taxation Fees List </h3>
		$inputs = array(
			'product_id' 	=> $product_id,
		);
		$producttaxatonslist = Webshoptaxation::ProductTaxations()->getProductTaxations($inputs);


<h3>Add Product Taxation Fees List </h3>
		$inputs = array(
			'taxation_id' 	=> 2,
			'product_id' 	=> 1,
			'tax_fee' 		=> '14.5',
			'fee_type'		=> 'percentage',
		);
		$producttaxatonid = Webshoptaxation::ProductTaxations()->addProductTaxation($inputs);



<h3>Update Product Taxation Fees List </h3>

		Update based on id
		-------------------
				$inputs = array(
					'tax_fee' 	=> '5',
					'fee_type'	=> 'flat',
				);

				$taxatonid = Webshoptaxation::ProductTaxations()->updateProductTaxation(1, $inputs);

		Update based on conditions
		--------------------------
				$inputs = array(
					'tax_fee' 	=> '5',
					'fee_type'	=> 'flat',
				);

				$conditions = array(
					'product_id' => 1,
					'taxation_id' => 2
				);

				$taxatonid = Webshoptaxation::ProductTaxations()->updateProductTaxation(null, $inputs, $conditions);



<h3>Delete Product Taxation Fees List </h3>

		Delete based on id
		-------------------
				$inputs = array(
					'tax_fee' 	=> '5',
					'fee_type'	=> 'flat',
				);
			
				$taxatonid = Webshoptaxation::ProductTaxations()->deleteProductTaxation(1);

		Delete based on conditions
		--------------------------
				$inputs = array(
					'tax_fee' 	=> '5',
					'fee_type'	=> 'flat',
				);

				$conditions = array(
					'product_id' => 1,
					'taxation_id' => 2
				);
				$taxatonid = Webshoptaxation::ProductTaxations()->deleteProductTaxation(null, $conditions);







		