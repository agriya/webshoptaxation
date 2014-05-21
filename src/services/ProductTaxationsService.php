<?php namespace Agriya\Webshoptaxation;

Use Exception;
class MissingProductTaxationsParamsExecption extends Exception {}

class ProductTaxationsService
{
	public function addProductTaxation($inputs = array())
	{
		try
		{
			//Throw exceptions if inputs are wrong
			if(is_null($inputs) || !is_array($inputs))
				throw new MissingProductTaxationsParamsExecption('The input array can not be empty. ');

			if(!isset($inputs['taxation_id']) || $inputs['taxation_id'] <=0)
				throw new MissingProductTaxationsParamsExecption('Taxation id can not be empty or zero. ');

			$taxations_det = Webshoptaxation::Taxations()->getTaxations(array('id' => $inputs['taxation_id']), 'first');

			if(!$taxations_det)
				throw new MissingProductTaxationsParamsExecption('Given taxation id not present or may be deleted. ');

			$user_id = $taxations_det->user_id;
			if(!$user_id)
				throw new MissingProductTaxationsParamsExecption('Something went wrong with the details of the given taxation. ');

			$inputs['user_id'] = $user_id;


			$rules = array(
				'taxation_id' 	=> 'required|numeric',
				'user_id' 		=> 'required|numeric',
				'product_id'	=> 'required|numeric',
				'tax_fee' 		=> 'required',
				'fee_type'		=> 'required|in:percentage,flat',
			);
			$valid_keys = array(
				'taxation_id'		=> '',
				'user_id' 			=> '',
				'product_id' 		=> '',
				'tax_fee' 			=> '',
				'fee_type'			=> '',
			);
			$inputs = array_intersect_key($inputs, $valid_keys);
			//$inputs = $inputs+$valid_keys;

			$validator = \Validator::make($inputs,$rules);
			if($validator->passes())
			{	
				$getProductTaxtions = $this->getProductTaxations(array('product_id' => $inputs['product_id'], 'taxation_id' => $inputs['taxation_id']), 'first');
				if(!$getProductTaxtions || count($getProductTaxtions) <=0)
				{
					$producttaxations = ProductTaxations::create($inputs);
					return $producttaxations->id;	
				}
				else
					throw new MissingProductTaxationsParamsExecption("Specified Taxation fee has already been inserted for this product");
				
			}
			else
			{
				throw new MissingProductTaxationsParamsExecption($validator->messages()->first());
			}
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}

	}


	public function getProductTaxations($options = array(), $return_type = 'all')
	{
		try
		{
			//Throw exceptions if inputs are wrong
			if(is_null($options) || !is_array($options) || (is_array($options) && empty($options)))
				throw new MissingProductTaxationsParamsExecption('The options can not be empty.');

			if(is_null($return_type)){$return_type = 'all';}
			if(!in_array($return_type, array('all','first')))
				throw new MissingProductTaxationsParamsExecption('Return type can be either \'all\' or \'first\'. ');


			//Create model object for taxations
			$producttaxations = ProductTaxations::with('taxations')->orderby('id','asc');

			$valid_keys = array(
					'id' => '',
					'product_id' => '',
					'taxation_id' => ''
				);
			$options = array_intersect_key($options, $valid_keys);

			if(empty($options))
				throw new MissingProductTaxationsParamsExecption('Options array should have either sperate or compbination of \'id\', \'product_id\' , \'taxation_id\'. Other elements are not valid');

			//Conditions based on input
			if(isset($options['id']) && $options['id'] > 0)
				$producttaxations->where('id','=',$options['id']);
			//if(isset($options['user_id']) && $options['user_id'] > 0)
				//$producttaxations->where('user_id','=',$options['user_id']);
			if(isset($options['product_id']) && $options['product_id'] > 0)
				$producttaxations->where('product_id','=',$options['product_id']);
			if(isset($options['taxation_id']) && $options['taxation_id'] > 0)
				$producttaxations->where('taxation_id','=',$options['taxation_id']);

			//Check the return type and get hte list
			if($return_type == 'first')
				$producttaxationslist = $producttaxations->first();
			else
				$producttaxationslist = $producttaxations->get();

			//Return the list
			if(count($producttaxationslist) > 0)
				return $producttaxationslist;
			else
				return false;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}


	public function updateProductTaxation($id = null, $inputs = array(), $conditions = array())
	{
		try
		{

			//Throw exceptions if inputs are wrong
			if(is_null($id) && empty($conditions))
				throw new MissingProductTaxationsParamsExecption('Both taxation id and condtions array can not be empty. Either should be passed to update the taxation fee.');

			//if conditions are specified then check are the valid inputs. If not throw the error
			if(!empty($conditions))
			{

				$valid_keys = array(
					'product_id' => '',
					'taxation_id' => ''
				);

				$conditions = array_intersect_key($conditions, $valid_keys);

				if(empty($conditions))
					throw new MissingProductTaxationsParamsExecption('Valid input condition keys to update the taxation fee are  \' product_id\', \'taxation_id\'. Other fields are not valid. ');

				$condition_rules = array(
					'product_id' 		=> 'required|numeric',
					'taxation_id'		=> 'required|numeric',
				);

				$condition_validator = \Validator::make($conditions,$condition_rules);
				if($condition_validator->fails() && is_null($id))
				{
					throw new MissingProductTaxationsParamsExecption('To update the taxation fee either id or conditions parameters are required. ');
				}

			}

			//Get the detail of the given taxation details from give id or from the conditions
			if(!is_null($id))
				$taxations_det = $this->getProductTaxations(array('id' => $id), 'first');
			elseif(!empty($conditions))
				$taxations_det = $this->getProductTaxations($conditions, 'first');
			else
				$taxations_det =false;				

			if(!$taxations_det)
				throw new MissingProductTaxationsParamsExecption('Given product taxation details is not present or may be deleted.');

			$product_taxation_id = $taxations_det->id;
			if(!$product_taxation_id)
				throw new MissingProductTaxationsParamsExecption('Something went wrong with the details of the given product taxation.');
			

			//Check the fields to update
			$rules = array(
				'tax_fee' 		=> 'sometimes|required',
				'fee_type'		=> 'sometimes|required|in:percentage,flat',
			);
			$valid_keys = array(
				'tax_fee' 			=> '',
				'fee_type'			=> '',
			);

			$inputs = array_intersect_key($inputs, $valid_keys);
			if(empty($inputs))
					throw new MissingProductTaxationsParamsExecption('You can either update \' tax_fee\' or \'fee_type\'. Other fields are not valid. ');

			$validator = \Validator::make($inputs,$rules);
			if($validator->passes())
			{
				$affectedRows = ProductTaxations::where('id', '=', $product_taxation_id)->update($inputs);
				return $affectedRows;
			}
			else
			{
				throw new MissingProductTaxationsParamsExecption($validator->messages()->first());
			}
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}

	}

	public function deleteProductTaxation($id = null, $conditions = array())
	{

		try
		{
			
			//Throw exceptions if inputs are wrong
			if(is_null($id) && empty($conditions))
				throw new MissingProductTaxationsParamsExecption('Both taxation id and condtions array can not be empty. Either should be passed to delete the taxation fee');

			//if conditions are specified then check are the valid inputs. If not throw the error
			if(!empty($conditions))
			{

				$valid_keys = array(
					'product_id' => '',
					'taxation_id' => ''
				);

				$conditions = array_intersect_key($conditions, $valid_keys);

				if(empty($conditions))
					throw new MissingProductTaxationsParamsExecption('Valid input condition keys to delete the taxation fee are  \' product_id\', \'taxation_id\'. Other fields are not valid. ');

				$condition_rules = array(
					'product_id' 		=> 'required|numeric',
					'taxation_id'		=> 'required|numeric',
				);

				$condition_validator = \Validator::make($conditions,$condition_rules);
				if($condition_validator->fails() && is_null($id))
				{
					throw new MissingProductTaxationsParamsExecption('To delete the taxation fee either id or valid conditions parameters are required. ');
				}

			}

			//Get the detail of the given taxation details from give id or from the conditions
			if(!is_null($id))
				$taxations_det = $this->getProductTaxations(array('id' => $id), 'first');
			elseif(!empty($conditions))
				$taxations_det = $this->getProductTaxations($conditions, 'first');
			else
				$taxations_det = false;

			if(!$taxations_det)
				throw new MissingProductTaxationsParamsExecption('Given product taxation details is not present or may be deleted.');

			$product_taxation_id = $taxations_det->id;
			if(!$product_taxation_id)
				throw new MissingProductTaxationsParamsExecption('Something went wrong with the details of the given product taxation.');
			
	
			$affectedRows = ProductTaxations::where('id', '=', $product_taxation_id)->delete();
			return $affectedRows;
			
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}

	}

}