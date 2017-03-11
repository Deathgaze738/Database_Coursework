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
		$lastName = $_POST['lastName'];
		$emailAddress = $_POST['emailAddress'];
		$age = $_POST['age'];
		$country = $_POST['country'];
		$password_hash = PASSWORD_HASH($_POST['password'], PASSWORD_DEFAULT);
		
		$stmt = $pdo->prepare('INSERT INTO user (first_name, last_name, email_address, password, country, age)
								VALUES (:firstName, :lastName, :emailAddress, :password_hash, :country, :age)');
								
		try{
			$stmt->execute(['firstName' => $firstName, 
							'lastName'=>$lastName,
							'emailAddress'=>$emailAddress,
							'password_hash'=>$password_hash,
							'country'=>$country,
							'age'=>$age]);
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
		$vars = array('firstName', 'lastName', 'emailAddress', 'password', 'country', 'age');
		if(!verifyNull($vars)){
			exit("NEIN");
		}
		validEmail();
		if(!is_numeric($_POST['age'])){
			exit("NEIN NUMBER");
		}
		
		if(strcmp($_POST['password'], $_POST['password_conf'])){
			exit("NEIN SAME PASS");
		}
		
		insertNewUser($pdo);
		echo "User Added Successfully";
		redirect("index.php");
	}
?>