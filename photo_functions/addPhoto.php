<?php
	function addPhoto($pdo, $album_id, $description, $path){
		try{
			$stmt = $pdo->prepare("SELECT user_owner FROM photo_album WHERE id = :id");
			$stmt->execute(['id'=>$album_id]);
			if($stmt->rowCount() == 0){
				#NOT YOUR ALBUM. CHEEKY CHEEKY.
				redirect("../photos.php");
			}
			$row = $stmt->fetch();
			if($row['user_owner'] == $_SESSION['id']){
				$stmt = $pdo->prepare('INSERT INTO photo (album_id, description, path) 
										VALUES (:album_id, :description, :path)');
				$stmt->execute(['album_id'=>$album_id, 'description'=>$description, 'path'=>$path]);
				$file_tmp = $_FILES['image']['tmp_name'];
				move_uploaded_file($file_tmp, "../".$path);
			}
		}
		catch(PDOException $e){
			echo $e->getCode();
		}
	}
	
	function getPath($pdo){
		$path = "uploads/";
		return $path.microtime().$_FILES['image']['name'];
	}
	
	function checkIfValidFile(){
		$ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
		$validTypes = array('jpg', 'bmp', 'png', 'gif', 'jpeg');
		if(in_array($ext, $validTypes) && $_FILES['image']['size'] < 5000000){
			return true;
		}
		return false;
	}
	
	include '../dbconnect.php';
	include '../functions.php';
	include '../verifySession.php';
	$postvars = ['description', 'collection'];
	if(verifyNull($postvars) && $_FILES['image']['name']){
		echo "WORKING";
		if(checkIfValidFile()){
			addPhoto($pdo, $_POST['collection'], $_POST['description'], getPath($pdo));
		}
	}
	redirect("../displayPhotos.php?collectionid=".$_POST['collection']);
?>