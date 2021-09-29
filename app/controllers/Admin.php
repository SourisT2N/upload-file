<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Functions;

class Admin extends Controller
{
    private $dbOrder;
    private $dbUser;
    private $dbFile;
    function __construct()
    {
        $this->dbOrder = $this->loadModel('Order');
        $this->dbUser = $this->loadModel('User');
        $this->dbFile = $this->loadModel('File');
    }

    public function index()
    {
        $data = [];
        $data['users'] = $this->dbUser->select([["fullname","email","date_created","status"],['order by date_created desc',"limit 5"]]);
        $data['files'] =$this->dbFile->select([['f._nameFile','f.storages','f.date_upload','u.fullname'],['order by f.date_upload desc',"limit 5"]]);
        $data['donates'] = $this->dbOrder->selectAll([["u.fullname","o.amount","o.date_donate",'o.status'],['order by o.date_donate desc','limit 5']]);
        return $this->view('admin/index',$data);
    }

    public function login()
    {
        $data = [];
        if(isset($_SESSION['user']) && ($_SESSION['user']['permission'] == 0 || $_SESSION['user']['status'] == 0 || $_SESSION['user']['blocked'] == 0))
        {
            Functions::deleteCookie();
            unset($_SESSION['user']);
            $data['message'] = 'Your account is not allowed to access';
        }
        return $this->view('admin/login',$data);
    }

    public function files()
    {
        return $this->view('admin/files');
    }

    public function donates()
    {
        return $this->view('admin/donates');
    }

    public function users()
    {
        return $this->view('admin/users');
    }

    public function addProfile()
    {
        return $this->view('admin/profile',['page' => 'add']);
    }

    public function showProfile($id)
    {
        $user = $this->dbUser->selectUser('',$id);
        if(!$user)
            return $this->view('admin/blank');
        $data['user'] = $user;
        $data['page'] = 'edit';
        return $this->view('admin/profile',$data);
    }

    public function checkCode() 
    {
        return $this->view('admin/checkCode');
    }

    public function mail()
    {
        return $this->view('admin/mail');
    }
}