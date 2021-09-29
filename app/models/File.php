<?php

use App\Core\Functions;
use App\Core\Model;
	class File extends Model
	{

		public function select($finds)
		{
			try
			{
				$column = $finds[0];
				$after  = $finds[1]??[];
				$str = implode(',',$column);
				$query = "select $str from files as f left join users as u on f.idUser = u.id";
				if(!empty($after))
				{
					$afterWhere = implode(' ',$after);
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

		public function selectFile($id)
		{
			try
			{
				$query = "select _nameFile,_urlFile,_extension from files where _name_md5 = '$id'";
				$excute = $this->conn->query($query);
				if($excute && $excute->num_rows > 0)
				{
					$rows = $excute->fetch_assoc();
					$path = UPLOAD_URL . $rows['_extension'] . '/' . $rows['_urlFile'] . '.' . $rows['_extension'];
					if(file_exists($path))
						return ['status' => 1,'file' => $rows,'text' => 'File To Ready Download'];
				}
				throw new Exception('File Not Ready Download');
			}
			catch(\Exception $e)
			{
				return ['status' => 0,'text' => $e->getMessage()];
			}
		}

		public function insertFile($files,$idUser)
		{
			try
			{
				$nameUrl = uniqid(time()."_");
				$arr = preg_split('`\.(?=[a-zA-Z]+$)`i',$files['name'][0]);
				$nameFile = $arr[0];
				$extension = $arr[count($arr)- 1];
				$date_upload = date('Y/m/d h:i:s');
				$date_expires = date('Y/m/d h:i:s',strtotime($date_upload) + 604800);
				if(!$idUser && (int)$files['size'][0] > 209715200)
					throw new Exception('File Size Too Large');
				$storages = Functions::getSize($files['size'][0],0);
				$name_md5 = md5($nameUrl.$nameFile.$extension.$date_upload.$date_expires.$storages);
				$idUser = $idUser??"NULL";
				$query = "insert into files(_nameFile,_urlFile,_name_md5,_extension,date_upload,date_expires,storages,idUser) 
				values('$nameFile','$nameUrl','$name_md5','$extension','$date_upload','$date_expires','$storages',$idUser);";
				$path = UPLOAD_URL . $extension;
				if(!is_dir($path))
					mkdir($path,0777);
				$path = $path . "/$nameUrl.$extension";
				if(!move_uploaded_file($files['tmp_name'][0],$path))
					throw new Exception('An Unknown Error');
				if($this->conn->query($query))
					return ['status' => 1,'url' => $name_md5,'name' => $nameFile,'text' => 'File Ready To Share'];
			}
			catch(Exception $e)
			{
				return ['status' => 0,'text' => $e->getMessage()];
			}
		}

		public function selectFileUser($params)
		{
			extract($params);
			$query = "select _idFile,_nameFile,_name_md5,_extension,date_upload,date_expires,storages from files";
			if(!$check)
			{
				$id = $this->conn->real_escape_string($id);
				$query .= " where idUser = $id";
			}
			$excute = $this->conn->query($query);
			$arrData = [];
			if($excute && $excute->num_rows > 0)
			{
				while($rows = $excute->fetch_assoc())
					$arrData['data'][] = $rows;
			}
			return $arrData;
		}

		public function deleteFile($params)
		{
			try
			{
				extract($params);
				$strId = implode(",",$idFiles);
				$query = "select f._idFile,f._urlFile,f._extension from files as f";
				if(!$check)
					$query .= " where f._idFile in ($strId) and f.idUser = $idUser";
				else
					$query .= " join users AS u ON f._idFile IN ($strId) AND f.idUser = u.id AND (f.idUser = $idUser or u.permission < $permission)
					UNION
					SELECT f._idFile,f._urlFile,f._extension FROM files AS f WHERE f._idFile IN ($strId) AND f.idUser IS NULL;";
				$execute = $this->conn->query($query);
				if(!$execute || $execute->num_rows == 0)
					throw new Exception();
				while($rows = $execute->fetch_assoc())
				{
					$path = UPLOAD_URL . $rows['_extension'] . '/' . $rows['_urlFile'] . "." . $rows['_extension'];
					if(!file_exists($path))
						throw new Exception();
					unlink($path);
					$query = "delete from files where _idFile = ".$rows['_idFile'];
					if(!$this->conn->query($query) || $this->conn->affected_rows == 0)
						throw new Exception();
				}
				return 'Delete file success';
			}
			catch(Exception $e)
			{
				throw new Exception('Delete file failed');
			}
		}
	}