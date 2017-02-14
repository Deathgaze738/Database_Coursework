<?php
	include_once 'dbconnect.php';
	include_once 'functions.php';
	include_once 'verifySession.php';
	include_once 'classes/user.php';
?>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</head>
<div id="mainWrapper">
	<?php
		include_once 'navbar.php';
	?>
	<div id="photoWrapper">
		<?php
			include_once 'photo_functions/photoCollectionList.php';
			displayPhotoCollections($pdo, $_SESSION['id']);
		?>
	</div>
</div>