<?php

	// Do we have messages?
	
		if (isset($_SESSION['messages']) && sizeof($_SESSION['messages']) > 0) {
			if (isset($messages) && sizeof($messages) > 0) {
				array_merge($messages, $_SESSION['messages']);
			} else {
				$messages = $_SESSION['messages'];
			}
			unset($_SESSION['messages']);
		}

	// Has 'action' been set?
	
		if (isset($_POST['action']) && $_POST['action'] != "") {
		
			
			switch($_POST['action']) {
				
				case "log_on":		run("users:log_on");
									break;
				case "log_off":		run("users:log_off");
									break;
				case "register":	run("users:register");
									break;
				
			}
			
		}
		
		if (isset($_GET['action']) && $_GET['action'] != "") {
		
			
			switch($_GET['action']) {
				
				case "log_off":		run("users:log_off");
									break;
				
			}
			
		}
		
?>