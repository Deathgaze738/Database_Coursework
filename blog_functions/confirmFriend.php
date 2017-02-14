<?php
	include '../dbconnect.php';
	include '../functions.php';
	include '../verifySession.php';
	$postvars = ['friendid', 'accept'];
	if(verifyNull($postvars)){
		if($_POST['accept'] == 0){
			$stmt = $pdo->prepare("DELETE FROM friendship 
									WHERE user_1=:friendid
									AND user_2 = :user");
			$stmt->execute(['friendid'=>$_POST['friendid'], 'user'=>$_SESSION['id']]);
			echo "Rejected";
		}
		elseif($_POST['accept'] == 1){
			$stmt = $pdo->prepare("UPDATE friendship 
									SET status = 1
									WHERE user_1 = :user1 
									AND user_2 = :user2");
			$stmt->execute(['user1'=>$_POST['friendid'], 'user2'=>$_SESSION['id']]);
			echo "Accepted";
		}
	}
	redirect("../blog.php");
?>