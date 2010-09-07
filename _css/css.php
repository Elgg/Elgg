<?php
/**
 * Outputs the main CSS view.
 *
 * Requests to $url/css.css are rewritten via
 * mod_rewrite rules in .htaccess (or similar) to this file.
 *
 * The main CSS is a view located at 'css'.  The location of the
 * file used to generate this view changes based upon current viewtype
 * and plugins enabled.  By default the viewtype is 'default' (HTML) and the
 * view file is views/default/css.php.  Plugins can override or extend this view.
 *
 * This view is cached via simplecache.
 *
 * @see views/default/css.php
 * @see simplecache/view.php
 * @see elgg_extend_view()
 *
 * @uses $_GET['viewtype'] The current viewtype.  Determines where to look for the
 * css.php view.
 * @uses $override A global that tells simplecache to ignore caching.
 *
 * @package Elgg
 * @subpackage Core
 */

global $viewinput, $override;

$viewinput['view'] = 'css';
$viewinput['viewtype'] = $_GET['viewtype'];

header("Content-type: text/css", true);
header('Expires: ' . date('r',time() + 86400000), true);
header("Pragma: public", true);
header("Cache-Control: public", true);

require_once(dirname(dirname(__FILE__)) . '/simplecache/view.php');
