<?php
    namespace App\Controllers;
    use App\Core\Controller;
    use App\Core\Functions;
use DateTimeImmutable;
use Exception;

class User extends Controller
    {
        private $dbAuth;
        private $dbUser;
        private $dbToken;
        private $dbOrder;

        function __construct()
        {
            parent::__construct();
            $this->dbAuth = $this->loadModel('Auth');
            $this->dbUser = $this->loadModel('User');
            $this->dbToken = $this->loadModel('Token');
            $this->dbOrder = $this->loadModel('Order');
        }

        public function register()
        {
            $urlFb = $this->facebook();
            $urlGG = $this->google();
            $title = 'Register';
            return $this->view('user/register',['urlFb' => $urlFb,'urlGG' =>$urlGG,'title' => $title]);
        }

        public function login($id = '')
        {
            $urlFb = $this->facebook();
            $urlGG = $this->google();
            $title = 'Login';
            return $this->view('user/login',['urlFb' => $urlFb,'urlGG' =>$urlGG,'title' => $title]);
        }

        public function auth($id = '')
        {
            $message = [];
            if($id)
            {
                $params = $this->dbAuth->selectAuth($id);
                if($params && !$params['status'])
                {
                    $idUser = $params['idUser'];
                    $this->dbUser->updateStatus($idUser);
                    $message= ['status' => 1,'confirm' => 0,'message' => 'Email verification is successful'];
                }
                elseif($params && $params['status'])
                    $message = ['status' => 0,'confirm' => 1,'message' => 'Link has expired'];
                else
                    $message = ['status' => 0,'confirm' => 0,'message' => 'Link not exits'];
            }
            else
            {
                if(!isset($_SESSION['user']) || (isset($_SESSION['user']) && $_SESSION['user']['status']))
                    $message = ['status' => 0,'confirm' => 0,'message' => 'Link not exits'];
                if(isset($_SESSION['user']) && !$_SESSION['user']['status'])
                    $message = ['status' => 1,'confirm' => 1,'message' => 'Verify your email'];
                if(isset($_SESSION['user']) && !$_SESSION['user']['blocked'])
                    $message = ['status' => 0,'confirm' => 0,'message' => 'Account has been locked'];
            }
            return $this->view('error/errorMessage',$message);
        }

        public function manager()
        {
            return $this->view('user/manager');
        }

        public function donate()
        {
            try
            {
                if(empty($_GET))
                    throw new Exception('An unknow error');
                if(!isset($_GET['orderId']) || !isset($_GET['requestId']))
                    throw new Exception('Donate is empty');
                if($_GET['orderId'] !== $_GET['requestId'])
                    throw new Exception('Error donate');
                $idOrder = $_GET['orderId'];
                $order = $this->dbOrder->select(['id' => $idOrder,'idUser' => $_SESSION['user']['id']??'NULL','check' => false]);
                if($order && $order['status'])
                    throw new Exception('Donate has been paid');
                $array = [
                    "partnerCode"   => CODE_MOMO,
                    "accessKey"     => KEY_MOMO,
                    "requestId"     => $idOrder,
                    "orderId"       => $idOrder,
                    "requestType"   => "transactionStatus"
                ];
                $signature = hash_hmac('SHA256',urldecode(http_build_query($array)),SECRET_MOMO);
                $array['signature'] = $signature;
                $res = json_decode(Functions::getResponseMOMO($array),true);
                if($res['errorCode'] != 0 && $res['errorCode'] != -1 && $res['errorCode'] != 49)
                    throw new Exception($res['message']);
                $status = $res['errorCode'] == 0?1:0;
                $message = $res['errorCode'] == 0?'Donate successfully':'Donate was canceled';
                $params = [
                    'id'             => $idOrder,
                    'status'         => $status,
                    'idUser'         => $_SESSION['user']['id']??'NULL',
                    'transaction_id' => $res['transId'],
                    'type'           => 'momo',
                    'amount'         => $res['amount']
                ];
                $createOrder = $this->dbOrder->insert($params);
                $_SESSION['payment']['status'] = 1;
                $_SESSION['payment']['message'] = $message;
            }
            catch(Exception $e)
            {
                if($e->getMessage() != '')
                {
                    $_SESSION['payment']['status'] = 0;
                    $_SESSION['payment']['message'] = $e->getMessage();
                }
            }
            header('location: /user/manager');
        }

        public function forgotPassword()
        {
            $title = 'Reset Password';
            return $this->view('user/forgot',['title' => $title]);
        }

        public function authFacebook()
        {
            $response = $this->callbackFacebook();
            Functions::setCookieLogin($response,$this->dbUser,$this->dbToken);
        }

        public function authGoogle()
        {
            try
            {
                $response = $this->callbackGoogle();
                Functions::setCookieLogin($response,$this->dbUser,$this->dbToken);
            }
            catch(Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function logout()
        {
            if(!isset($_SESSION['user']))
                return header('location: /user/login');
            Functions::setCookieOptions('expires',strtotime('-7 days'));
            Functions::deleteCookie();
            header('location: /user/login');
        }
    }