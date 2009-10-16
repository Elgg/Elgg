<?php
/**
 * Elgg XML output pageshell for ODD
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 */

header("Content-Type: text/xml");
?>
<odd>
<?php
echo $vars['body'];
?>
</odd>