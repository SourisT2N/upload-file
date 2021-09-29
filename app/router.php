<?php 
	use App\Core\Functions;
	use Bramus\Router\Router;
	$router = new Router();
	$router->setNamespace('App\Controllers');
	$router->set404("Error@error404");

	$router->get('/','Home@index');
	$router->get('/download/([a-zA-Z0-9]+)','Home@download');
	$router->before('GET|POST|PUT|DELETE','/((?!(api)|(public)|(upload)|(ajaxAdmin)).)*','Api@checkBeforeLoad');
	$router->before('GET','/user/(register|login|forgot)',function()
	{
		if(isset($_SESSION['user']))
			header('location: /');
	});
	
	$router->before('GET|POST|PUT|DELETE','/api/((change/.+)|(payment/.+))',function(){
		if(!isset($_SESSION['user']))
		{
			echo json_encode(['status' => 400,'You are not allowed']);
			exit;
		}
	});

	$router->before('GET|POST|PUT|DELETE','/api/login',function(){
		if(isset($_SESSION['user']))
		{
			echo json_encode(['status' => 400,'You are logged']);
			exit;
		}
	});

	$router->before('GET','/@Administrator/login',function()
	{
		if(isset($_SESSION['user']) && $_SESSION['user']['permission'] != 0)
			header('location: /@Administrator');
	});

	$router->before('GET','/(user|(user/(manager|logout|donate)))',function()
	{
		if(!isset($_SESSION['user']))
			header('location: /user/login');
	});

	$router->before('GET','/(@Administrator|@Administrator/((?!login).)*)',function()
	{
		if(!isset($_SESSION['user']) || $_SESSION['user']['permission'] == 0 || $_SESSION['user']['blocked'] == 0 || $_SESSION['user']['status'] == 0)
			header('location: /@Administrator/login');
	});

	//user	
	$router->mount('/user',function() use ($router)
	{
		$router->get('/','User@manager');
		$router->get('/register','User@register');
		$router->get('/login','User@login');
		$router->get('/manager','User@manager');
		$router->get('/auth/([a-zA-Z0-9]*)?','User@auth');
		$router->get('/auth','User@auth');
		$router->get('/logout','User@logout');
		$router->get('/facebook/auth','User@authFacebook');
		$router->get('/google/auth','User@authGoogle');
		$router->get('/forgot','User@forgotPassword');
		$router->get('/donate','User@donate');
	});

	//admin
	$router->mount('/@Administrator',function() use($router) 
	{
		$router->get('/',"Admin@index");
		$router->get('/files',"Admin@files");
		$router->get('/login',"Admin@login");
		$router->get('/donates',"Admin@donates");
		$router->get('/users',"Admin@users");
		$router->get('/add/users',"Admin@addProfile");
		$router->get('/users/(\d+)',"Admin@showProfile");
		$router->get('/check-code',"Admin@checkCode");
		$router->get('/mail',"Admin@mail");
	});

	// api
	$router->mount('/api',function () use ($router)
	{
		$router->post('/uploadFile','Api@ajaxUpload');
		$router->get('/getFile/([a-zA-Z0-9]+)','Api@getFile');
		$router->post('/register','Api@register');
		$router->delete('/file','Api@deleteFileAuto');
		$router->delete('/delete/file/(\d+)','Api@deleteFile');
		$router->put('/resendMail','Api@resendMail');
		$router->post('/login','Api@login');
		$router->post('/auth','Api@checkToken');
		$router->get('/getFileUser/(\w+)','Api@getFileUser');
		$router->get('/getFileUser','Api@getFileUser');
		$router->put('/change/password','Api@changePassword');
		$router->post('/getCode','Api@getCodeForgot');
		$router->post('/newPass','Api@newPassword');
		$router->post('/payment/momo','Api@paymentMOMO');
	});

	//check ajax admin
	$router->before('GET|POST|PUT|DELETE','/ajaxAdmin/.*',function()
	{
		if(!isset($_SESSION['user']) || $_SESSION['user']['permission'] == 0  || $_SESSION['user']['blocked'] == 0 || $_SESSION['user']['status'] == 0)
		{
			echo json_encode(['status' => 400,"message" => "You are not allowed"]);
			exit;
		}
	});
	$router->before('GET|POST|PUT|DELETE', '/ajaxAdmin/(add|delete|update)/(donate|donate/.*)',function() {
		if($_SESSION['user']['permission'] < 2)
		{
			echo json_encode(['status' => 400,"message" => "You are not allowed"]);
			exit;
		}
	});
	//ajax admin
	$router->mount('/ajaxAdmin',function () use ($router)
	{
		$router->get('/getChart','AjaxAdmin@getChart');
		$router->get('/files','AjaxAdmin@getFiles');
		$router->delete('/delete/files','AjaxAdmin@deleteFiles');
		$router->get('/donates','AjaxAdmin@getDonates');
		$router->delete('/delete/donate','AjaxAdmin@deleteOrders');
		$router->post('/add/donate','AjaxAdmin@addOrder');
		$router->put('/update/donate/(\w+)','AjaxAdmin@updateOrder');
		$router->get('/donate/(\w+)','AjaxAdmin@getOrderId');
		$router->get('/users','AjaxAdmin@getUsers');
		$router->delete('/delete/users','AjaxAdmin@deleteUsers');
		$router->put('/changes/users','AjaxAdmin@changeStatus');
		$router->post('/add/user','AjaxAdmin@createUser');
		$router->put('/update/user','AjaxAdmin@updateUser');
		$router->get('/code/(\w+)','AjaxAdmin@checkCode');
		$router->post('/send-mail','AjaxAdmin@sendMail');
	});
	
	$router->run();
