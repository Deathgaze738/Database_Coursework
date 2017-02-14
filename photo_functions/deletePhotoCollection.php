<?php
	function deleteCollection($pdo, $collectionid, $user_id){
		$stmt = $pdo->prepare("SELECT user_owner FROM photo_album WHERE id = :id");
		$stmt->execute(['id'=>$collectionid]);
		if($stmt->rowCount() == 0){
			#NOT YOUR ALBUM. CHEEKY CHEEKY.
			redirect("../photos.php");
		}
		$row = $stmt->fetch();
		if($row['user_owner'] == $user_id){
			try{
				$stmt = $pdo->prepare("DELETE FROM photo_album WHERE id=:id");
				$stmt->execute(['id'=>$collectionid]);
			}
			catch(PDOException $e){
				exit($e->getCode());
			}
		}
	}
	
	include '../dbconnect.php';
	include '../functions.php';
	include '../verifySession.php';
	$vars = ['collectionid'];
	if(!verifyNull($vars)){
		exit("NULL");
	}
	deleteCollection($pdo, $_POST['collectionid'], $_SESSION['id']);
	redirect("../photos.php");
?>
