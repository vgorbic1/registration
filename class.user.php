<?php
/**
 * User Class
 * @author Vlad Gorbich
 */
require_once 'dbconfig.php';

class USER {	
	private $conn;
	
	public function __construct() {
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function runQuery($sql) {
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	
	public function lasdID() {
		$stmt = $this->conn->lastInsertId();
		return $stmt;
	}
	
	public function register($title,$fname,$mname,$lname,$email,$phone,$webpage,$organization,$department,$address,$city,$state,$zip,$country,$accompany,$presentation,$student,$paper,$code,$time) {
		try {							
			$stmt = $this->conn->prepare("INSERT INTO tbl_users(userTitle,userFname,userMname,userLname,userEmail,userPhone,userWebpage,userOrganization,userDepartment,userAddress,userCity,userState,userZip,userCountry,userAccompany,userPresentation,userStudent,userPaper,tokenCode,userTimeRegistered)VALUES(:user_title,:user_fname,:user_mname,:user_lname,:user_email,:user_phone,:user_webpage,:user_organization,:user_department,:user_address,:user_city,:user_state,:user_zip,:user_country,:user_accompany,:user_presentation,:user_student,:user_paper,:activation_code,:time_registered) ");
			$stmt->bindparam(":user_title", $title);
			$stmt->bindparam(":user_fname", $fname);
			$stmt->bindparam(":user_mname", $mname);
			$stmt->bindparam(":user_lname", $lname);
			$stmt->bindparam(":user_email", $email);
			$stmt->bindparam(":user_phone", $phone);
			$stmt->bindparam(":user_webpage", $webpage);
			$stmt->bindparam(":user_organization", $organization);
			$stmt->bindparam(":user_department", $department);
			$stmt->bindparam(":user_address", $address);
			$stmt->bindparam(":user_city", $city);
			$stmt->bindparam(":user_state", $state);
			$stmt->bindparam(":user_zip", $zip);
			$stmt->bindparam(":user_country", $country);
			$stmt->bindparam(":user_accompany", $accompany);
			$stmt->bindparam(":user_presentation", $presentation);
			$stmt->bindparam(":user_student", $student);
			$stmt->bindparam(":user_paper", $paper);
			$stmt->bindparam(":activation_code", $code);
			$stmt->bindparam(":time_registered", $time);
			$stmt->execute();	
			return $stmt;
		} catch(PDOException $ex) {
			echo $ex->getMessage();
		}
	}
	
	public function redirect($url) {
		header("Location: $url");
	}
	
	function send_mail($email, $message, $subject) {
		
		$admin_email = "admin@site.com"; 
		$headers = "From: " . $admin_email . "\r\n";
		// add other useful headers $headers .= "";		
		mail($email, $subject, $message, $headers);

	}	
}
