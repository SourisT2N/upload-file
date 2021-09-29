<?php
    namespace App\Core;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    class Functions
    {
        private static $strSize = ['KB','MB','GB','TB'];
        private static $cookieOptions = [
			'path'	  => '/',
			'domain'  => 'webfileupload.so',
			'secure'  => true,
			'httponly'=> true,
			'samesite'=> 'Lax'
		];
        public static function getSize($size,$count)
        {
            $length = count(self::$strSize);
            while(true)
            {
                if($size >= 1024 && $count < $length)
                {
                    $size /= 1024;
                    $count++;
                }
                else
                    break;
            }
            $str = $count > 0 ? self::$strSize[$count - 1] : 'B';
            return floor($size) . $str;
        }

        public static function deleteFileAuto($dir)
        {
            $file = scandir($dir);
            foreach($file as  $val)
            {
                if($val != '.' && $val != '..' && $val != '.htaccess')
                {
                    $path = $dir . $val;
                    if(is_dir($path))
                        self::deleteFileAuto($path . '/');
                    elseif(filectime($path) + 604800 < time())
                        unlink($path);
                }
            }
        }

        public static function deleteCookie()
        {
            setcookie('_rft','',self::$cookieOptions);
            setcookie('_atk','',self::$cookieOptions);
        }

        public static function setCookieOptions($key,$val)
        {
            self::$cookieOptions[$key] = $val;
        }

        public static function getCookieOptions()
        {
            return self::$cookieOptions;
        }

        public static function verifyEmail($className,$text,$flag)
        {
            echo "<p class='form-notify $className'>$text</p>";
            if($flag)
            echo "<div class='form-group btn-submit resend mt-4'>
                    <button class='btn btn-danger btn-block btn-round' id='resend'>Resend</button>
                </div>";
        }

        public static function setCookie($tokens)
        {
            setcookie('_rft',$tokens['refreshToken'],Functions::getCookieOptions());
            setcookie('_atk',$tokens['accessToken'],Functions::getCookieOptions());
        }

        public static function checkRecaptcha($response)
        {
            $remoteAddr = $_SERVER['REMOTE_ADDR'];
            $path = "https://www.google.com/recaptcha/api/siteverify?secret=".SECRET_CAPTCHA."&response=$response&remoteip=$remoteAddr";
            $file = file_get_contents($path);
            return $file;
        }

        
        public static function sendMail($mailTo,$name,$subject,$body)
        {
            $mail = new PHPMailer(true);
            try
            {
                $mail->isSMTP();
                $mail->CharSet = 'UTF-8';
                $mail->Host       = SMTP;
                $mail->SMTPAuth   = true;
                $mail->Username   = USER_EMAIL;
                $mail->Password   = PASS_EMAIL;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port 	  = PORT;

                $mail->setFrom('uploadfile@webfileupload.online','Upload File');
                $mail->addAddress($mailTo,$name);
                $mail->addReplyTo('support_upload@gmail.com', 'Information');

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body	   = $body;
                $mail->send();
            }
            catch(\Exception $e)
            {
                die($e->getMessage());
            }
        }

        public static function setCookieLogin($response,$dbUser,$dbToken)
        {
            $user = $dbUser->selectUser($response['email']);
            if(!$user)
                $user = $dbUser->createUser($response,1);
            
            $_SESSION['user'] = [
                'id' => $user['id'],
                'fullname' => $user['fullname'],
                'api_key' => $user['api_key'],
                'status' => $user['status'],
                'email' => $user['email'],
                'blocked' => $user['blocked'],
                'password' => $user['password'],
            ];
            
            $tokens = $dbToken->insertToken($user);
            self::setCookieOptions('expires',strtotime('+7 days'));
            self::setCookie($tokens);
            header('location: /');
        }

        public static function getResponseMOMO($array)
        {
            $opts = ['http' =>
                [
                    'method'  => 'POST',
                    'header'  => 'Content-Type: application/json',
                    'content' => json_encode($array)
                ]
            ];
            $context  = stream_context_create($opts);
            $data = file_get_contents('https://test-payment.momo.vn/gw_payment/transactionProcessor', false, $context);
            return $data;
        }

        public static function renderSelectOptions($data,$value)
        {
            $str = "";
            foreach($data as $key => $val)
            {
                $name = $key == $value ? 'selected' : '';
                $str .= "<option value = '$key' $name>$val</option>";
            }
            return $str;
        }
    }