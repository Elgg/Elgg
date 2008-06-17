<?php
/**
 * Elgg administrator
 *
 * @copyright Copyright (c) 2007 Pro Soft Resources Inc. http://www.prosoftpeople.com
 * @author Rolando Espinoza La Fuente <rho@prosoftpeople.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

define('context', 'elggadmin');
require(dirname(dirname(dirname(__FILE__))) . '/includes.php');

$request = elggadmin_parse_request();

elggadmin_render_output($request);

?>
