<?php

	$english = array(
	
		/**
		 * Sessions
		 */
			
			'login' => "Log in",
			'loginok' => "You have been logged in.",
			'loginerror' => "We couldn't log you in. Make sure your details are correct and please try again.",
	
			'logout' => "Log out",
			'logoutok' => "You have been logged out.",
			'logouterror' => "We couldn't log you out. Please try again.",
	
		/**
		 * Errors
		 */
	
			'actionundefined' => "The requested action (%s) was not defined in the system.",
			'actionloggedout' => "Sorry, you cannot perform this action while logged out.",
	
		/**
		 * User details
		 */

			'username' => "Username",
			'password' => "Password",
	
		/**
		 * Dashboard
		 */
	
			'dashboard' => "Dashboard",
	
		/**
		 * Profile
		 */
	
			'profile' => "Profile",
	
		/**
		 * Account
		 */
	
			'account' => "Account",
	
			'register' => "Register",
			'registerok' => "You have successfully registered for %s.",
			'registerbad' => "Your registration was unsuccessful. The username may already exist, or your passwords might not match.",
	
		/**
		 * Welcome
		 */
	
			'welcome' => "Welcome %s",
			'welcome_message' => "Welcome to this Elgg installation.",
	
	);
	
	add_translation("en",$english);

?>