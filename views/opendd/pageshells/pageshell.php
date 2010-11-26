<?php
/**
 * Elgg XML output pageshell for ODD
 *
 * @package Elgg
 * @subpackage Core
 *
 */

header("Content-Type: text/xml");
?>
<odd>
<?php
echo $vars['body'];
?>
</odd>