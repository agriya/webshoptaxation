<?php namespace Agriya\Webshoptaxation;

use Agriya\Webshoptaxation\TaxationsService;
class Webshoptaxation {

	public static function greeting(){
		return "What up dawg";
	}
	public static function Taxations(){
		return new TaxationsService();
	}
	public static function ProductTaxations(){
		return new ProductTaxationsService();
	}
}