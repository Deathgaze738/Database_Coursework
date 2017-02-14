<?php
	function addCollection($pdo, $name, $description, $owner, $privacy){
		try{
			$stmt = $pdo->prepare('INSERT INTO photo_album (user_owner, name, description, privacy_setting) 
									VALUES (:owner, :name, :desc, :privacy)');
			$stmt->execute(['owner'=>$owner, 'name'=>$name, 'desc'=>$description, 'privacy'=>$privacy]);
		}
		catch(PDOException $e){
			echo $e->getCode();
		}
	}
	
	include '../dbconnect.php';
	include '../functions.php';
	include '../verifySession.php';
	$postvars = ['name', 'privacy'];
	if(verifyNull($postvars)){
		echo "WORKING";
		if(isset($_POST['desc'])){
			addCollection($pdo, $_POST['name'], $_POST['desc'], $_SESSION['id'], $_POST['privacy']);
		}
	}
	redirect("../photos.php");
?>