<?php

/**
 * oauth-php: Example OAuth server
 *
 * Simple logon for consumer registration at this server.
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

if (isset($_POST['username']) && isset($_POST['password']))
{
	if ($_POST['username'] == USERNAME && $_POST['password'] == PASSWORD)
	{
		$_SESSION['authorized'] = true;
		if (!empty($_REQUEST['goto']))
		{
			header('Location: ' . $_REQUEST['goto']);
			die;
		}

		echo "Logon succesfull.";
		die;
	}
}

$smarty = session_smarty();
$smarty->display('logon.tpl');

?>