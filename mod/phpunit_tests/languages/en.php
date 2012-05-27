<?php
/**
 *  Copyright (C) 2012 Quanbit Software S.A.
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 */

/**
 * Elgg PHPUnit tests plugin language pack
 * 
 * @author andres
 */

$english = array(
			//Admin settings label
				'admin:administer_utilities:phpunit_tests' => 'PHPUnit Tests',
				
			//Settings section titles
				'phpunit_test:section:site' =>'Test Site',
				'phpunit_test:section:admin' => 'Admin Test User',
				'phpunit_test:section:user' => 'Normal Test User',
			
			//Action button label				
				'phpunit_test:button_label:install' => 'Install Test Environment',
			
			//Errors when reading/writing config file
				'phpunit_test:error:reading_template' => 'Error reading the test config file template',
				'phpunit_test:error:write_config' => 'Error writing the test config file',

			//Settings form labels
				//Test site
				'phpunit_test:label:site:dataroot' => 'The full path of the test data directory:', 
				'phpunit_test:label:site:dbname' => 'The test DB name:',
				'phpunit_test:label:site:dbuser' => 'The test DB username:', 
 				'phpunit_test:label:site:dbpass' => 'The test DB password:',
				'phpunit_test:label:site:dbhost' => 'The test DB host',
				'phpunit_test:label:site:dbprefix' => 'The test DB table prefix',
				'phpunit_test:label:site:siteemail' => 'The test site email address (used when sending system emails):',
				'phpunit_test:label:site:sitename' => 'The test site name',
	 			'phpunit_test:label:site:wwwroot' =>  'The test site URL',

				//Test Admin USer
				'phpunit_test:label:admin:displayname' => 'Display name:',
 				'phpunit_test:label:admin:email' => 'e-mail:',
 				'phpunit_test:label:admin:username' => 'Username:',
 				'phpunit_test:label:admin:password' => 'Password:',
				
				//Test Standard USer
				'phpunit_test:label:user:displayname' => 'Display name:',
 				'phpunit_test:label:user:email' => 'e-mail:',
 				'phpunit_test:label:user:username' => 'Username:',
 				'phpunit_test:label:user:password' => 'Password:' 
 				
				);

add_translation("en", $english);