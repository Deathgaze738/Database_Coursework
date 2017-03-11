<?php
	include_once 'dbconnect.php';
	include_once 'functions.php';
	include_once 'verifySession.php';
?>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</head>
<div id="mainWrapper">
<?php
	include 'navBar.php';
	try{
		$stmt = $pdo->prepare("SELECT first_name, last_name, country, age, privacy_setting_fk
								FROM user
								WHERE id = :id");
		$stmt->execute(['id'=>$_SESSION['id']]);
		$row  = $stmt->fetch();
	}
	catch(PDOException $e){
		exit($e->getCode());
	}
	echo '<form action="updateAccount.php" method="POST">';
		echo '<label>First Name: </label><input name="firstName" type="text" value="'.$row['first_name'].'"><br>';
		echo '<label>Last Name: </label><input name="lastName" type="text" value="'.$row['last_name'].'"><br>';
		echo '<label>Country: </label><input name="country" type="text" value="'.$row['country'].'"><br>';
		echo '<label>Age: </label><input name="age" type="number" value="'.$row['age'].'"><br>';
		echo '<label>Blog Privacy: </label><select name="privacy">';
			echo '<option value="0" ';if($row['privacy_setting_fk'] == 0){ echo "selected";} echo '>Public</option>';
			echo '<option value="1" ';if($row['privacy_setting_fk'] == 1){ echo "selected";} echo '>Friends of Friends</option>';
			echo '<option value="2" ';if($row['privacy_setting_fk'] == 2){ echo "selected";} echo '>Friends</option>';
		echo '</select>';
		echo '<input type="submit" value="Update Profile"><br>';
	echo '</form>';
?>
</div>