<?php
	function editPost($pdo, $post_id, $user_id, $content){
		echo $post_id;
		$stmt = $pdo->prepare("SELECT blog_owner_id FROM blog_post WHERE id = :post_id");
		$stmt->execute(['post_id'=>$post_id]);
		if($stmt->rowCount() == 0){
			return;
		}
		$row = $stmt->fetch();
		if($row['blog_owner_id'] == $user_id){
			$stmt = $pdo->prepare("UPDATE blog_post 
									SET content=:content
									WHERE id = :id");
			$stmt->execute(['content'=>$content, 'id'=>$post_id]);
		}
	}
	
	include '../dbconnect.php';
	include '../functions.php';
	include '../verifySession.php';
	$vars = ['update_id'];
	if(!verifyNull($vars)){
		exit("NULL");
	}
	editPost($pdo, $_POST['update_id'], $_SESSION['id'], $_POST['content']);
	redirect("http://localhost/blog.php");
?>