<?php
	include_once '../dbconnect.php';
	include_once '../functions.php';
	include_once '../verifySession.php';
	include_once 'verifyCollectionPermission.php';
	function addAnnotation($pdo, $photo_id, $user_id){
		try{
			#verify that the user is current users friend
			$stmt = $pdo->prepare("SELECT count(*) AS valid
									FROM friendship 
									WHERE (user_1 = :id1
									AND user_2 = :id2)
									OR (user_2 = :id3 
									AND user_1 = :id4)");
			$stmt->execute(['id1'=>$user_id, 'id2'=>$_SESSION['id'], 'id3'=>$_SESSION['id'], 'id4'=>$user_id]);
			if($stmt->rowCount() == 0){
				echo "Not your friend tho";
				return;
			}
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
			if(verifyCollectionPermission($pdo, $_SESSION['id'], $row['id'])){
				$stmt = $pdo->prepare('INSERT INTO annotation (photo_id, user_id) 
										VALUES (:photo_id, :user_id)');
				$stmt->execute(['photo_id'=>$photo_id, 'user_id'=>$user_id]);
			}
			echo "ADDED ANNOTATION";
		}
		catch(PDOException $e){
			echo $e->getCode();
		}
	}
	
	$postvars = ['photoid', 'userid', 'collectionid'];
	if(verifyNull($postvars)){
		echo "WORKING";
		addAnnotation($pdo, $_POST['photoid'], $_POST['userid']);
	}
	redirect("../displayPhotos.php?collectionid=".$_POST['collectionid']);
?>