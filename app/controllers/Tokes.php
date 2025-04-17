<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * Tokes class
 */
class Tokes
{
	use MainController;

	public function index()
	{

		$this->view('tokes');
	}

}
