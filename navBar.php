<?php
	include_once 'dbconnect.php';
	$stmt = $pdo->prepare('SELECT first_name, last_name 
							FROM user 
							WHERE id = :id');
	$stmt->execute(['id'=>$_SESSION['id']]);
	if($stmt->rowCount() == 0){
		exit('Server Error');
	}
	$row  = $stmt->fetch();
	echo '<nav>';
		echo '<h2> Logged in as: '.$row['first_name'].' '.$row['last_name'].'</h2>';
		echo '<ul>';
			echo '<li>';
				echo '<a href="blog.php"> My Blog </a>';
			echo '</li>';
			echo '<li>';
				echo '<a href="photos.php"> My Photos </a>';
			echo '</li>';
			echo '<li>';
				echo '<a href="circles.php"> My Circles </a>';
			echo '</li>';
			echo '<li>';
				echo '<a href="editProfile.php"> Edit Profile </a>';
			echo '</li>';
		echo '</ul>';
	echo '</nav>';
?>
