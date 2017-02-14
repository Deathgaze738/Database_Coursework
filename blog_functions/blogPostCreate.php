<?php
	function createPost($pdo, $user_id, $content){
		try{
			$stmt = $pdo->prepare('INSERT INTO blog_post (blog_owner_id, content) 
									VALUES (:blog_owner_id, :content)');
			$stmt->execute(['blog_owner_id'=>$user_id, 'content'=>$content]);
		}
		catch(PDOException $e){
			echo $e->getCode();
		}
	}
	
	include '../dbconnect.php';
	include '../functions.php';
	include '../verifySession.php';
	createPost($pdo, $_SESSION['id'], $_POST['content']);
	redirect("http://localhost/blog.php");
?>