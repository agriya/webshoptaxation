<?php namespace Agriya\Webshoptaxation;

class ProductTaxations extends \Eloquent
{
	protected $table = "product_taxations";
	public $timestamps = false;
	protected $primarykey = 'id';
	protected $table_fields = array("id", "taxation_id", "product_id", "user_id", "tax_fee", "fee_type");
	protected $fillable = array("id", "taxation_id", "product_id", "user_id", "tax_fee", "fee_type");

	public function taxations()
    {
        return $this->belongsTo('Agriya\Webshoptaxation\Taxations','taxation_id','id');
    }

}