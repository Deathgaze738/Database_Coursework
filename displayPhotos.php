<?php
	include_once 'dbconnect.php';
	include_once 'functions.php';
	include_once 'verifySession.php';
	include_once 'photo_functions/verifyCollectionPermission.php';
	include_once 'classes/user.php';
	if(!ISSET($_GET['collectionid'])){
		echo "No id";
		redirect('photos.php');
	}
	if(!verifyCollectionPermission($pdo, $_SESSION['id'], $_GET['collectionid'])){
		echo "No Permission";
		redirect('photos.php');
	}
?>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</head>
<div id="mainWrapper">
	<?php
		include_once 'navbar.php';
	?>
	<div id="photosWrapper">
		<?php
			#Check if it's your photo collection
			$yours = 0;
			try{
				$stmt = $pdo->prepare("SELECT id 
										FROM photo_album
										WHERE user_owner = :user
										AND id = :id");
				$stmt->execute(['user'=>$_SESSION['id'], 'id'=>$_GET['collectionid']]);
				if($stmt->rowCount() != 0){
					$yours = 1;
				}
			}
			catch(PDOException $e){
				exit($e->getCode());
			}
			include_once 'photo_functions/photoCollectionContent.php';
			if($yours){
				echo '<form action="photo_functions/addPhoto.php" method="POST" enctype="multipart/form-data">';
					echo '<label>Description: </label><input type="text" name="description"></input>';
					echo '<label>File: </label><input type="file" name="image"></input>';
					echo '<input type="hidden" value="'.$_GET['collectionid'].'" name="collection">';
					echo '<input type="submit" value="Add Photo">';
				echo '</form>';
			}
			$user = new user($_SESSION['id'], $pdo);
			displayPhotos($pdo, $_GET['collectionid'], $user);
		?>
	</div>
</div>