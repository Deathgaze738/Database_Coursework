<?php
	class circle{
		private $id;
		private $creator;
		private $name;
		private $description;
		
		function __construct($id, $creator, $name, $description){
			$this->id = $id;
			$this->creator = $creator;
			$this->name = $name;
			$this->description = $description;
		}
		
		function getId(){
			return $this->id;
		}
		
		function getCreator(){
			return $this->creator;
		}
		
		function getName(){
			return $this->name;
		}
		
		function getDescription(){
			return $this->description;
		}
	}
?>