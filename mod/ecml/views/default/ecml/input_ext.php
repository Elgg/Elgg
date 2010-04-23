<?php
/**
 * Displays an ECML icon on ECML-enabled forms
 *
 * @package ECML
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$docs_href = "{$vars['url']}pg/ecml";
?>
<a href="<?php echo $docs_href; ?>" class="longtext_control" title="<?php echo elgg_echo('ecml:help'); ?>" target="_new"><img src="<?php echo $vars['url']; ?>mod/ecml/graphics/ecml.png" width="50" height="15" alt="ECML" /></a>