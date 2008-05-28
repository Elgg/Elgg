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
	
			'notfound' => "The requested resource could not be found, or you do not have access to it.",
	
		/**
		 * User details
		 */

			'name' => "Display name",
			'email' => "Email address",
			'username' => "Username",
			'password' => "Password",
			'passwordagain' => "Password (again for verification)",
	
		/**
		 * Access
		 */
	
			'PRIVATE' => "Private",
			'LOGGED_IN' => "Logged in users",
			'PUBLIC' => "Public",
	
		/**
		 * Dashboard
		 */
	
			'dashboard' => "Dashboard",
	
		/**
		 * Profile
		 */
	
			'profile' => "Profile",
	
		/**
		 * Search
		 */
	
			'search' => "Search",
			'searchtitle' => "Search: %s",
	
		/**
		 * Account
		 */
	
			'account' => "Account",
			'settings' => "Settings",
	
			'register' => "Register",
			'registerok' => "You have successfully registered for %s.",
			'registerbad' => "Your registration was unsuccessful. The username may already exist, or your passwords might not match.",
	
		/**
		 * Administration
		 */
	
			'admin' => "Administration",
			'admin:description' => "The admin panel allows you to control all aspects of the system, from user management to how plugins behave. Choose an option below to get started.",
	
		/**
		 * Generic action words
		 */
	
			'save' => "Save",
			'update' => "Update",
			'edit' => "Edit",
			'delete' => "Delete",
			'load' => "Load",
			'upload' => "Upload",
	
		/**
		 * Generic data words
		 */
	
			'title' => "Title",
			'description' => "Description",
			'tags' => "Tags",
	
		/**
		 * Input / output strings
		 */

			'deleteconfirm' => "Are you sure you want to delete this item?",
			'fileexists' => "A file has already been uploaded. To replace it, select it below:",
	
		/**
		 * Import / export
		 */
			'importsuccess' => "Import of data was successful",
			
		/**
		 * Installation and system settings
		 */
	
			'installation' => "Installation",
			'installation:success' => "Elgg's database was installed successfully.",
			'installation:configuration:success' => "Your initial configuration settings have been saved. Now register your initial user; this will be your first system administrator.",
	
			'installation:settings' => "System settings",
			'installation:settings:description' => "Now that the Elgg database has been successfully installed, you need to enter a couple of pieces of information to get your site fully up and running. We've tried to guess where we could, but you may find that you need to tweak these details.",
			'sitename' => "The name of your site (eg \"My social networking site\"):",
			'wwwroot' => "The site URL, followed by a trailing slash:",
			'path' => "The full path to your site root on your disk, followed by a trailing slash:",
			'dataroot' => "The full path to the directory where uploaded files will be stored, followed by a trailing slash:",
	
		/**
		 * Welcome
		 */
	
			'welcome' => "Welcome %s",
			'welcome_message' => "Welcome to this Elgg installation.",
	
	);
	
	add_translation("en",$english);

?>