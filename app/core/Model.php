<?php 
namespace App\Core;
use App\Core\Functions;
use Exception;

class Model
{
	protected $conn;
	function __construct()
	{
		$this->conn = new \mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
		if($this->conn->connect_error)
			die("Error: " . $this->conn->connect_error);
	}

}