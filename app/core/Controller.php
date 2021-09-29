<?php 
	namespace App\Core;

use Exception;
use Facebook\Exceptions;
	use Facebook\Facebook;
use Google_Client;
use Google_Service_Oauth2;

class Controller
	{

		private $options = [
			'app_id' => IDAPP_FB,
			'app_secret' => SECRET_FB,
			'default_graph_version' => 'v10.0'
        ];
		
		private $fb;
        private $gg;
		function __construct()
		{
			$this->fb = new Facebook($this->options);
            $this->gg = new Google_Client();
            $this->gg->setClientId(IDAPP_GG);
            $this->gg->setClientSecret(SECRET_GG);
            $this->gg->setRedirectUri(DOMAIN.'/user/google/auth');
            $this->gg->addScope("email");
            $this->gg->addScope("profile");
		}

		protected function loadModel($dbName)
		{
			$path = APP_PATH . "models/$dbName.php";
			if(file_exists($path))
			{
				require_once $path;
				return new $dbName;
			}
		}

		protected function view($pathName,$data = [])
		{
			$path = PUBLIC_PATH . "$pathName.php";
			if(file_exists($path))
				require $path;
			else
				require PUBLIC_PATH . "error/error404.php";
		}

		protected function facebook()
		{
			$helper = $this->fb->getRedirectLoginHelper();
			$permissions = ['email'];
			$loginUrl = $helper->getLoginUrl(DOMAIN.'/user/facebook/auth', $permissions);
			return $loginUrl;
		}

        protected function google()
        {
            return $this->gg->createAuthUrl();
        }

		protected function callbackFacebook()
		{
			$helper = $this->fb->getRedirectLoginHelper();
            try {
                $accessToken = $helper->getAccessToken();
            } catch(Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } 
            catch(Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }

            if (! isset($accessToken)) 
            {
                if ($helper->getError()) {
                  header('HTTP/1.0 401 Unauthorized');
                  echo "Error: " . $helper->getError() . "\n";
                  echo "Error Code: " . $helper->getErrorCode() . "\n";
                  echo "Error Reason: " . $helper->getErrorReason() . "\n";
                  echo "Error Description: " . $helper->getErrorDescription() . "\n";
                } else {
                  header('HTTP/1.0 400 Bad Request');
                  echo 'Bad request';
                }
                exit;
            }

            try {
                // Get the \Facebook\GraphNode\GraphUser object for the current user.
                // If you provided a 'default_access_token', the '{access-token}' is optional.
                $response = $this->fb->get('/me?locale=en_US&fields=name,email', $accessToken);
            } 
            catch(Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } 
            catch(Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
              
            $user = $response->getGraphUser();
			return ['fullname' => $user->getName(),'email' => $user->getEmail()];
		}

        protected function callbackGoogle()
        {
            try
            {
                if(isset($_GET['code']))
                {
                    $this->gg->authenticate($_GET['code']);
                    $token = $this->gg->getAccessToken();
                    $this->gg->setAccessToken($token);
                    $gg_services = new Google_Service_Oauth2($this->gg);
                    $data = $gg_services->userinfo->get();
                    return ['fullname' => $data['name'],"email" => $data['email']];
                }
            }
            catch(Exception $e)
            {
                http_response_code(400);
                throw new Exception("Bad Request");
            }
        }
	}