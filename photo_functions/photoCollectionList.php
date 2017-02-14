<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/classes/photoCollection.php';
	include_once $_SERVER['DOCUMENT_ROOT'].'/classes/photo.php';
	include_once $_SERVER['DOCUMENT_ROOT'].'/classes/photo_comment.php';
	include_once $_SERVER['DOCUMENT_ROOT'].'/classes/photo_annotation.php';
	include_once 'verifySession.php';
	include_once 'verifyCollectionPermission.php';
	
	function displayPhotoCollections($pdo, $id){
		echo '<div class="photo_collection_wrapper">';
		if(isset($_GET['userid'])){
			#If user ID is set, we will display other peoples photocollections
			try{
				$stmt = $pdo->prepare("SELECT first_name 
										FROM user
										WHERE id = :id");
				$stmt->execute(['id'=>$_GET['userid']]);
				$name = $stmt->fetch();
				
				$stmt = $pdo->prepare("SELECT id, name, description, (SELECT count(*) FROM photo WHERE album_id = p.id) AS noOfPhotos
										FROM photo_album AS p
										WHERE user_owner = :id
										ORDER BY id DESC");
				$stmt->execute(['id'=>$_GET['userid']]);
				if($stmt->rowCount() == 0){
					echo "No photo collections found.";
					return;
				}
			}
			catch(PDOException $e){
				echo $e->getCode();
			}
			echo '<h1>'.$name['first_name'].'\'s Photo Collections</h1>';
			foreach($stmt as $row){
				if(verifyCollectionPermission($pdo, $_GET['userid'], $row['id'])){
					echo '<a href="displayPhotos.php?collectionid='.$row['id'].'">';
						echo '<div class="photo_collection">';
							echo $row['name'];
							echo '<br>';
							echo 'Contains '.$row['noOfPhotos'].' photos.';
						echo '</div>';
					echo '</a>';
				}
			}
		}
		else{
			#Display our photocollections
			echo '<form action="photo_functions/addPhotoCollection.php" method="POST">';
				echo '<label>Name: </label><input type="text" name="name"></input>';
				echo '<label>Description: </label><input type="text" name="desc"></input>';
				echo '<label>Privacy: </label><select name="privacy">';
					echo '<option value="0">Public</option>';
					echo '<option value="1">Friends of Friends</option>';
					echo '<option value="2">Friends</option>';
				echo '</select>';
				echo '<input type="submit" value="Add Collection">';
			echo '</form>';
			try{
				$stmt = $pdo->prepare("SELECT id, name, description, (SELECT count(*) FROM photo WHERE album_id = p.id) AS noOfPhotos
										FROM photo_album AS p
										WHERE user_owner = :id");
				$stmt->execute(['id'=>$id]);
				
				if($stmt->rowCount() == 0){
					echo "No photo collections found.";
					return;
				}
			}
			catch(PDOException $e){
				echo $e->getCode();
			}
			foreach($stmt as $row){
				echo '<a href="displayPhotos.php?collectionid='.$row['id'].'">';
					echo '<div class="photo_collection">';
						echo $row['name'];
						echo '<br>';
						echo 'Contains '.$row['noOfPhotos'].' photos.';
					echo '</div>';
				echo '</a>';
				echo '<form action="photo_functions/deletePhotoCollection.php" method="POST">';
					echo '<input type="hidden" name="collectionid" value="'.$row['id'].'"></input>';
					echo '<input type="submit" value="Delete">';
				echo '</form>';
			}
		}
		echo '</div>';
	}
?>