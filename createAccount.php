<?php
	include 'dbconnect.php';
	include 'functions.php';
	
	function validEmail(){
		$email = $_POST['emailAddress'];
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			exit("Please enter a valid email address.");
		}
	}
	
	function insertNewUser($pdo){
		$firstName = $_POST['firstName'];
		$lastName = $_POST['firstName'];
		$emailAddress = $_POST['emailAddress'];
		$password_hash = PASSWORD_HASH($_POST['password'], PASSWORD_DEFAULT);
		
		$stmt = $pdo->prepare('INSERT INTO user (first_name, last_name, email_address, password)
								VALUES (:firstName, :lastName, :emailAddress, :password_hash)');
								
		try{
			$stmt->execute(['firstName' => $firstName, 
							'lastName'=>$lastName,
							'emailAddress'=>$emailAddress,
							'password_hash'=>$password_hash]);
		}
		catch(PDOException $e){
			#code 23000 is duplicate ID violating the unique email address constraint
			if($e->getCode() == 23000){
				exit('Email Address is already in use, please use a different one.');
			}
			else{
				exit('Unhandled PDO Error with code '.$e->getCode());
			}
		}
	}
	
	if(!empty($_POST)){
		$vars = array('firstName', 'lastName', 'emailAddress', 'password');
		if(!verifyNull($vars)){
			exit("NEIN");
		}
		validEmail();
		
		insertNewUser($pdo);
		echo "User Added Successfully";
	}
?>