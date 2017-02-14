<?php
	include_once '../dbconnect.php';
	include_once '../functions.php';
	include_once '../verifySession.php';
	include_once 'verifyCollectionPermission.php';
	function addComment($pdo, $photo_id, $content, $commenter){
		try{
			$stmt = $pdo->prepare("SELECT pa.id
									FROM photo AS p
									RIGHT JOIN photo_album AS pa
									ON p.album_id = pa.id
									WHERE p.id = :id");
			$stmt->execute(['id'=>$photo_id]);
			if($stmt->rowCount() == 0){
				echo "Cheeky Cheeky";
				return;
			}
			$row = $stmt->fetch();
			if(verifyCollectionPermission($pdo, $commenter, $row['id'])){
				$stmt = $pdo->prepare('INSERT INTO photo_comment (photo_id, content, commenter) 
										VALUES (:photo_id, :content, :commenter)');
				$stmt->execute(['photo_id'=>$photo_id, 'content'=>$content, 'commenter'=>$commenter]);
			}
			echo "ADDED ANNOTATION";
		}
		catch(PDOException $e){
			echo $e->getCode();
		}
	}
	
	$postvars = ['photoid', 'collectionid', 'content'];
	if(verifyNull($postvars)){
		echo "WORKING";
		addComment($pdo, $_POST['photoid'], $_POST['content'], $_SESSION['id']);
	}
	redirect("../displayPhotos.php?collectionid=".$_POST['collectionid']);
?>