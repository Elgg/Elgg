<?php
/**
 * Elgg upgrade script.
 *
 * This script triggers any necessary upgrades. If the site has been upgraded
 * to the most recent version of the code, no upgrades are run and the caches
 * are flushed. If you would prefer that this script is not accessible to others
 * after an upgrade, you can delete it. Future versions of Elgg will include a
 * new version of the script. Deleting the script is not a requirement and
 * leaving it behind does not affect the security of the site.
 *
 * Upgrades use a table {db_prefix}upgrade_lock as a mutex to prevent concurrent upgrades.
 *
 * The URL to forward to after upgrades are complete can be specified by setting $_GET['forward']
 * to a relative URL.
 *
 * @package    Elgg.Core
 * @subpackage Upgrade
 */

// we want to know if an error occurs
ini_set('display_errors', 1);

define('UPGRADING', 'upgrading');

require_once __DIR__ . '/autoloader.php';

(new Elgg\Application())->bootCore();

// test the URL rewrite rules
if (!class_exists('ElggRewriteTester')) {
	require dirname(__FILE__) . '/install/ElggRewriteTester.php';
}

$rewriteTester = new \ElggRewriteTester();
$url = elgg_get_site_url() . "__testing_rewrite?__testing_rewrite=1";
if (!$rewriteTester->runRewriteTest($url)) {
	// see if there is a problem accessing the site at all
	// due to ip restrictions for example

	if (!$rewriteTester->runLocalhostAccessTest()) {
		// note: translation may not be available until after upgrade
		$msg = elgg_echo("installation:htaccess:localhost:connectionfailed");
		if ($msg === "installation:htaccess:localhost:connectionfailed") {
			$msg = "Elgg cannot connect to itself to test rewrite rules properly. Check "
					. "that curl is working and there are no IP restrictions preventing "
					. "localhost connections.";
		}
		echo $msg;
		exit;
	}

	// note: translation may not be available until after upgrade
	$msg = elgg_echo("installation:htaccess:needs_upgrade");
	if ($msg === "installation:htaccess:needs_upgrade") {
		$msg = "You must update your .htaccess file so that the path is injected "
			. "into the GET parameter __elgg_uri (you can use install/config/htaccess.dist as a guide).";
	}
	echo $msg;
	exit;
}

// reset cache to have latest translations available during upgrade
elgg_reset_system_cache();

elgg_trigger_deprecated_event('upgrade', 'system', array(
	"The 'upgrade', 'system' event has been deprecated in favor of using \Elgg\Upgrades\Upgrade interface.",
	"2.0",
));

$upgrader = new \Elgg\UpgradeService();
$upgrades = $upgrader->getUnprocessedUpgrades();

echo elgg_view_page('', '', 'upgrade', array('upgrades' => $upgrades));
