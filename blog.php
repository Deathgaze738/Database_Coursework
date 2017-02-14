<?php
	include_once 'dbconnect.php';
	include_once 'functions.php';
	include_once 'verifySession.php';
	include_once 'classes/blogPosts.php';
	include_once 'classes/user.php';
	$user_id = $_SESSION['id'];
	$user;
	$permissionTrigger = 0;
	if(isset($_GET['blogid'])){
		include_once 'blog_functions/checkBlogPermission.php';
		if($_GET['blogid'] == $_SESSION['id']){
			redirect("blog.php");
		}
		if(!checkBlogPermission($user_id, $_GET['blogid'], $pdo)){
			$permissionTrigger = 1;
		}
		$user_id = $_GET['blogid'];
		$user = new user($_GET['blogid'], $pdo);
		
	}
	else{
		$user = new user($_SESSION['id'], $pdo);
	}
?>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</head>
<div id="mainWrapper">
<?php
	include 'navBar.php';
	if(!ISSET($_GET['blogid'])){
		echo '<h1>Create a post</h1>';
		echo '<form action="blog_functions/blogPostCreate.php" method="POST">';
			echo '<label>Content: </label><input name="content" type="text"><br>';
			echo '<input type="submit" value="Post to my blog."><br>';
		echo '</form>';
		echo '<h1>Blog Posts</h1>';
		include_once 'blog_functions/blogPostDisplay.php';
		include_once 'friendsList.php';
		displayPosts($pdo, $user_id, 0);
		printFriendsInfo($user_id, $pdo, $user, 1);
	}
	else{
		include_once 'blog_functions/checkIfFriends.php';
		$check = checkFriendship($_SESSION['id'], $_GET['blogid'], $pdo);
		if($check == 0){
			echo '<form action="blog_functions/addFriend.php" method="POST">';
				echo '<input name="friendid" value="'.$_GET['blogid'].'" type="hidden">';
				echo '<input name="submit" value="Add Friend" type="submit">';
			echo '</form>';
		}
		elseif($check == 2){
			echo 'Friend Request Sent';
		}
		echo '<a href="photos.php?userid='.$_GET['blogid'].'"> Pictures </a>';
		echo '<h1>Blog Posts</h1>';
		include_once 'blog_functions/blogPostDisplay.php';
		if(!$permissionTrigger){
			include_once 'friendsList.php';
			displayPosts($pdo, $user_id, 1);
			printFriendsInfo($user_id, $pdo, $user, 0);
		}
		else{
			echo "No Permission";
		}
	}
?>
</div>
<script>
	$(".edit_button").click(function(){
		console.log($(this).attr("value"));
		var post_id = parseInt($(this).attr("value"));
		var div_to_change = "#post"+$(this).attr("value");
		var post_content = $("#post_content_"+post_id).text();
		console.log(post_content);
		$.ajax({url: "blog_functions/blogPostEditForm.php", 
				method: "POST",
				data:{"content": post_content,
					"id": post_id},
				success: function(result){
			$(div_to_change).html(result);
		}});
	});
</script>