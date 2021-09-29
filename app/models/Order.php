<?php
use App\Core\Model;

use function PHPSTORM_META\expectedReturnValues;

class Order extends Model
{
    public function select($params)
    {
        extract($params);
        $id     = $this->conn->real_escape_string($id);
        $query  = "select o.*";
        if(!$check)
        {
            $idUser = $this->conn->real_escape_string($idUser);
            $query .= " from orders as o where o.id = '$id' and o.idUser = $idUser";
        }
        else
            $query .= ",u.email from orders as o,users as u where o.id = '$id' and u.id = o.idUser";
        $exec   = $this->conn->query($query);
        if($exec && $exec->num_rows > 0)
            return $exec->fetch_assoc();
        return false;
    }

    public function insert($params)
    {
        try
        {
            extract($params);
            $id = $this->conn->real_escape_string($id);
            $status = $this->conn->real_escape_string($status);
            $transaction_id = $this->conn->real_escape_string($transaction_id);
            $type = $this->conn->real_escape_string($type);
            $amount = $this->conn->real_escape_string($amount);
            $date_donate = (new DateTimeImmutable($date??''))->format('Y-m-d H:i:s');
            $query = "insert into orders values('$id',$status,$idUser,'$transaction_id','$type',$amount,'$date_donate');";
            if($this->conn->query($query) && $this->conn->affected_rows > 0)
                return true;
            throw new Exception('Id or transaction id exits');
        }
        catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }
    }

    public function selectAll($finds)
    {
       try
       {
            $column = $finds[0];
            $after = $finds[1]??[];
            $str = implode(',',$column);
            $query = "select $str from orders as o inner join users as u on o.idUser = u.id";
            if(!empty($after))
            {
                $afterWhere = implode(' ',$after);
                $query  .= " $afterWhere";
            }
            $data  = [];
            $exec  = $this->conn->query($query);
            if($exec)
                while($rows = $exec->fetch_assoc())
                    $data[] = $rows; 
            return $data;
       }
       catch(Exception $e)
       {
            throw new Exception($e->getMessage());
       }
    }

    public function deleteOrders($params,$idUser)
    {
        try
        {
            $strId = '"'.implode('","', $params).'"';
            $query = "DELETE FROM orders where id in ($strId);";
            if($this->conn->query($query) && $this->conn->affected_rows > 0)
                return true;
            throw new Exception('Error: '.$this->conn->error);
        }
        catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }
    }

    public function updateOrder($params)
    {
        try
        {
            extract($params);
            $oldId = $this->conn->real_escape_string($oldId);
            $id = $this->conn->real_escape_string($id);
            $status = $this->conn->real_escape_string($status);
            $transaction_id = $this->conn->real_escape_string($transaction_id);
            $type = $this->conn->real_escape_string($type);
            $amount = $this->conn->real_escape_string($amount);
            $date_donate = (new DateTimeImmutable($date??''))->format('Y-m-d H:i:s');
            $query = "update orders set id = '$id',transaction_id = '$transaction_id',type = '$type',status = $status,amount = $amount,date_donate = '$date_donate' where id = '$oldId'";
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