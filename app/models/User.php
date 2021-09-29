<?php
    use App\Core\Model;
    class User extends Model
    {
        public function select($finds)
        {
            try
            {
                $column = $finds[0];
                $after  = $finds[1]??[];
                $str = implode(',', $column);
                $query = "select $str from users";
                if(!empty($column))
                {
                    $afterWhere = implode(' ', $after);
                    $query .= " $afterWhere";
                }
                $data  = [];
                $exec  = $this->conn->query($query);
                if($exec)
                {
                    while($rows = $exec->fetch_assoc())
                        $data[] = $rows;
                }
                return $data;
            }
            catch(Exception $e)
            {
                throw new Exception($e->getMessage());
            }
        }

        public function selectUser($email,$id = '')
        {
            try
            {
                $email = $this->conn->escape_string(strtolower($email)??'');
                $id    = $id?$this->conn->escape_string($id):'NULL';
                $query = "select id,fullname,email,password,api_key,status,blocked,permission from users where email = '$email' or id = $id";
                $excute = $this->conn->query($query);
                if($excute && $excute->num_rows > 0)
                    return $excute->fetch_assoc();
            }
            catch(Exception $e)
            {
                throw new \Exception($e->getMessage());
            }
        }

        public function createUser(array $params)
        {
            try
            {
                extract($params);
                $fullname = $this->conn->escape_string($fullname??'');
                $email = $this->conn->escape_string(strtolower($email)??'');
                $password = password_hash($password??'',PASSWORD_BCRYPT);
                $api_key =  bin2hex($email.openssl_random_pseudo_bytes(20));
                $date = (new DateTimeImmutable())->format("Y-m-d h:i:s");
                $permission = $permission??0;
                $status = $status??0;
                $blocked = $blocked??1;
                $query = "insert into users(fullname,email,password,api_key,date_created,date_update,status,blocked,permission) values('$fullname','$email','$password','$api_key','$date','$date',$status,$blocked,$permission)";
                if($this->conn->query($query));
                    return ['id' => $this->conn->insert_id,'email' => $email,'fullname' => $fullname,'api_key' => $api_key,'password' => $password,'status' => $status,'blocked' => 1];
            }
            catch(Exception $e)
            {
                throw new Exception($e->getMessage());
            }
        }

        public function updateStatus($idUser)
        {
            $idUser = $this->conn->real_escape_string($idUser??'');
            $query = "update users set status = 1 where id = $idUser and status = 0";
            if($this->conn->query($query))
                return true;
            return false;
        }

        public function checkLogin($params)
        {
            try
            {
                if($user = $this->selectUser($params['email']))
                {
                    $pass = $user['password'];
                    if(password_verify($params['password'],$pass))
                    {
                        if(!$user['blocked'])
                            return ['status' => 0,'message' => 'Account has been locked'];
                        $_SESSION['user'] = $user;
                        return ['status' => 1,'message' => 'Login successfully','user' => $user];
                    }
                }
                return ['status' => 0,'message' => 'Email or password is incorrect'];
            }
            catch(Exception $e)
            {
                return ['status' => 0,'message' => $e->getMessage()];
            }
        }

        public function changePassword($params)
        {
            $nPass = password_hash($params['new-password']??'',PASSWORD_BCRYPT);
            $oPass = $params['current-password'];
            $date  = (new DateTimeImmutable())->format("Y-m-d H:i:s");
            if(password_verify($oPass,$_SESSION['user']['password']))
            {
                $query = "update users set password = '$nPass',date_update = '$date' where id = ".$_SESSION['user']['id'];
                if($this->conn->query($query))
                {
                    $_SESSION['user']['password'] = $nPass;
                    return true;
                }
                throw new Exception("An Unknow Error");
            }
            throw new Exception("Password incorrect");
        }

        public function newPassword($password,$email)
        {
            $nPass = password_hash($password??'',PASSWORD_BCRYPT);
            $query = "update users set password = '$nPass' where email = '$email'";
            if($this->conn->query($query) && $this->conn->affected_rows > 0)
                return true;
            return false;
        }

        public function delete($params,$permission = '')
        {
            $arrId = $this->conn->real_escape_string(join(',',$params));
            $query = "delete from users where id in ($arrId)";
            if($permission)
                $query .= " and permission < $permission";
            if($this->conn->query($query) && $this->conn->affected_rows > 0)
                return true;
            return false;
        }
        
        public function updateUser($params,$bool)
        {
            try
            {
                $id = $params['id'];
                $permission = $params['userPer']??'';
                unset($params['id']);
                unset($params['userPer']);
                $query = 'update users set ';
                foreach ($params as $key => $val)
                {
                    $val = $this->conn->real_escape_string($val);
                    $key = $this->conn->real_escape_string($key);
                    $query .= "$key = ";
                    if($key == 'fullname' || $key == 'email')
                        $query .= "'$val', ";
                    elseif(($key == 'status' || $key == 'blocked') && !$bool)
                        $query .= "if($key = 0,1,0), ";
                    else
                        $query .= "$val, ";
                }
                $date  = (new DateTimeImmutable())->format("Y-m-d H:i:s");
                $query .= "date_update = '$date' where id = $id";
                if($permission)
                    $query .= "  and permission < $permission";
                if($this->conn->query($query) && $this->conn->affected_rows > 0)
                    return true;
                return false;
            }
            catch(Exception $e)
            {
                throw new Exception($e->getMessage());
            }
        }
    }