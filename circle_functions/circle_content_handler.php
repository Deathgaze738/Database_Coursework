<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/classes/circle_content.php';
	include_once '../dbconnect.php';
	include_once '../verifySession.php';
	include_once 'verifyCirclePermission.php';
	include_once '../functions.php';
	include_once $_SERVER['DOCUMENT_ROOT'].'/classes/user.php';
	$var = ['circle_id'];
	verifyNull($var);
	if(!verifyCirclePermission($_SESSION['id'], $_POST['circle_id'], $pdo)){
		exit("You're not apart of that circle. Nice try, boiyo");
	}
	$circle_content = new circle_content($pdo, $_POST['circle_id']);
	$members = $circle_content->getMembers();
	echo '<div class="circle_members">';
		echo '<p>';
			foreach($members as $member){
				echo '<a href="blog.php?blogid='.$member['id'].'">'.$member['first_name'].' '.$member['last_name'].'</a>     ';
			}
		echo '</p>';
	echo '</div>';
	echo '<div class="circle_messages">';
		$messages = $circle_content->getMessages();
		foreach($messages as $message){
			echo '<div class="circle_message">';
				echo '<p>'.'<a href="blog.php?blogid='.$message['sender_user_id'].'">'.$message['first_name'].' '.$message['last_name'].'   '.$message['timestamp'].'</a></p>';
				echo '<p> '.$message['content'].' </p>';
			echo '</div>';
		}
	echo '</div>';
?>