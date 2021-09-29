<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Functions;
use App\Core\Validation;

class AjaxAdmin extends Controller
{
    private $dbOrder;
    private $dbFile;
    private $dbUser;

    function __construct()
    {
        $this->dbOrder = $this->loadModel('Order');
        $this->dbFile = $this->loadModel('File');
        $this->dbUser = $this->loadModel('User');
        $this->dbToken = $this->loadModel('Token');
    }

    public function getChart()
    {
        header('Access-Control-Allow-Origin: '.DOMAIN);
        header('Access-Control-Allow-Methods: GET');
        header("Access-Control-Allow-Headers: X-Requested-With");
        header("Access-Control-Request-Headers: X-Requested-With");
        try
        {
            $data = [];
            $data['users'] = $this->dbUser->select([['count(id) as total']])[0]['total'];
            $data['files'] = $this->dbFile->select([['count(f._idFile) as total']])[0]['total'];
            $donates = $this->dbOrder->selectAll([['count(o.id) as total,sum(o.amount) as amounts'],['and o.status = 1']]);
            $data['donates'] = $donates[0]['total'];
            $data['totalAmount'] = $donates[0]['amounts'];
            echo json_encode(['status' => '200','message' => 'success','data' => $data]);
        }
        catch (\Exception $e)
        {
            echo json_encode(['status' => '500','message' =>'Error: '.$e->getMessage()]);
        }
    }

    public function getFiles()
    {
        header('Access-Control-Allow-Origin: '.DOMAIN);
        header('Access-Control-Allow-Methods: GET');
        header("Access-Control-Allow-Headers: X-Requested-With");
        header("Access-Control-Request-Headers: X-Requested-With");
        try
        {
            $data = $this->dbFile->selectFileUser(['check' => true]);
            echo json_encode(['status' => '200','message' => 'success','data' => $data['data']??[]]);
        }
        catch(\Exception $e)
        {
            echo json_encode(['status' => '500','message' =>'Error: '.$e->getMessage()]);
        }
    }

    public function deleteFiles()
    {
        header('Access-Control-Allow-Origin: '.DOMAIN);
        header('Access-Control-Allow-Methods: DELETE');
        header("Access-Control-Allow-Headers: X-Requested-With");
        header("Access-Control-Request-Headers: X-Requested-With");
        header('Content-Type: application/json');
        try
        {
            $data = json_decode(file_get_contents('php://input'),true)['idFiles']??[];
            if(empty($data))
                throw new \Exception('Not Empty');
            $message = $this->dbFile->deleteFile(['idFiles' => $data,'check' => true,'idUser' => $_SESSION['user']['id'],'permission' => $_SESSION['user']['permission']]);
            echo json_encode(['status' => 200,'message' => $message]);
        }
        catch(\Exception $e)
        {
            echo json_encode(['status' => 500,'message' => $e->getMessage()]);
        }
    }

    public function getDonates()
    {
        header('Access-Control-Allow-Origin: '.DOMAIN);
        header('Access-Control-Allow-Methods: GET');
        header("Access-Control-Allow-Headers: X-Requested-With");
        header("Access-Control-Request-Headers: X-Requested-With");
        try
        {
            $data = $this->dbOrder->selectAll([['o.id','o.status','u.fullname','o.transaction_id','o.type','o.amount','o.date_donate']])??[];
            print_r(json_encode(['status' => 200,'message' => 'success','data' => $data]));
        }
        catch(\Exception $e)
        {
            echo json_encode(['status' => 500,'message' => $e->getMessage()]);
        }
    }

    public function deleteOrders()
    {
        header('Access-Control-Allow-Origin: '.DOMAIN);
        header('Access-Control-Allow-Methods: DELETE');
        header("Access-Control-Allow-Headers: X-Requested-With");
        header("Access-Control-Request-Headers: X-Requested-With");
        header('Content-Type: application/json');
        try
        {
            $data = json_decode(file_get_contents('php://input'), true)['arrId']??[];
            if(empty($data))
                throw new \Exception('Data not empty');
            $this->dbOrder->deleteOrders($data,$_SESSION['user']['id']);
            echo json_encode(['status' => 200,'message' => 'Delete success']);
        }
        catch(\Exception $e)
        {
            echo json_encode(['status' =>500,'message' => $e->getMessage()]);
        }
    }

    public function addOrder()
    {
        header('Access-Control-Allow-Origin: '.DOMAIN);
        header('Access-Control-Allow-Methods: POST');
        header("Access-Control-Allow-Headers: X-Requested-With");
        header("Access-Control-Request-Headers: X-Requested-With");
        try
        {
            $post = $_POST;
            if(!isset($post['id']) || !isset($post['date']) || !isset($post['transaction_id']) || !isset($post['type']) || !isset($post['amount']) || !isset($post['email']) || !isset($post['status']))
                throw new \Exception('Data not empty');
            Validation::init($post);
            $count = count($post);
            if(count(Validation::getError()) != 0 || $count !== Validation::getStatus() || $count !== 7)
                throw new \Exception('Check input again');
            $user = $this->dbUser->selectUser($post['email']);
            if(!$user)
                throw new \Exception('User not found');
            $post['idUser'] = $user['id'];
            $this->dbOrder->insert($post);
            echo json_encode(['status' => '200','message' => 'Add order success']);
        }
        catch(\Exception $e)
        {
            echo json_encode(['status' =>500,'message' => $e->getMessage()]);
        }
    }
    
    public function getOrderId($id)
    {
        header('Access-Control-Allow-Origin: '.DOMAIN);
        header('Access-Control-Allow-Methods: GET');
        header("Access-Control-Allow-Headers: X-Requested-With");
        header("Access-Control-Request-Headers: X-Requested-With");
        try
        {
            if(!$id)
                throw new \Exception('Id not empty');
            $order = $this->dbOrder->select(['id' => $id,'check' => true]);
            if(!$order)
                throw new \Exception('Not found donate');
            echo json_encode(['status' =>200,'data' => $order]);
        }
        catch(\Exception $e)
        {
            echo json_encode(['status' =>500,'message' => $e->getMessage()]);
        }
    }

    public function updateOrder($id)
    {
        header('Access-Control-Allow-Origin: '.DOMAIN);
        header('Access-Control-Allow-Methods: PUT');
        header("Access-Control-Allow-Headers: X-Requested-With");
        header("Access-Control-Request-Headers: X-Requested-With");
        try
        {
            if(!$id)
                throw new \Exception('Id not empty');
            $post = json_decode(file_get_contents('php://input'), true);
            if(!isset($post['id']) || !isset($post['date']) || !isset($post['transaction_id']) || !isset($post['type']) || !isset($post['amount']) || !isset($post['email']) || !isset($post['status']))
                throw new \Exception('Data not empty');
            Validation::init($post);
            $count = count($post);
            if(count(Validation::getError()) !== 0 || $count !== Validation::getStatus() || $count !== 7)
                    throw new \Exception('Check input again');
            $post['oldId'] = $id??'';
            $check = $this->dbOrder->updateOrder($post);
            if(!$check)
                throw new \Exception('Update error');
            echo json_encode(['status' =>200,'message' => 'Update successfully']);
        }
        catch(\Exception $e)
        {
            echo json_encode(['status' =>500,'message' => $e->getMessage()]);
        }
    }

    public function getUsers()
    {
        header('Access-Control-Allow-Origin: '.DOMAIN);
        header('Access-Control-Allow-Methods: GET');
        header("Access-Control-Allow-Headers: X-Requested-With");
        header("Access-Control-Request-Headers: X-Requested-With");
        try
        {
            $data = $this->dbUser->select([['id','fullname','permission','email','date_created','date_update','status','blocked']]);
            echo json_encode(['status' => 200,'message' => 'Success','data' => $data]);
        }
        catch (\Exception $e)
        {
            echo json_encode(['status' =>500,'message' => $e->getMessage(),'data' => []]);
        }
    }
    
    public function deleteUsers()
    {
        header('Access-Control-Allow-Origin: '.DOMAIN);
        header('Access-Control-Allow-Methods: DELETE');
        header("Access-Control-Allow-Headers: X-Requested-With");
        header("Access-Control-Request-Headers: X-Requested-With");
        try
        {
            $data = json_decode(file_get_contents('php://input'), true)['arrId']??[];
            if(empty($data))
                throw new \Exception('Data not empty');
            if(!$this->dbUser->delete($data,$_SESSION['user']['permission']))
                throw new \Exception('Delete failed');
            echo json_encode(['status' => 200,'message' => 'Delete Successful']);
        }
        catch (\Exception $e)
        {
            echo json_encode(['status' =>500,'message' => $e->getMessage()]);
        }
    }

    public function changeStatus()
    {
        header('Access-Control-Allow-Origin: '.DOMAIN);
        header('Access-Control-Allow-Methods: PUT');
        header("Access-Control-Allow-Headers: X-Requested-With");
        header("Access-Control-Request-Headers: X-Requested-With");
        try
        {
            $data = json_decode(file_get_contents('php://input'), true);
            if(!isset($data['id']) || (!isset($data['status']) && !isset($data['blocked'])) || count($data) != 2)
                throw new \Exception('Check input again');
            if($data['id'] !== $_SESSION['user']['id'])
                $data['userPer'] = $_SESSION['user']['permission'];
            if(!$this->dbUser->updateUser($data,false))
                throw new \Exception('Update failed');
            if($data['id'] == $_SESSION['user']['id'])
            {
                $user = $this->dbUser->selectUser('',$_SESSION['user']['id']);
                $_SESSION['user'] = $user;
                $tokens = $this->dbToken->insertToken($_SESSION['user']);
                if($tokens)
                {
                    Functions::setCookieOptions('expires',strtotime('+7 days'));
                    Functions::setCookie($tokens);
                }
            }
            echo json_encode(['status' => 200,'message' => 'Update success']);
        }
        catch (\Exception $e)
        {
            echo json_encode(['status' => 500,'message' => $e->getMessage()]);
        }
    }

    public function createUser()
    {
        header('Access-Control-Allow-Origin: '.DOMAIN);
        header('Access-Control-Allow-Methods: POST');
        header("Access-Control-Allow-Headers: X-Requested-With");
        header("Access-Control-Request-Headers: X-Requested-With");
        try
        {
            $post = $_POST;
            if(!isset($post['fullname']) || !isset($post['email']) || !isset($post['password']) || !isset($post['permission']) || !isset($post['status']) || !isset($post['blocked']))
                throw new \Exception('Data not empty');
            Validation::init($post);
            $count = count($post);
            if(count(Validation::getError()) !== 0 || $count !== Validation::getStatus() || $count !== 6)
                throw new \Exception('Check input again');
            if($post['permission'] > $_SESSION['user']['permission'])
                throw new \Exception('Check permission');
            if($this->dbUser->selectUser($post['email']))
                throw new \Exception('Email exits');
            $this->dbUser->createUser($post);
            echo json_encode(['status' => 200,'message' => 'Create successfully']);
        }
        catch (\Exception $e)
        {
            echo json_encode(['status' =>500,'message' => $e->getMessage()]);
        }
    }

    public function updateUser()
    {
        header('Access-Control-Allow-Origin: '.DOMAIN);
        header('Access-Control-Allow-Methods: PUT');
        header("Access-Control-Allow-Headers: X-Requested-With");
        header("Access-Control-Request-Headers: X-Requested-With");
        try
        {
            $post = json_decode(file_get_contents('php://input'), true);
            if(!isset($post['id']) || !isset($post['fullname']) || !isset($post['email']) || !isset($post['permission']) || !isset($post['status']) || !isset($post['blocked']))
                throw new \Exception('Data not empty');
            Validation::init($post);
            $count = count($post);
            if(count(Validation::getError()) !== 0 || $count !== Validation::getStatus() || $count !== 6)
                throw new \Exception('Check input again');
            if($post['permission'] > $_SESSION['user']['permission'])
                throw new \Exception('Check permission');
            if($post['id'] !== $_SESSION['user']['id'])
                $post['userPer'] = $_SESSION['user']['permission'];
            if(!$this->dbUser->updateUser($post,true))
                throw new \Exception('Update failed');
            if($post['id'] == $_SESSION['user']['id'])
            {
                $user = $this->dbUser->selectUser('',$_SESSION['user']['id']);
                $_SESSION['user'] = $user;
                $tokens = $this->dbToken->insertToken($_SESSION['user']);
                if($tokens)
                {
                    Functions::setCookieOptions('expires',strtotime('+7 days'));
                    Functions::setCookie($tokens);
                }
            }
            echo json_encode(['status' => 200,'message' => 'Update success']);
        }
        catch (\Exception $e)
        {
            echo json_encode(['status' =>500,'message' => $e->getMessage()]);
        }
    }

    public function checkCode($code)
    {
        header('Access-Control-Allow-Origin: '.DOMAIN);
        header('Access-Control-Allow-Methods: GET');
        header("Access-Control-Allow-Headers: X-Requested-With");
        header("Access-Control-Request-Headers: X-Requested-With");
        try
        {
            $array = [
                "partnerCode"   => CODE_MOMO,
                "accessKey"     => KEY_MOMO,
                "requestId"     => $code,
                "orderId"       => $code,
                "requestType"   => "transactionStatus"
            ];
            $signature = hash_hmac('SHA256',urldecode(http_build_query($array)),SECRET_MOMO);
            $array['signature'] = $signature;
            $res = json_decode(Functions::getResponseMOMO($array),true);
            echo json_encode(['status' => 200,'message' => 'Get donate success','data' => $res]);
        }
        catch (\Exception $e)
        {
            echo json_encode(['status' => 500,'message' => $e->getMessage()]);
        }
    }

    public function sendMail()
    {
        header('Access-Control-Allow-Origin: '.DOMAIN);
        header('Access-Control-Allow-Methods: POST');
        header("Access-Control-Allow-Headers: X-Requested-With");
        header("Access-Control-Request-Headers: X-Requested-With");
        try
        {
            $post = $_POST;
            if(!isset($post['name']) || !isset($post['email']) || !isset($post['subject']) || !isset($post['body']))
                throw new \Exception('Data not empty');
            if(count($post) != 4)
                throw new \Exception('Check input again');
            echo json_encode(['status' =>200,'message' => 'Send mail successfully']);
            Functions::sendMail($post['email'],$post['name'],$post['subject'],$post['body']);
        }
        catch(\Exception $e)
        {
            echo json_encode(['status' =>500,'message' => $e->getMessage()]);
        }
    }
}