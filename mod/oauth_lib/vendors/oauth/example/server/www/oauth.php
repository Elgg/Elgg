<?php

/**
 * oauth-php: Example OAuth server
 *
 * This file implements the OAuth server endpoints. The most basic
 * implementation of an OAuth server.
 *
 * Call with: /oauth/request_token, /oauth/authorize, /oauth/access_token
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

require_once '../core/init.php';

$server = new OAuthServer();

switch($_SERVER['PATH_INFO'])
{
case '/request_token':
	$server->requestToken();
	exit;

case '/access_token':
	$server->accessToken();
	exit;

case '/authorize':
	# logon

	assert_logged_in();

	try
	{
		$server->authorizeVerify();
		$server->authorizeFinish(true, 1);
	}
	catch (OAuthException $e)
	{
		header('HTTP/1.1 400 Bad Request');
		header('Content-Type: text/plain');
		
		echo "Failed OAuth Request: " . $e->getMessage();
	}
	exit;

	
default:
	header('HTTP/1.1 500 Internal Server Error');
	header('Content-Type: text/plain');
	echo "Unknown request";
}

?>