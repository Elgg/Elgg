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
 * The command line installer script for the phpunit environment.
 * By running this script the test DB will be populated and the
 * fixtures will be created. 
 * 
 * IMPORTANT: Before running this installer you should have created 
 * the config.ini file, either by using the PHPUnit Tests plugin settings
 * or by creating it by hand. 
 * 
 * @author andres
 */
//require_once(dirname(__FILE__) . "/model/ElggTestConfigFile.php");
require_once(dirname(__FILE__) . "/model/ElggTestInstaller.php");

//$configFile = new ElggTestConfigFile('config.ini');
//$params = $configFile->getMappings();

$installer = new ElggTestInstaller();
$installer->install('config.ini');