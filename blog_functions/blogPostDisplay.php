<?php
	function displayPosts($pdo, $user_id, $options){
		$pageNum = 1;
		$postsPerPage = 10;
		
		if(isset($_GET['pageNum'])){
			$pageNum = $_GET['pageNum'];
		}
		$limit = ($pageNum-1)*$postsPerPage;
		$myPostsObj = new blogPosts($user_id, $pdo, $limit, $postsPerPage);
		$posts = $myPostsObj->getBlogPosts();
		if(count($posts) != 0){
			echo "<div style='width: 400px; float: left;' >";
			foreach($posts as $post){
				#MAKE THEM LOOK PRETTY IN HERE
				echo "<div id='post".$post['id']."'>";
					echo $post['id']." - ".$post['timestamp'];
					echo "<br>";
					echo "<p id='post_content_".$post['id']."'>".$post['content']."</p>";
					echo "<br>";
					if(!$options){
						echo '<form id="delete" style="display:inline;" method="post" action="blog_functions/blogPostDelete.php">';
							echo '<input type="hidden" name="delete_id" value="'.$post['id'].'"/>'; 
							echo '<input type="submit" name="delete" value="Delete!"/>';   
						echo '</form>';
						echo '<button type="button" style="display:inline;" class="edit_button" value="'.$post['id'].'">Edit</button>';
					}
					echo '<br>';
				echo '</div>';
				################################
			}
			$totalPages = ceil($myPostsObj->getNumberPosts()/$postsPerPage);
			for($i = 1; $i <= $totalPages; $i++){
				#MAKE THEM LOOK PRETTY IN HERE
				if($i == $pageNum){
					echo "<p style='display:inline;'>-".$i."-</p>";
				}
				else{
					echo "<a href='blog.php?pageNum=".$i."'style='display:inline;'> -".$i."- </a>";
				}
				################################
			}
			echo "</div>";
		}
		else{
			echo "No posts to show.";
		}
	}
?>