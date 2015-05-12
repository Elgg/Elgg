<?php
/**
 * Environment info
 */

$env = elgg()->env;
$name = '"' . $env->getName() . '"';
$prod_key = $env->isProd() ? 'option:yes' : 'option:no';

?>
<table class="elgg-table-alt">
	<tr class="odd">
		<td><b><?php echo elgg_echo('admin:env:label:name'); ?> :</b></td>
		<td><?php echo elgg_view('output/text', ['value' => $name]) ?></td>
	</tr>
	<tr class="even">
		<td><b><?php echo elgg_echo('admin:env:label:is_prod'); ?> :</b></td>
		<td><?php echo elgg_echo($prod_key) ?></td>
	</tr>
</table>
