<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * {CLASSNAME} class
 */
class {CLASSNAME}
{
	use MainController;

	public function index()
	{
		// this is the controller sample
		// you can use this to create your own controller
		//using the thunder framework. all controllers
		// you create using the thunder framework will 
		// have this structure.
		$data['title'] = '{CLASSNAME}';
		$this->view('{classname}', $data);
	}

}
