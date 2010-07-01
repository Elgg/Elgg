<?php

require_once '../core/init.php';

assert_logged_in();

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		$store = OAuthStore::instance();
		$key   = $store->updateConsumer($_POST, 1, true);

		$c = $store->getConsumer($key);
		echo 'Your consumer key is: <strong>' . $c['consumer_key'] . '</strong><br />';
		echo 'Your consumer secret is: <strong>' . $c['consumer_secret'] . '</strong><br />';
	}
	catch (OAuthException $e)
	{
		echo '<strong>Error: ' . $e->getMessage() . '</strong><br />';
	}
}
		

$smarty = session_smarty();
$smarty->display('register.tpl');

?>