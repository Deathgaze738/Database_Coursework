<?php
	function printFriendsInfo($id, $pdo, $user, $pending){
		$friends = $user->getFriends();
		$recommendations = $user->getRecommendations($pdo);
		$circles = $user->getCircles();
		$friendRequests = $user->getPending();
		echo "<div style='float: right;'>";
			echo "<input id='recs' type='text' placeholder='Search for Friends'>";
			echo "<div id='searchBox'>";
			
			echo "</div>";
			echo "<div id='friends'>";
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
			echo "<h1>FRIENDS LIST </h1>";
			foreach($friends as $friend){
				echo "<a href='blog.php?blogid=".$friend['id']."'>".$friend['first_name']." ".$friend['last_name']."</a>";
				echo '<form action="blog_functions/deleteFriend.php" method="POST">';
					echo '<input name="friendid" value="'.$friend['id'].'" type="hidden">';
					echo '<input name="submit" value="Delete" type="submit">';
				echo '</form>';
				echo "<br>";
			}
			echo "<h1>Recommendations (People near you)</h1>";
			foreach($recommendations as $friend){
				echo "<a href='blog.php?blogid=".$friend['id']."'>".$friend['first_name']." ".$friend['last_name']."</a>";
				echo "<br>";
			}
			echo "<h1>CIRCLES LIST </h1>";
			foreach($circles as $circle){
				echo "<a href='circles.php?circleid=".$circle->getId()."'>".$circle->getName().", ".$circle->getDescription()."</a>";
				echo "<br>";
			}
			echo "</div>";
		echo "</div>";
	}
?>