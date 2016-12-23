<?php
class Users {
	public $table_name = 'adoosite_user';
	
	function __construct(){
		//database configuration
		$dbServer = 'localhost'; //Define database server host
		$dbUsername = 'daylaptr_emall'; //Define database username
		$dbPassword = 'Rs2Ybohm'; //Define database password
		$dbName = 'daylaptr_emall'; //Define database name
		
		//connect databse
		$con = mysqli_connect($dbServer,$dbUsername,$dbPassword,$dbName);
		if(mysqli_connect_errno()){
			die("Failed to connect with MySQL: ".mysqli_connect_error());
		}else{
			$this->connect = $con;
		}
	}
	
	function checkUserss($oauth_provider,$oauth_uid,$fname,$lname,$email,$gender,$locale,$picture){

			$prev_query = mysqli_query($this->connect,"SELECT * FROM ".$this->table_name." WHERE email = '".$email."' ") or die(mysql_error($this->connect));
			if(mysqli_num_rows($prev_query)>0){

				$prev = mysqli_query($this->connect,"SELECT * FROM ".$this->table_name." WHERE email = '".$email."' ANDtype = 'facebook'");
				if (mysqli_num_rows($prev)>0) {
					$update = mysqli_query($this->connect,"UPDATE $this->table_name SET type = '".$oauth_provider."', oauth_uid = '".$oauth_uid."', fullname = '".$fname.' '.$lname."', email = '".$email."',  images = '".$picture."', pass='".Hash::sha256($oauth_uid)."' WHERE type = '".$oauth_provider."' AND oauth_uid = '".$oauth_uid."'");
					$query = mysqli_query($this->connect,"SELECT * FROM $this->table_name WHERE type = '".$oauth_provider."' AND oauth_uid = '".$oauth_uid."'");
					$result = mysqli_fetch_array($query);
				}else{

					header("Location: ".url_sid("index.php?f=user&do=login&er=1")."");
				}
				
			}
			else
			{
				$insert = mysqli_query($this->connect,"INSERT INTO $this->table_name SET type = '".$oauth_provider."', oauth_uid = '".$oauth_uid."', fullname = '".$fname.' '.$lname."', email = '".$email."',   images = '".$picture."', registrationTime = NOW(), pass='".Hash::sha256($oauth_uid)."'");
				$query = mysqli_query($this->connect,"SELECT * FROM $this->table_name WHERE type = '".$oauth_provider."' AND oauth_uid = '".$oauth_uid."'");
				$result = mysqli_fetch_array($query);
			}
			
			
		
		
		return $result;
	}
}
?>