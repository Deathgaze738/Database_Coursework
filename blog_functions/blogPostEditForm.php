<?php
	include "../functions.php";
	if(!isset($_POST['content']) || !isset($_POST['id'])){
		exit("Server Error");
	}	
	echo '<form id="update" style="display:inline;" method="post" action="blog_functions/blogPostEdit.php">';
		echo '<input type="text" name="content" value="'.$_POST['content'].'"/>';
		echo '<input type="hidden" name="update_id" value="'.$_POST['id'].'"/>'; 
		echo '<input type="submit" name="update" value="Update Post!"/>';   
	echo '</form>';
?>