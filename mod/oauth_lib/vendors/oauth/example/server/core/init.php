<?php

/**
 * oauth-php: Example OAuth server
 *
 * Global initialization file for the server, defines some helper
 * functions, required includes, and starts the session.
 *
 * @author Arjan Scherpenisse <arjan@scherpenisse.net>
 *
 * 
 * The MIT License
 * 
 * Copyright (c) 2007-2008 Mediamatic Lab
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */


/*
 * Simple 'user management'
 */
define ('USERNAME', 'sysadmin');
define ('PASSWORD', 'sysadmin');


/*
 * Always announce XRDS OAuth discovery
 */
header('X-XRDS-Location: http://' . $_SERVER['SERVER_NAME'] . '/services.xrds');


/*
 * Initialize the database connection
 */
$info = parse_url(getenv('DB_DSN'));
($GLOBALS['db_conn'] = mysql_connect($info['host'], $info['user'], $info['pass'])) || die(mysql_error());
mysql_select_db(basename($info['path']), $GLOBALS['db_conn']) || die(mysql_error());
unset($info);


require_once '../../../library/OAuthServer.php';

/*
 * Initialize OAuth store
 */
require_once '../../../library/OAuthStore.php';
OAuthStore::instance('MySQL', array('conn' => $GLOBALS['db_conn']));


/*
 * Session
 */
session_start();


/*
 * Template handling
 */
require_once 'smarty/libs/Smarty.class.php';
function session_smarty()
{
	if (!isset($GLOBALS['smarty']))
	{
		$GLOBALS['smarty'] = new Smarty;
		$GLOBALS['smarty']->template_dir = dirname(__FILE__) . '/templates/';
		$GLOBALS['smarty']->compile_dir = dirname(__FILE__) . '/../cache/templates_c';
	}
	
	return $GLOBALS['smarty'];
}

function assert_logged_in()
{
	if (empty($_SESSION['authorized']))
	{
		$uri = $_SERVER['REQUEST_URI'];
		header('Location: /logon?goto=' . urlencode($uri));
	}
}

function assert_request_vars()
{
	foreach(func_get_args() as $a)
	{
		if (!isset($_REQUEST[$a]))
		{
			header('HTTP/1.1 400 Bad Request');
			echo 'Bad request.';
			exit;
		}
	}
}

function assert_request_vars_all()
{
	foreach($_REQUEST as $row)
	{
		foreach(func_get_args() as $a)
		{
			if (!isset($row[$a]))
			{
				header('HTTP/1.1 400 Bad Request');
				echo 'Bad request.';
				exit;
			}
		}
	}
}

?>