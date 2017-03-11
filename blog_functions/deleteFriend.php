<?php
	function deleteFriend($pdo, $friend_id, $user_id){
		$stmt = $pdo->prepare("DELETE FROM friendship 
								WHERE (user_1=:friend_id
								AND user_2 = :user_id)
								OR (user_1=:user_id2
								AND user_2 = :friend_id2)");
		$stmt->execute(['friend_id'=>$friend_id,
						'user_id'=>$user_id,
						'friend_id2'=>$friend_id,
						'user_id2'=>$user_id]);
	}
	
	include '../dbconnect.php';
	include '../functions.php';
	include '../verifySession.php';
	$vars = ['friendid'];
	if(!verifyNull($vars)){
		exit("NULL");
	}
	deleteFriend($pdo, $_POST['friendid'], $_SESSION['id']);
	redirect("http://localhost/blog.php");
?>