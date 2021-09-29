<?php
use App\Core\Model;

class Auth extends Model
{
    public function insertAuth($params,$check)
    {
        try
        {
            extract($params);
            $token = bin2hex(random_bytes(45).$id);
            $url = DOMAIN.'/user/auth/'.$token;
            $query = "insert into auth(idUrl,idUser,date_expires) values('$token',$id,DATE_ADD(NOW(), INTERVAL 5 MINUTE));";
            if($check)
                $query = "update auth set idUrl = '$token',date_expires = DATE_ADD(NOW(), INTERVAL 5 MINUTE) where idUser = $id";
            if($this->conn->query($query))
            {
                $subject = "Welcome To Upload File";
                if($check)
                    $subject = "Resend Verification Link";
                $body = "<p>Please Click This Link To Confirm Email</p><a href='$url'>Confirm Email</a>";
                return ['email' => $email,'name' => $fullname,'subject' => $subject,'body' => $body];
            }
        }
        catch(Exception $e)
        {
            return false;   
        }
    }

    public function selectAuth($id)
    {
        $query = "select idUser,date_expires from auth,users AS u where idUrl = '$id' AND idUser = u.id and u.status = 0";
        $excute = $this->conn->query($query);
        if($excute && $excute->num_rows > 0)
        {
            $row = $excute->fetch_assoc();
            $id = $row['idUser'];
            $date_expires = strtotime($row['date_expires']);
            $status = time() > $date_expires;
            return ['status' => $status,'idUser' => $id];
        }
        return false;
    }

    public function selectUser($idUrl)
    {
        $idUrl = $this->conn->real_escape_string($idUrl);
        $query = "select u.id,u.fullname,u.email from users AS u , auth AS a where u.id = a.idUser AND a.idUrl = '$idUrl'";
        $excute = $this->conn->query($query);
        if($excute && $excute->num_rows > 0)
            return $excute->fetch_assoc();
        return false;
    }
}