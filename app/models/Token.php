<?php 
	use App\Core\Model;
	use Firebase\JWT\JWT;

class Token extends Model
	{
		public function insertToken($user,$status = 0)
		{
			try
			{
				$id = $user['id'];
				$issuedAt   = new DateTimeImmutable();
				$expire     = $issuedAt->modify('+5 minutes')->getTimestamp();
				$expire_refresh     = $issuedAt->modify('+1 day')->format('Y-m-d H:i:s');
				$accessPayload = array(
					"iss" => DOMAIN,
					"iat" => $issuedAt->getTimestamp(),
					"nbf" => $issuedAt->getTimestamp(),
					"exp" => $expire,
					"dataId" => $id,
				);
				$refreshToken = bin2hex(openssl_random_pseudo_bytes(50).$user['id'].$user['api_key'].time());
				$accessToken = JWT::encode($accessPayload,PRIVATE_KEY,ALGO_TOKEN);
				$query = "insert into token(access_token,refresh_token,refresh_expires,idUser) values('$accessToken','$refreshToken','$expire_refresh',$id)";
				if($status)
					$query = "update token set access_token = '$accessToken',refresh_token = '$refreshToken',refresh_expires = '$expire_refresh' where access_token = '".$user['atk']."' and refresh_token = '".$user['rft']."'";
				if($this->conn->query($query))
					return ['accessToken' => $accessToken,'refreshToken' => $refreshToken];
				else
					return false;
			}
			catch(Exception $e)
			{
				return false;
			}
		}

		public function selectUserToken($atk,$rfk)
		{
			try
			{
				$query = "select u.id,u.fullname,u.email,u.api_key,u.status,u.blocked,u.password,u.permission from token,users as u where idUser = u.id and access_token = '$atk' and refresh_token = '$rfk' and refresh_expires > NOW()";
				$excute = $this->conn->query($query);
				if($excute && $excute->num_rows > 0 )
					return $excute->fetch_assoc();
			}
			catch(Exception $e)
			{
				return false;
			}
		}
	}