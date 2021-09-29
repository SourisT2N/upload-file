<?php
    namespace App\Core;

    class Validation
    {
        private static $messageError = [];
        private static $status  = 0;

        public static function init(array $arrParams)
        {
            $pass = '';
            foreach($arrParams as $key => $val)
            {
                $check = $key == 'new-password' || $key == 'current-password'?'password':$key;
                switch($check)
                {
                    case 'fullname':
                        (!self::checkName($val)?self::$messageError[] = 'Name contains only a-z, from 3 to 50 characters':self::$status++);
                        break;
                    case 'email':
                        (!self::checkEmail($val)?self::$messageError[] = 'Email contains only (a-z), (0-9), (._)':self::$status++);
                        break;
                    case 'password':
                        (!self::checkPass($val)?self::$messageError[] = $key.' contains only (a-z), (0-9), (_), from 6-20 characters':self::$status++);
                        $pass = $val;
                        break;
                    case 'code':
                        (!self::checkCode($val)?self::$messageError[] = 'Code contains only (0-9) and 6 characters':self::$status++);
                        break;
                    case 'status':
                        ($val != 0 && $val != 1?self::$messageError[] = 'Status error':self::$status++);
                        break;
                    case 'blocked':
                        ($val != 0 && $val != 1?self::$messageError[] = 'Status error':self::$status++);
                        break;
                    case 'permission':
                        ($val != 0 && $val != 1 && $val != 2?self::$messageError[] = 'Status error':self::$status++);
                        break;
                    case 're-password':
                        ($pass !== $val?self::$messageError[] = 'Passwords do not match':self::$status++);
                        break;
                    case 'id':
                        (!self::checkIdType($val)?self::$messageError[] = $key . ' contains only (0-9) and (a-z)':self::$status++);
                        break;
                    case  'type':
                        (!self::checkIdType($val)?self::$messageError[] = $key . ' contains only (0-9) and (a-z)':self::$status++);
                        break;
                    case 'transaction_id':
                        (!self::checkAmountIdTrans($val)?self::$messageError[] = $key . ' contains only (0-9)':self::$status++);
                        break;
                    case 'amount':
                        (!self::checkAmountIdTrans($val)?self::$messageError[] = $key . ' contains only (0-9)':self::$status++);
                        break;
                    case 'date':
                        (!self::checkDate($val)?self::$messageError[] = 'Date format not correct':self::$status++);
                        break;                        
                    default:
                        self::$messageError[] = 'Not Found Input';
                }
            }
        }

        private static function checkName($str)
        {
            $regex = '`^[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂẾưăạảấầẩẫậắằẳẵặẹẻẽềềểếỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s|]{3,50}$`i';
            return preg_match($regex,$str);
        }

        private static function checkEmail($str)
        {
            return filter_var($str,FILTER_VALIDATE_EMAIL);
        }

        private static function checkPass($str)
        {
            $regex = '`^\w{6,20}$`i';
            return preg_match($regex,$str);
        }

        private static function checkCode($str)
        {
            $regex = '`^\d{6}$`i';
            return preg_match($regex,$str);
        }

        private static function checkIdType($str)
        {
            $regex = '`^\w+$`i';
            return preg_match($regex,$str);
        }

        private static function checkAmountIdTrans($str)
        {
            $regex = '`^\d+$`';
            return preg_match($regex,$str);
        }

        private static function checkDate($str)
        {
            return \DateTime::createFromFormat('d/m/Y H:i a',$str) !== false || new \DateTime($str) !== false;
        }

        public static function getError()
        {
            return self::$messageError;
        }

        public static function getStatus()
        {
            return self::$status;
        }
    }