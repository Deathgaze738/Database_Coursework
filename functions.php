<?php
	function verifyNull($array){
		foreach ($array as $var){
			if(isset($_POST[$var])){
				if("" == trim($_POST[$var])){
					return false;
				}
			}
			else{
				return false;
			}
		}
		return true;
	}
	
	function redirect($url){
		header('Location: ' . $url);
		exit();
	}
?>