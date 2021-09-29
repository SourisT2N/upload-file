<?php 
	namespace App\Controllers;
	use App\Core\Controller;
	class Error extends Controller
	{
		public function error404()
		{
			return $this->view("error/error404");
		}
	}