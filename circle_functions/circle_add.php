<?php
	include_once '../dbconnect.php';
	include_once '../verifySession.php';
	include_once 'verifyCirclePermission.php';
	include_once '../functions.php';
	
	function leaveCircle($pdo, $circle, $user){
		$stmt = $pdo->prepare("INSERT INTO circle_membership
								(circle_id, user_id) 
								VALUES (:circle, :user)");
		$stmt->execute(['user'=>$user, 'circle'=>$circle]);
	}
	
	$vars = ['circle_id', 'member'];
	if(!verifyNull($vars)){
		exit("No circle ID");
	}
	if(!verifyCirclePermission($_SESSION['id'], $_POST['circle_id'], $pdo)){
		exit("Nice try, you don't have permission for that circle.");
	}
	leaveCircle($pdo, $_POST['circle_id'], $_POST['member']);
	redirect("../circles.php?circleid=".$_POST['circle_id']);
?>