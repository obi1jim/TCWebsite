<?php 

namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * Main Model trait
 */
Trait Model
{
	use Database;

	public $limit 		= 30;
	public $offset 		= 0;
	public $order_type 	= "desc";
	public $order_column = "id";
	public $errors 		= [];

	/*public function findAll()
	{
	 
		$query = "select * from $this->table order by $this->order_column $this->order_type limit $this->limit offset $this->offset";

		return $this->query($query);
	}*/
	public function findAll()
	{

		try {
			$query = "select * from $this->table order by $this->order_column $this->order_type limit $this->limit offset $this->offset";
			return $this->query($query);
		} catch (\Throwable $e) {
			// Log the detailed error for developers
			error_log("Database error: " . $e->getMessage());
			// Show a generic error message to the user
			echo "An unexpected error occurred. Please contact support.";
			return false;
    	}
	}

	public function where($data, $data_not = [])
	{
		$keys = array_keys($data);
		$keys_not = array_keys($data_not);
		$query = "select * from $this->table where ";

		foreach ($keys as $key) {
			$query .= $key . " = :". $key . " && ";
		}

		foreach ($keys_not as $key) {
			$query .= $key . " != :". $key . " && ";
		}
		
		$query = trim($query," && ");

		$query .= " order by $this->order_column $this->order_type limit $this->limit offset $this->offset";
		$data = array_merge($data, $data_not);

		return $this->query($query, $data);
	}

	/*public function first($data, $data_not = [])
	{
		$keys = array_keys($data);
		$keys_not = array_keys($data_not);
		$query = "select * from $this->table where ";

		foreach ($keys as $key) {
			$query .= $key . " = :". $key . " && ";
		}

		foreach ($keys_not as $key) {
			$query .= $key . " != :". $key . " && ";
		}
		
		$query = trim($query," && ");

		$query .= " limit $this->limit offset $this->offset";
		$data = array_merge($data, $data_not);
		
		$result = $this->query($query, $data);
		if($result)
			return $result[0];

		return false;
	}*/
	public function first($data, $data_not = [])
	{
		$keys = array_keys($data);
		$keys_not = array_keys($data_not);
		$query = "select * from $this->table where ";

		foreach ($keys as $key) {
			$query .= $key . " = :". $key . " && ";
		}

		foreach ($keys_not as $key) {
			$query .= $key . " != :". $key . " && ";
		}
		
		$query = trim($query," && ");

		$query .= " limit $this->limit offset $this->offset";
		$data = array_merge($data, $data_not);

		try {
			$result = $this->query($query, $data);
			if($result)
				return $result[0];
			return false;
		} catch (\Throwable $e) {
			error_log("Database error: " . $e->getMessage());
			echo "An unexpected error occurred. Please contact support.";
			return false;
		}
	}
	//this is used in the signup function in the User model.
	public function insert($data)
	{
		
		/** remove unwanted data **/
		if(!empty($this->allowedColumns))
		{
			foreach ($data as $key => $value) {
				
				if(!in_array($key, $this->allowedColumns))
				{
					unset($data[$key]);
				}
			}
		}

		$keys = array_keys($data);
		//show($data);
		//show($keys);
		$query = "insert into $this->table (".implode(",", $keys).") values (:".implode(",:", $keys).")";
		$this->query($query, $data);

		return false;
	}

	public function update($id, $data, $id_column = 'id')
	{

		/** remove unwanted data **/
		if(!empty($this->allowedColumns))
		{
			foreach ($data as $key => $value) {
				
				if(!in_array($key, $this->allowedColumns))
				{
					unset($data[$key]);
				}
			}
		}

		$keys = array_keys($data);
		$query = "update $this->table set ";

		foreach ($keys as $key) {
			$query .= $key . " = :". $key . ", ";
		}

		$query = trim($query,", ");

		$query .= " where $id_column = :$id_column ";

		$data[$id_column] = $id;

		$this->query($query, $data);
		return false;

	}

	public function delete($id, $id_column = 'id')
	{

		$data[$id_column] = $id;
		$query = "delete from $this->table where $id_column = :$id_column ";
		$this->query($query, $data);

		return false;

	}

	public function getError($key)
	{
		if(!empty($this->errors[$key]))
			return $this->errors[$key];

		return "";
	}

	protected function getPrimaryKey(){

		return $this->primaryKey ?? 'id';
	}

	//This validates the data submitted by the user
	//and makes sure there are no duplicates in the database.
	//this is all based on the variables for onInsertValidationRules.
	public function validateSignUp($data){
		$this->errors = [];

		$validationRules = $this->onInsertValidationRules;
		

		if(!empty($validationRules))
		{
			foreach ($validationRules as $column => $rules) {
				
				if(!isset($data[$column]))
					continue;

				foreach ($rules as $rule) {
				
					switch ($rule) {
						case 'required':
							//this displays the error message if the field is empty
							//on the form, the field is required
							//if the field is not empty, it will be ignored
							if(empty($data[$column]))
								
								//displayed as First Name instead of first_name
								$this->errors[$column] = underscore_to_space_str($column) . " is required";
								// this is for testing: echo ($column . " testing || ");
							break;
						
						case 'email':

							if(!filter_var(trim($data[$column]),FILTER_VALIDATE_EMAIL))
								$this->errors[$column] = "Invalid email address";
							break;
						case 'alpha':

							if(!preg_match("/^[a-zA-Z]+$/", trim($data[$column])))
								$this->errors[$column] = ucfirst($column) . " should only have aphabetical letters without spaces";
							break;
						case 'alpha_space':

							if(!preg_match("/^[a-zA-Z ]+$/", trim($data[$column])))
								$this->errors[$column] = underscore_to_space_str($column) . " should only have aphabetical letters & spaces";
							break;
						case 'alpha_numeric':
							
							if(!preg_match("/^[a-zA-Z0-9]+$/", trim($data[$column])))
								$this->errors[$column] = ucfirst($column) . " should only have aphabetical letters & spaces";
							break;
						case 'numeric':
						
							if(!preg_match("/^[0-9]+$/", trim($data[$column])))
								$this->errors[$column] = underscore_to_space_str($column) . " should only have whole numbers";
							break;
						case 'alpha_numeric_symbol':

							if(!preg_match("/^[a-zA-Z0-9\-\_\$\%\*\[\]\(\)\& ]+$/", trim($data[$column])))
								$this->errors[$column] = ucfirst($column) . " should only have aphabetical letters & spaces";
							break;
						case 'alpha_symbol':

							if(!preg_match("/^[a-zA-Z\-\_\$\%\*\[\]\(\)\& ]+$/", trim($data[$column])))
								$this->errors[$column] = ucfirst($column) . " should only have aphabetical letters & spaces";
							break;
						
						case 'not_less_than_8_chars':

							if(strlen(trim($data[$column])) < 8)
								$this->errors[$column] = ucfirst($column) . " should not be less than 8 characters";
							break;

						case 'confirm_password':

							if(strlen(trim($data[$column])) < 8)
								$this->errors[$column] = ucfirst($column) . " should not be less than 8 characters";
							if($data[$column] != $data['password'])
								$this->errors[$column] = "Password and Confirm Password should be same";
							break;
						
						case 'unique':

							$key = $this->getPrimaryKey();
							if(!empty($data[$key]))
							{
								//edit mode
								if($this->first([$column=>$data[$column]],[$key=>$data[$key]])){
									$this->errors[$column] = underscore_to_space_str($column) . " should be unique";
								}
							}else{
								//insert mode
								if($this->first([$column=>$data[$column]])){
									$this->errors[$column] = underscore_to_space_str($column) . " should be unique";
								}
							}
							break;
						
						default:
							$this->errors['rules'] = "The rule ". $rule . " was not found!";
							break;
					}
				}
			}
		}

		if(empty($this->errors))
		{
			return true;
		}

		return false;
	}

	//this validates the data submitted by the user
	//but doesn't check for duplicates in the database.
	//this is all based on the variables for onUpdateValidationRules
	public function validateSignIn($data){
		$this->errors = [];

		$validationRules = $this->onUpdateValidationRules;
		

		if(!empty($validationRules))
		{
			foreach ($validationRules as $column => $rules) {
				
				if(!isset($data[$column]))
					continue;

				foreach ($rules as $rule) {
				
					switch ($rule) {
						case 'required':
							//this displays the error message if the field is empty
							//on the form, the field is required
							//if the field is not empty, it will be ignored
							if(empty($data[$column]))
								
								//displayed as First Name instead of first_name
								$this->errors[$column] = underscore_to_space_str($column) . " is required";
								// this is for testing: echo ($column . " testing || ");
							break;
						
						case 'email':

							if(!filter_var(trim($data[$column]),FILTER_VALIDATE_EMAIL))
								$this->errors[$column] = "Invalid email address";
							break;
						case 'alpha':

							if(!preg_match("/^[a-zA-Z]+$/", trim($data[$column])))
								$this->errors[$column] = ucfirst($column) . " should only have aphabetical letters without spaces";
							break;
						case 'alpha_space':

							if(!preg_match("/^[a-zA-Z ]+$/", trim($data[$column])))
								$this->errors[$column] = underscore_to_space_str($column) . " should only have aphabetical letters & spaces";
							break;
						case 'alpha_numeric':
							
							if(!preg_match("/^[a-zA-Z0-9]+$/", trim($data[$column])))
								$this->errors[$column] = ucfirst($column) . " should only have aphabetical letters & spaces";
							break;
						case 'numeric':
						
							if(!preg_match("/^[0-9]+$/", trim($data[$column])))
								$this->errors[$column] = underscore_to_space_str($column) . " should only have whole numbers";
							break;
						case 'alpha_numeric_symbol':

							if(!preg_match("/^[a-zA-Z0-9\-\_\$\%\*\[\]\(\)\& ]+$/", trim($data[$column])))
								$this->errors[$column] = ucfirst($column) . " should only have aphabetical letters & spaces";
							break;
						case 'alpha_symbol':

							if(!preg_match("/^[a-zA-Z\-\_\$\%\*\[\]\(\)\& ]+$/", trim($data[$column])))
								$this->errors[$column] = ucfirst($column) . " should only have aphabetical letters & spaces";
							break;
						
						case 'not_less_than_8_chars':

							if(strlen(trim($data[$column])) < 8)
								$this->errors[$column] = ucfirst($column) . " should not be less than 8 characters";
							break;

						case 'confirm_password':

							if(strlen(trim($data[$column])) < 8)
								$this->errors[$column] = ucfirst($column) . " should not be less than 8 characters";
							if($data[$column] != $data['password'])
								$this->errors[$column] = "Password and Confirm Password should be same";
							break;
						
						case 'unique':

							$key = $this->getPrimaryKey();
							if(!empty($data[$key]))
							{
								//edit mode
								if($this->first([$column=>$data[$column]],[$key=>$data[$key]])){
									$this->errors[$column] = underscore_to_space_str($column) . " should be unique";
								}
							}else{
								//insert mode
								if($this->first([$column=>$data[$column]])){
									$this->errors[$column] = underscore_to_space_str($column) . " should be unique";
								}
							}
							break;
						
						default:
							$this->errors['rules'] = "The rule ". $rule . " was not found!";
							break;
					}
				}
			}
		}

		if(empty($this->errors))
		{
			return true;
		}

		return false;
	}

	public function validate($data)
	{

		$this->errors = [];

		if(!empty($this->primaryKey) && !empty($data[$this->primaryKey]))
		{
			$validationRules = $this->onUpdateValidationRules;
		}else if(!empty($this->emailUniqueColumn) && !empty($data[$this->emailUniqueColumn])){
			$validationRules = $this->onUpdateValidationRules;

		}else{

			$validationRules = $this->onInsertValidationRules;
		}

		if(!empty($validationRules))
		{
			foreach ($validationRules as $column => $rules) {
				
				if(!isset($data[$column]))
					continue;

				foreach ($rules as $rule) {
				
					switch ($rule) {
						case 'required':
							//this displays the error message if the field is empty
							//on the form, the field is required
							//if the field is not empty, it will be ignored
							if(empty($data[$column]))
								
								//displayed as First Name instead of first_name
								$this->errors[$column] = underscore_to_space_str($column) . " is required";
								// this is for testing: echo ($column . " testing || ");
							break;
						
						case 'email':

							if(!filter_var(trim($data[$column]),FILTER_VALIDATE_EMAIL))
								$this->errors[$column] = "Invalid email address";
							break;
						case 'alpha':

							if(!preg_match("/^[a-zA-Z]+$/", trim($data[$column])))
								$this->errors[$column] = ucfirst($column) . " should only have aphabetical letters without spaces";
							break;
						case 'alpha_space':

							if(!preg_match("/^[a-zA-Z ]+$/", trim($data[$column])))
								$this->errors[$column] = underscore_to_space_str($column) . " should only have aphabetical letters & spaces";
							break;
						case 'alpha_numeric':
							
							if(!preg_match("/^[a-zA-Z0-9]+$/", trim($data[$column])))
								$this->errors[$column] = ucfirst($column) . " should only have aphabetical letters & spaces";
							break;
						case 'numeric':
						
							if(!preg_match("/^[0-9]+$/", trim($data[$column])))
								$this->errors[$column] = underscore_to_space_str($column) . " should only have whole numbers";
							break;
						case 'alpha_numeric_symbol':

							if(!preg_match("/^[a-zA-Z0-9\-\_\$\%\*\[\]\(\)\& ]+$/", trim($data[$column])))
								$this->errors[$column] = ucfirst($column) . " should only have aphabetical letters & spaces";
							break;
						case 'alpha_symbol':

							if(!preg_match("/^[a-zA-Z\-\_\$\%\*\[\]\(\)\& ]+$/", trim($data[$column])))
								$this->errors[$column] = ucfirst($column) . " should only have aphabetical letters & spaces";
							break;
						
						case 'not_less_than_8_chars':

							if(strlen(trim($data[$column])) < 8)
								$this->errors[$column] = ucfirst($column) . " should not be less than 8 characters";
							break;

						case 'confirm_password':

							if(strlen(trim($data[$column])) < 8)
								$this->errors[$column] = ucfirst($column) . " should not be less than 8 characters";
							if($data[$column] != $data['password'])
								$this->errors[$column] = "Password and Confirm Password should be same";
							break;
						
						case 'unique':

							$key = $this->getPrimaryKey();
							if(!empty($data[$key]))
							{
								//edit mode
								if($this->first([$column=>$data[$column]],[$key=>$data[$key]])){
									$this->errors[$column] = underscore_to_space_str($column) . " should be unique";
								}
							}else{
								//insert mode
								if($this->first([$column=>$data[$column]])){
									$this->errors[$column] = underscore_to_space_str($column) . " should be unique";
								}
							}
							break;
						
						default:
							$this->errors['rules'] = "The rule ". $rule . " was not found!";
							break;
					}
				}
			}
		}

		if(empty($this->errors))
		{
			return true;
		}

		return false;
	}

	
}