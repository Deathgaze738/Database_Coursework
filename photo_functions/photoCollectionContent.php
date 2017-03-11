<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/classes/photoCollection.php';
	include_once $_SERVER['DOCUMENT_ROOT'].'/classes/user.php';
	function displayPhotos($pdo, $collection, $user){
		$photoCollectionContent = new photoCollection($pdo, $collection);
		$photos = $photoCollectionContent->getPhotos();
		$friends = $user->getFriends();
		$owner = false;
		if($user->getId() == $photoCollectionContent->getOwner()){
			$owner = true;
		}
		foreach($photos as $photo){
			$annotationsIds = array();
			echo '<div class="photoWrapper">';
					echo '<div class="photo_main">';
						if($owner){
							echo '<form action="photo_functions/deletePhoto.php" method="POST">';
								echo '<input type="hidden" name="photoid" value="'.$photo->getId().'"></input>';
								echo '<input type="hidden" name="collectionid" value="'.$collection.'">';
								echo '<input type="submit" value="Delete">';
							echo '</form>';
						}
						echo '<p>'.$photo->getDescription().'</p>';
						echo '<img src="'.$photo->getPath().'"/>';
					echo '</div>';
					echo '<div class="photo_annotations">';
						$annotations = $photo->getAnnotations();
						echo '<p>Annotations</p>';
						foreach($annotations as $annotation){
							echo '<a href="blog.php?blogid='.$annotation->getUser().'">';
							echo $annotation->getUsername();
							array_push($annotationsIds, $annotation->getUser());
							echo '</a>  ';
						}
						echo '<form action="photo_functions/addAnnotation.php" method="POST">';
							echo '<input type="hidden" name="photoid" value="'.$photo->getId().'">';
							echo '<input type="hidden" name="collectionid" value="'.$collection.'">';
							echo '<select name="userid">';
								foreach($friends as $friend){
									if(!in_array($friend['id'], $annotationsIds)){
										echo '<option value="'.$friend['id'].'">'.$friend['first_name'].' '.$friend['last_name'].'</option>';
									}
								}
							echo '</select>';
							echo '<input type="submit" value="Add Annotation">';
						echo '</form>';
					echo '</div>';
					echo '<div class="photo_comments">';
						echo '<p> Comments </p>';
						$comments = $photo->getComments();
						echo '<p>';
						echo '<form action="photo_functions/addComment.php" method="POST">';
							echo '<input type="hidden" name="photoid" value="'.$photo->getId().'">';
							echo '<input type="hidden" name="collectionid" value="'.$collection.'">';
							echo '<input type="text" name="content">';
							echo '<input type="submit" value="Add Comment">';
						echo '</form>';
						foreach($comments as $comment){
							echo '<a href="blog.php?blogid='.$comment->getUser().'">';
							echo $comment->getUsername();
							echo '</a>  ';
							echo '<p>'.$comment->getContent().'</p>';
							if($owner){
								echo '<form action="photo_functions/deleteComment.php" method="POST">';
								echo '<input type="hidden" name="photoid" value="'.$photo->getId().'">';
								echo '<input type="hidden" name="commentid" value="'.$comment->getId().'">';
								echo '<input type="hidden" name="collectionid" value="'.$collection.'">';
								echo '<input type="submit" value="Delete Comment">';
							}
						echo '</form>';
						}
						echo '</p>';
					echo '</div>';
			echo '</div>';
		}
	}
?>