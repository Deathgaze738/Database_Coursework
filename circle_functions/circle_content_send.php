<?php
	include_once '../dbconnect.php';
	include_once '../verifySession.php';
	include_once 'verifyCirclePermission.php';
	include_once '../functions.php';
	
	function sendMessage($pdo, $content, $circle, $user){
		$stmt = $pdo->prepare("INSERT INTO circle_message
								(sender_user_id, receiver_circle_id, content)
								VALUES (:user, :circle, :content)");
		$stmt->execute(['user'=>$user, 'circle'=>$circle, 'content'=>$content]);
	}
	
	$vars = ['circle_id'];
	if(!verifyNull($vars)){
		exit("No circle ID");
	}
	if(!verifyCirclePermission($_SESSION['id'], $_POST['circle_id'], $pdo)){
		exit("Nice try, you don't have permission for that circle.");
	}
	sendMessage($pdo, $_POST['content'], $_POST['circle_id'], $_SESSION['id']);
?>