<?php
/**
 * Outputs a JS view.
 *
 * There are 2 main JS views used in elgg:
 * 	js/initialise_elgg
 * 	js/friendsPickerv1
 *
 * The location of the files used to generate these view can change based upon
 * current viewtype and plugins enabled.  By default the viewtype is
 * 'default' (HTML) and the view files are in views/default/js/.  Plugins can
 * override or extend these views.
 *
 * These 2 main JS views are cached via simplecache.
 *
 * @see views/default/js/initialise_elgg.php
 * @see views/default/js/friendsPickerv1.php
 * @see simplecache/view.php
 * @see elgg_extend_view()
 *
 * @uses $_GET['viewtype'] The current viewtype.  Determins where to look for the
 * JS view files.
 * @uses $_GET['view'] The view to output, relative to the view js/
 * @uses $override A global that tells simplecache to ignore caching.
 *
 * @package Elgg
 * @subpackage Core
 */

global $viewinput, $override;

$viewinput['view'] = 'js/' . $_GET['js'];
$viewinput['viewtype'] = $_GET['viewtype'];

header('Content-type: text/javascript');
header('Expires: ' . date('r',time() + 864000000));
header("Pragma: public");
header("Cache-Control: public");

require_once(dirname(dirname(__FILE__)) . '/simplecache/view.php');