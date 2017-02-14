<?php
	include_once '../dbconnect.php';
	include_once '../verifySession.php';
	include_once 'verifyCirclePermission.php';
	include_once '../functions.php';
	
	function leaveCircle($pdo, $circle, $user){
		$stmt = $pdo->prepare("DELETE FROM circle_membership
									WHERE circle_id =:circle
									AND user_id = :user");
		$stmt->execute(['user'=>$user, 'circle'=>$circle]);
	}
	
	$vars = ['circle_id'];
	if(!verifyNull($vars)){
		exit("No circle ID");
	}
	if(!verifyCirclePermission($_SESSION['id'], $_POST['circle_id'], $pdo)){
		exit("Nice try, you don't have permission for that circle.");
	}
	leaveCircle($pdo, $_POST['circle_id'], $_SESSION['id']);
	redirect("../circles.php");
?>