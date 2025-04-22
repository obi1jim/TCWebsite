<?php

namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * Tokes class
 */
class Tokes
{
	
	use Model;

	protected $table = 'tokes';
	protected $primaryKey = 'id';
	protected $loginUniqueColumn = 'date_dropped';

	protected $allowedColumns = [

		'date_dropped',
		'amount',
	];

	/*****************************
	 * 	rules include:
		required
		alpha
		email
		numeric
		unique
		symbol
		longer_than_8_chars
		alpha_numeric_symbol
		alpha_numeric
		alpha_symbol
	 * 
	 ****************************/
	protected $validationRules = [

		'date_drop' => [
			'unique',
			'required',
		],
		'amount' => [
			'numeric',
			'required',
		],
		
	];

	

}