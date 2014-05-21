<?php namespace Agriya\Webshoptaxation;

class Taxations extends \Eloquent
{
    protected $table = "taxations";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "user_id", "tax_name", "tax_slug", "tax_description", "tax_fee", "fee_type");
    protected $fillable = array("id", "user_id", "tax_name", "tax_slug", "tax_description", "tax_fee", "fee_type");

    public function producttaxations()
    {
        return $this->hasMany('Agriya\Webshoptaxation\ProductTaxations','taxation_id','id');
    }

}