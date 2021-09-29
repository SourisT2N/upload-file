<?php 
	namespace App\Controllers;
	use App\Core\Controller;
	class Home extends Controller
	{
		public function index()
		{
			return $this->view('home/index');
		}

		public function download($id)
		{
			return $this->view('home/download');
		}
	}