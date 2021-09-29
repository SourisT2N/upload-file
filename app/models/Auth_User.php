<?php
use App\Core\Model;
class Auth_User extends Model
{
    public function getCode($arr,$status = 0)
    {
        try
        {
            extract($arr);
            $query = "select ua.id ,ua.code from user_auth as ua ";
            if($status)
                $query .= ",users as u where ua.idUser = u.id and u.email = '$email' and ua.date_expires > now() and ua.rule = $rule and ua.code = $code;";
            else
                $query .= "where ua.idUser = $id and ua.date_expires > now() and ua.rule = $rule;";
            $exec = $this->conn->query($query);
            if($exec && $exec->num_rows > 0)
                return $exec->fetch_assoc();
            return false;
        }
        catch(Exception $e)
        {
            throw new Exception("Error: ".$e->getMessage());
        }
    }

    public function createCode($id,$rule)
    {
        try
        {
            $code = $this->getCode(['id' => $id,'rule' => $rule]);
            $date_expires = (new DateTimeImmutable())->modify('+5 minutes')->format('Y-m-d H:i:s');
            if($code)
            {
                $code = $code['code'];
                $query = "update user_auth set date_expires = '$date_expires' where idUser = $id and code = $code";
            }
            else
            {
                $code = mt_rand(100000,999999);
                $query = "insert into user_auth set code = $code,date_expires = '$date_expires',rule = $rule,idUser = $id;";
            }
            if($this->conn->query($query) && $this->conn->affected_rows > 0)
                return $code;
            return false;
        }
        catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }
    }

    public function deleteCode($code,$email)
    {
        try
        {
            $query = "DELETE user_auth from user_auth inner join users where user_auth.idUser = users.id AND users.email = '$email' and user_auth.code = $code;";
            if($this->conn->query($query) && $this->conn->affected_rows > 0)
                return true;
        }
        catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }
    }
}