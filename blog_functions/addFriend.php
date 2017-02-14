<?php
	include '../dbconnect.php';
	include_once '../verifySession.php';
	include '../functions.php';
	
	$postvars = ['friendid'];
	if(verifyNull($postvars)){
		try{
			$stmt = $pdo->prepare("INSERT INTO friendship 
									(user_1, user_2) VALUES
									(:user, :user2)");
			$stmt->execute(['user'=>$_SESSION['id'], 'user2'=>$_POST['friendid']]);
		}
		catch(PDOException $e){
			echo $e->getCode();
		}
		redirect("../blog.php?blogid=".$_POST['friendid']);
	}
?>