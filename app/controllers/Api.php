<?php 
	namespace App\Controllers;
	use App\Core\Controller;
	use App\Core\Functions;
	use App\Core\Validation;
	use Exception;
	use Firebase\JWT\JWT;

class Api extends Controller
	{
		private $dbToken;
		private $dbFile;
		private $dbUser;
		private $dbAuth;
		private $dbAuth_User;
		function __construct()
		{
			$this->dbToken = $this->loadModel('Token');
			$this->dbFile = $this->loadModel('File');
			$this->dbUser = $this->loadModel('User');
			$this->dbAuth = $this->loadModel('Auth');
			$this->dbAuth_User = $this->loadModel('Auth_User');
		}

		public function checkBeforeLoad()
		{
			$this->loadToken();

			if(isset($_SESSION['user']) && !$_SESSION['user']['status'] && !preg_match('`\/auth(\/(([a-zA-Z0-9]*)))?`',$_SERVER['REQUEST_URI']))
				header('location: /user/auth');
			if(isset($_SESSION['user']) && !$_SESSION['user']['blocked'] && !preg_match('`\/auth(\/(([a-zA-Z0-9]*)))?`',$_SERVER['REQUEST_URI']))
				header('location: /user/auth');
		}

		public function checkToken()
		{
			header('Access-Control-Allow-Origin: '.DOMAIN);
			header('Access-Control-Allow-Methods: POST');
			header('Access-Control-Request-Headers: X-Requested-With');
			header('Access-Control-Allow-Headers: X-Requested-With');
			header('Access-Control-Allow-Credentials: true');

			if($_SERVER['REQUEST_METHOD'] !== 'POST')
				return;
			$this->loadToken();
		}

		private function loadToken()
		{
			try
			{
				if(!isset($_COOKIE['_atk'])|| !isset($_COOKIE['_rft']))
					throw new Exception('Not Exits Cookies');
				$_atk = $_COOKIE['_atk'] ?? '';
				$_rft = $_COOKIE['_rft'] ?? '';

			
				$atk = (array)JWT::decode($_atk,PRIVATE_KEY,[ALGO_TOKEN]);
				$user = $this->check($atk,$_atk,$_rft);
				$_SESSION['user'] = $user;
				if(isset($_SESSION['user']) && !$_SESSION['user']['blocked'])
					throw new Exception('Blocked');
				if($atk['exp'] > time())
					return;
				$user['atk'] = $_atk;
				$user['rft'] = $_rft;
				if($tokens = $this->dbToken->insertToken($user,1))
				{
					Functions::setCookieOptions('expires',strtotime('+7 days'));
					Functions::setCookie($tokens);
				}
				else
					throw new Exception('No Insert');
			}
			catch(Exception $e)
			{
				Functions::setCookieOptions('expires',strtotime('-7 days'));
				Functions::deleteCookie();
				unset($_SESSION['user']);
			}
		}

		public function check($atk,$_atk,$_rft)
		{
			if($atk['iss'] !== DOMAIN || $atk['iat'] > $atk['nbf'] || $atk['nbf'] > time())
					throw new Exception('Not JWT');
			if(!$user = $this->dbToken->selectUserToken($_atk,$_rft))
				throw new Exception('Not User');
			return $user;
		}

		public function ajaxUpload()
		{
			header('Access-Control-Allow-Origin: '.DOMAIN);
			header('Access-Control-Allow-Methods: POST');
			header('Content-Type: multipart/form-data');
			header('Access-Control-Request-Headers: Content-type,X-Requested-With');
			header('Access-Control-Allow-Headers: Content-type,X-Requested-With');
			header('Access-Control-Allow-Credentials: true');
			try
			{
				if(isset($_FILES['file']))
				{
					$file = $_FILES['file'];				
					$idUser = $_SESSION['user']['id']??'NULL';
					print_r(json_encode($this->dbFile->insertFile($file,$idUser)));
					\http_response_code(200);
				}
				else
				{
					\http_response_code(400);
					header('http/2 400 Bad Request');
				}
			}
			catch(\Exception $e)
			{
				echo json_encode(['status' => 0,'text' => $e->getMessage()]);
			}
		}

		public function getFile($idFile)
		{
			header("Access-Control-Allow-Origin: ".DOMAIN);
			header("Access-Control-Allow-Methods: GET");
			header("Content-Type: application/json");
			header("Access-Control-Allow-Headers: Content-Type,X-Requested-With");
			header("Access-Control-Request-Headers: Content-Type,X-Requested-With");
			header("Access-Control-Request-Credentials: true");
			try
			{
				\http_response_code(200);
				echo json_encode($this->dbFile->selectFile($idFile));
			}
			catch(\Exception $e)
			{
				echo json_encode(['status' => 0,'text' => $e->getMessage()]);
			}
		}

		public function deleteFileAuto()
		{
			header('Access-Control-Allow-Origin: '.DOMAIN);
			header('Access-Control-Allow-Methods: DELETE');
			header('Access-Control-Request-Headers: X-Requested-With');
			header('Access-Control-Allow-Headers: X-Requested-With');
			header('Access-Control-Allow-Credentials: false');
			if($_SERVER['REQUEST_METHOD'] === 'DELETE')
				Functions::deleteFileAuto('upload/');
			else
			{
				\http_response_code(400);
				header('http/2 400 Bad Request');
			}
		}

		public function register()
		{
			header("Access-Control-Allow-Origin: ".DOMAIN);
			header("Access-Control-Allow-Methods: POST");
			header("Content-Type: application/json");
			header("Access-Control-Allow-Headers: Content-Type,X-Requested-With");
			header("Access-Control-Request-Headers: Content-Type,X-Requested-With");
			if($_SERVER['REQUEST_METHOD'] === 'POST')
			{
				try
				{
					$post = json_decode(\file_get_contents('php://input'),true);
					$rs = json_decode(Functions::checkRecaptcha($post['g-recaptcha-response'??'']));
					if(!$rs->success)
						throw new Exception('Please verify captcha');
					unset($post['g-recaptcha-response']);
					if(!isset($post['fullname']) || !isset($post['email']) || !isset($post['password']) || !isset($post['re-password']))
						throw new Exception('Data not empty');
					Validation::init($post);
					$count = count($post);
					if(count(Validation::getError()) !== 0 || $count !== Validation::getStatus() || $count !== 4)
						throw new Exception('Check input again');
					unset($post['re-password']);
					if($this->dbUser->selectUser($post['email']))
						throw new Exception('Email already exists');
					if($params = $this->dbUser->createUser($post))
					{
						echo json_encode(['status' => 1,'message' => 'Registration successful.Please Confirm Your Email']);
						$auth = $this->dbAuth->insertAuth($params,0);
						Functions::sendMail($auth['email'],$auth['name'],$auth['subject'],$auth['body']);
					}
					else
						throw new Exception('Registration failed');
				}
				catch(Exception $e)
				{
					print_r(json_encode(['status' => 0,'message' => $e->getMessage()]));
				}
			}
		}

		public function login()
		{
			header('Access-Control-Allow-Origin: '.DOMAIN);
			header('Access-Control-Allow-Methods: POST');
			header('Content-Type: application/json');
			header("Access-Control-Allow-Headers: X-Requested-With");
			header("Access-Control-Request-Headers: X-Requested-With");

			if($_SERVER['REQUEST_METHOD'] === 'POST')
			{
				try
				{
					
					$user = \json_decode(\file_get_contents('php://input'),true);
					$rs = json_decode(Functions::checkRecaptcha($user['g-recaptcha-response']??''));
					if(!$rs->success)
						throw new Exception('Please verify captcha');

					unset($user['g-recaptcha-response']);
					if(!isset($user['email']) || !isset($user['password']))
						throw new Exception('Data not empty');
					Validation::init($user);
					$count = count($user);
					if(count(Validation::getError()) !== 0 || $count !== Validation::getStatus() || $count !== 2)
						throw new Exception(Validation::getError());
					$checkLogin = $this->dbUser->checkLogin($user);
					if($checkLogin['status'])
					{
						$user = $checkLogin['user'];
						unset($checkLogin['user']);
						if($tokens = $this->dbToken->insertToken($user,0))
						{
							Functions::setCookieOptions('expires',strtotime('+7 days'));
							Functions::setCookie($tokens);
							echo json_encode(['status' => 1,'message' => 'Login successfully']);
						}
					}
					else
						throw new Exception($checkLogin['message']);
				}
				catch(Exception $e)
				{
					echo json_encode(['status' => 0,'message' => $e->getMessage()]);
				}
			}
		}

		public function resendMail()
		{
			header('Access-Control-Allow-Origin: '.DOMAIN);
			header('Access-Control-Allow-Method: PUT');
			header('Content-Type: application/json');
			header("Access-Control-Request-Headers: Content-Type,X-Requested-With");
			header('Access-Control-Allow-Headers: Content-type,X-Requested-With');
			header('Access-Control-Allow-Credentials: true');
			if($_SERVER['REQUEST_METHOD'] === 'PUT')
			{
				$user = [];
				if(isset($_SESSION['user']))
					$user = $_SESSION['user'];
				else
				{
					$idUrl = \json_decode(\file_get_contents('php://input'),true)['idURL'];
					$user = $this->dbAuth->selectUser($idUrl);
				}
				if($user)
				{
					echo json_encode(['status' => 1,'message' => 'Resend successfully']);
					$auth = $this->dbAuth->insertAuth($user,1);
					Functions::sendMail($auth['email'],$auth['name'],$auth['subject'],$auth['body']);
				}
				else
					echo json_encode(['status' => 0,'message' => 'Resend failed']);
			}
			else
			{
				\http_response_code(400);
				header('http/2 400 Bad Request');
			}
		}

		public function getFileUser($id = '')
		{
			header('Access-Control-Allow-Origin: '.DOMAIN);
			header('Access-Control-Allow-Methods: GET');
			header("Access-Control-Allow-Headers: X-Requested-With");
			header("Access-Control-Request-Headers: X-Requested-With");
			header('Access-Control-Allow-Credentials: true');

			if($_SERVER['REQUEST_METHOD'] !== 'GET')
				return;
			$id = $_SESSION['user']['id']??$id;
			$data = $this->dbFile->selectFileUser(['id' => $id,'check' => false]);
			echo \json_encode(['data' => $data['data']??[]]);
		}

		public function getCodeForgot()
		{
			header('Access-Control-Allow-Origin: '.DOMAIN);
			header('Access-Control-Allow-Methods: POST');
			header("Access-Control-Allow-Headers: X-Requested-With");
			header("Access-Control-Request-Headers: X-Requested-With");
			try
			{
				$rs = json_decode(Functions::checkRecaptcha($_POST['g-recaptcha-response']??''));
				if(!$rs->success)
					throw new Exception('Please verify captcha');
				unset($_POST['g-recaptcha-response']);
				if(!isset($_POST['email']))
					throw new Exception('Not empty');
				Validation::init($_POST);
				$count  = count($_POST);
				if(count(Validation::getError()) !== 0 || $count !== Validation::getStatus() || $count !== 1)
					throw new Exception('Check input again');
				$_SESSION['forgot']['email'] = $_POST['email'];
				$user = $this->dbUser->selectUser($_SESSION['forgot']['email']);
				if(!$user)
					throw new Exception('This email is not registered');
				$code = $this->dbAuth_User->createCode($user['id'],1);
				if(!$code)
					throw new Exception('An error unknow');
				echo json_encode(['status' => 1,'message'=>'Code to: '.$_SESSION['forgot']['email']]);
				$subject = 'Request Forgot Password';
				$body	 = 'Your verification code is '.$code;
				Functions::sendMail($_SESSION['forgot']['email'],$user['fullname'],$subject,$body);
			}
			catch(Exception $e)
			{
				echo \json_encode(['status' => 0,'message' => $e->getMessage()]);
			}
		}

		public function newPassword()
		{
			header('Access-Control-Allow-Origin: '.DOMAIN);
			header('Access-Control-Allow-Methods: POST');
			header("Access-Control-Allow-Headers: X-Requested-With");
			header("Access-Control-Request-Headers: X-Requested-With");
			try
			{
				$rs = json_decode(Functions::checkRecaptcha($_POST['g-recaptcha-response']??''));
				if(!$rs->success)
					throw new Exception('Please verify captcha');
				unset($_POST['g-recaptcha-response']);
				if(!isset($_SESSION['forgot']['email']) || !isset($_POST['code']) || !isset($_POST['password']) || !isset($_POST['re-password']))
					throw new Exception('Not empty');
				Validation::init($_POST);
				$count = count($_POST);
				if(count(Validation::getError()) !== 0 || $count !== Validation::getStatus() || $count !== 3)
					throw new Exception('Check input again');
				$checkCode = $this->dbAuth_User->getCode(['email' => $_SESSION['forgot']['email'],'code' => $_POST['code'],'rule' => 1],1);
				if(!$checkCode)
					throw new Exception('Incorrect code');
				$this->dbAuth_User->deleteCode($_POST['code'],$_SESSION['forgot']['email']);
				$newPass = $this->dbUser->newPassword($_POST['password'],$_SESSION['forgot']['email']);
				if(!$newPass)
					throw new Exception('An unknow error');
				echo json_encode(['status' => 1,'message' => 'Change password successfully']);
			}
			catch(Exception $e)
			{
				echo json_encode(['status' => 0,'message' => $e->getMessage()]);
			}
		}

		public function changePassword()
		{
			header('Access-Control-Allow-Origin: '.DOMAIN);
			header('Access-Control-Allow-Methods: PUT');
			header("Access-Control-Allow-Headers: X-Requested-With");
			header("Access-Control-Request-Headers: X-Requested-With");
			header("Access-Control-Request-Headers: X-Requested-With");
			header('Access-Control-Allow-Credentials: true');
			parse_str(file_get_contents("php://input"),$put);
			try
			{
				$atk = (array)JWT::decode($_COOKIE['_atk']??'',PRIVATE_KEY,[ALGO_TOKEN]);
				$this->check($atk,$_COOKIE['_atk'],$_COOKIE['_rft']??'');
				if(!isset($_SESSION['user']))
					throw new Exception('Not Login');
				$rs = json_decode(Functions::checkRecaptcha($put['g-recaptcha-response'] ?? ""));
				if(!$rs->success)
				{
					echo json_encode(['status' => 0,'message' => 'Please verify captcha']);
					exit;
				}
				unset($put['g-recaptcha-response']);
				if(!isset($put['new-password']) || !isset($put['current-password']) || !isset($put['re-password']))
					throw new Exception('Not Empty');
				Validation::init($put);
				$count = count($put);
				if(count(Validation::getError()) !== 0 || $count !== Validation::getStatus() || $count !== 3)
					throw new Exception('Check input again');
				if($this->dbUser->changePassword($put))
				{
					$tokens = $this->dbToken->insertToken($_SESSION['user']);
					if($tokens)
					{
						Functions::setCookieOptions('expires',strtotime('+7 days'));
						Functions::setCookie($tokens);
					}
					echo \json_encode(['status' => 1,'message' => 'Change password success']);
				}
			}
			catch(Exception $e)
			{
				echo \json_encode(['status' => 0,'message' => $e->getMessage()]);
			}
		}

		public function deleteFile($idFile = '')
		{
			header('Access-Control-Allow-Origin: '.DOMAIN);
			header('Access-Control-Allow-Methods: DELETE');
			header("Access-Control-Allow-Headers: X-Requested-With");
			header("Access-Control-Request-Headers: X-Requested-With");
			header('Access-Control-Allow-Credentials: true');
			try
			{
					$idUser = $_SESSION['user']['id']??'NULL';
					$idFile = $idFile ?: 'NULL';
					$message = $this->dbFile->deleteFile(['idFiles' => [$idFile], 'idUser' => $idUser,'check' => false]);
					echo json_encode(['status' => 1,'message' => $message]);
			}
			catch (Exception $e)
			{
				echo json_encode(['status' => 0,'message' => $e->getMessage()]);
			}
		}

		public function paymentMOMO()
		{
			header('Access-Control-Allow-Origin: '.DOMAIN);
			header('Access-Control-Allow-Methods: GET');
			header("Access-Control-Allow-Headers: X-Requested-With");
			header("Access-Control-Request-Headers: X-Requested-With");
			header('Access-Control-Allow-Credentials: true');
			try
			{
				$array = [
					"partnerCode"   => CODE_MOMO,
					"accessKey"     => KEY_MOMO,
					"requestId"     => "MM".date('YmdHis'),
					"amount"        => "10000",
					"orderId"       => "MM".date('YmdHis'),
					"orderInfo"     => "Donate Upload File",
					"returnUrl"     => RETURN_MOMO,
					"notifyUrl"     => DOMAIN,
					"extraData"     => "email=".$_SESSION['user']['email']
				];
				$signature = hash_hmac('SHA256',urldecode(http_build_query($array)),SECRET_MOMO);
				$array['requestType'] = 'captureMoMoWallet';
				$array['signature'] = $signature;
				$response = json_decode(Functions::getResponseMOMO($array),true);
				if($response['errorCode'] != 0)
					throw new Exception($response['message']);
				echo json_encode(['status' => 1,'url' => $response['payUrl']]);
			}
			catch(Exception $e)
			{
				echo json_encode(['status' => 0,'message' => $e->getMessage()]);
			}
		}
	}