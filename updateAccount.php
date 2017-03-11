 <?php
	function updateAccount($pdo, $user_id, $first_name, $last_name, $country, $age, $privacy){
		try{
			$stmt = $pdo->prepare("UPDATE user 
									SET first_name=:first_name,
									last_name = :last_name,
									country = :country,
									age = :age,
									privacy_setting_fk = :privacy
									WHERE id = :id");
			$stmt->execute(['first_name'=>$first_name, 
							'last_name'=>$last_name,
							'country'=>$country,
							'age'=>$age,
							'privacy'=>$privacy,
							'id'=>$user_id]);
		}
		catch(PDOException $e){
			exit($e->getCode());
		}
	}
	
	include 'dbconnect.php';
	include 'functions.php';
	include 'verifySession.php';
	$vars = ['firstName', 'lastName', 'country', 'age', 'privacy'];
	if(!verifyNull($vars)){
		exit("NULL");
	}
	updateAccount($pdo, $_SESSION['id'], $_POST['firstName'], $_POST['lastName'], $_POST['country'], $_POST['age'], $_POST['privacy']);
	redirect("http://localhost/editProfile.php");
?>