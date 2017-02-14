<?php
	include_once 'dbconnect.php';
	include_once 'functions.php';
	include_once 'verifySession.php';
	include_once 'classes/user.php';
	include_once 'classes/circle_content.php';
	$user_id = $_SESSION['id'];
	$circle_id = 0;
	$user = new user($user_id, $pdo);
	if(isset($_GET['circleid'])){
		include_once 'circle_functions/verifyCirclePermission.php';
		if($_GET['circleid'] == $_SESSION['id']){
			redirect("http://localhost/circles.php");
		}
		if(!verifyCirclePermission($user_id, $_GET['circleid'], $pdo)){
			exit("No Permission");
		}
		$circle_id = $_GET['circleid'];
	}
?>
<style>
	.circle_info{
		display: inline;
		width: 10%;
		height: 10vh;
	}
	.circle_info:hover{
		background: gray;
	}
	.circle_info *{
		display: inline;
		width: 10%;
		height: 10vh;
		padding: 10px;
	}
	.circle_controls{
		display: none;
	}
	#send_message_form{
		display: none;
	}
</style>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</head>
<div id="mainWrapper">
	<?php
		include_once 'navbar.php';
	?>
	<div id="circleWrapper">
		<div id="circleList">
			<?php
				include_once 'classes/circle.php';
				$circles = $user->getCircles();
				foreach($circles as $circle){
						echo '<div class="circle_info" value="'.$circle->getId().'">';
							echo '<h2 class="circle_name">'.$circle->getName().'</h2>';
						echo '</div>';
				}
			?>
		</div>
		<div id="circleCreation">
			<form action="circle_functions/circle_create.php" method="POST">
				<label>Name: </label><input name="name" type="text">
				<label>Description: </label><input name="desc" type="text">
				<input value="Create Circle" type="submit">
			</form>
		</div>
		<?php
			$friends = $user->getFriends();
			foreach($circles as $circle){
				$circle_content = new circle_content($pdo, $circle->getId());
				$members = $circle_content->getMembers();
				echo '<div class="circle_controls" id="controls_'.$circle->getId().'">';
					echo '<form action="circle_functions/circle_leave.php" method="POST">';
						echo '<input name="circle_id" value="'.$circle->getId().'" type="hidden">';
						echo '<input name="submit" value="Leave Circle" type="submit">';
					echo '</form>';
					echo '<form action="circle_functions/circle_add.php" method="POST">';
						echo '<input name="circle_id" value="'.$circle->getId().'" type="hidden">';
						echo '<select name="member">';
						foreach($friends as $friend){
							if(!in_array($friend, $members, true)){
								echo '<option value="'.$friend['id'].'">'.$friend['first_name'].' '.$friend['last_name'].'</option>';
							}
						}
						echo '</select>';
						echo '<input name="submit" value="Add to Circle" type="submit">';
					echo '</form>';
				echo '</div>';
			}
		?>
		<div id="circleContent">
			<?php
				echo "<h1>Please Choose a Circle.</h1>";
			?>
		</div>
		<?php
			echo '<div id="send_message_form">';
				echo '<fieldset>';
					echo '<input id="send_content" type="text" name="content">';
					echo '<input id="send_submit" type="button" value="Send" disables="">';
				echo '</fieldset>';
			echo '</div>';
		?>
	</div>
</div>
<script>
	$(function(){
		<?php echo "var circle_id =".$circle_id.";"; ?>
		var flag = 0;
		if(circle_id != 0){
			var control_id = "#controls_"+circle_id;
			updateCircle();
			$("#send_message_form").show();
			control_id = "#controls_"+circle_id;
			console.log(control_id);
			flag = 1;
		}
		$(control_id).show();
		
		$("#send_submit").click(function(){
			var content = $("#send_content").val();
			$("#send_content").val("");
			console.log(content);
			$.ajax({url: "circle_functions/circle_content_send.php", 
					method: "POST",
					data:{"circle_id": circle_id,
						"content": content},
					success: function(result){
						updateCircle()
			}});
		});
		$(".circle_info").click(function(){
			control_id = "#controls_"+circle_id;
			$(control_id).hide();
			circle_id = parseInt($(this).attr("value"));
			updateCircle();
			$("#send_message_form").show();
			control_id = "#controls_"+circle_id;
			console.log(control_id);
			$(control_id).show();
		});
		function updateCircle(){
			console.log("UPDATED");
			flag = 1;
			var div_to_change = "#circleContent";
			$.ajax({url: "circle_functions/circle_content_handler.php", 
					method: "POST",
					data:{"circle_id": circle_id},
					success: function(result){
				$(div_to_change).html(result);
			}});
		}
		setInterval(function() {
			if(flag){
				updateCircle();
			}
		}, 5000);
	});

</script>