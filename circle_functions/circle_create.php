<?php
	include_once '../dbconnect.php';
	include_once '../verifySession.php';
	include_once '../functions.php';
	
	function createCircle($pdo, $name, $desc, $creator){
		$pdo->beginTransaction();
		try{
			$stmt = $pdo->prepare("INSERT INTO circle
									(creator, name, description) 
									VALUES (:creator, :name, :desc);");
			$stmt->execute(['creator'=>$creator, 'name'=>$name, 'desc'=>$desc]);
			$id = $pdo->lastInsertId();
			$stmt = $pdo->prepare("INSERT INTO circle_membership
									(circle_id, user_id) 
									VALUES (:circle, :user)");
			$stmt->execute(['user'=>$creator, 'circle'=>$id]);
			$pdo->commit();
		}
		catch(PDOException $e){
			$pdo->rollBack();
			echo $e->getCode();
		}
	}
	
	$vars = ['name', 'desc'];
	if(!verifyNull($vars)){
		exit("Some vars are null.");
	}
	createCircle($pdo, $_POST['name'], $_POST['desc'], $_SESSION['id']);
	redirect("../circles.php");
?>