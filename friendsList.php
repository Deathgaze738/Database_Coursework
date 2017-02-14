<?php
	function printFriendsInfo($id, $pdo, $user, $pending){
		$friends = $user->getFriends();
		$fof = $user->getFriendsOfFriends();
		$circles = $user->getCircles();
		$friendRequests = $user->getPending();
		echo "<div style='float: right;'>";
		if(count($friendRequests) != 0 && $pending == 1){
			echo "<h1> Pending Friend Requests </h1>";
			foreach($friendRequests as $request){
				echo "<div>";
					echo "<p> ".$request['first_name']." ".$request['last_name']." wants to be your friend!</p>";
					echo '<form action="blog_functions/confirmFriend.php" method="POST">';
						echo '<input name="friendid" value="'.$request['id'].'" type="hidden">';
						echo '<input name="accept" value="1" type="hidden">';
						echo '<input name="submit" value="Accept" type="submit">';
					echo '</form>';
					echo '<form action="blog_functions/confirmFriend.php" method="POST">';
						echo '<input name="friendid" value="'.$request['id'].'" type="hidden">';
						echo '<input name="accept" value="0" type="hidden">';
						echo '<input name="submit" value="Reject" type="submit">';
					echo '</form>';
			}
		}
		echo "<h1>".$user->getFirstName()."'s FRIENDS LIST </h1>";
		foreach($friends as $friend){
			echo "<a href='blog.php?blogid=".$friend['id']."'>".$friend['first_name']." ".$friend['last_name']."</a>";
			echo "<br>";
		}
		echo "<h1>".$user->getFirstName()."'s FRIENDS OF FRIENDS LIST </h1>";
		foreach($fof as $friend){
			echo "<a href='blog.php?blogid=".$friend['id']."'>".$friend['first_name']." ".$friend['last_name']."</a>";
			echo "<br>";
		}
		echo "<h1>".$user->getFirstName()."'s CIRCLES LIST </h1>";
		foreach($circles as $circle){
			echo "<a href='circles.php?circleid=".$circle->getId()."'>".$circle->getName().", ".$circle->getDescription()."</a>";
			echo "<br>";
		}
		echo "</div>";
	}
?>