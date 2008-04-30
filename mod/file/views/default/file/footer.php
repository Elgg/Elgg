<?php
	/**
	 * Elgg file browser footer
	 * 
	 * @package ElggFile
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$limit = $vars['limit'];
	$offset = $vars['offset']; 
	$url = $_SERVER['request_uri'];
?>
<div id="navbar">
	<table width="100%">
		<tr>
			<td>
				<div id="prev"><?php if ($offset>0) { ?><a href="<?php echo "$url?offset=" . ($offset-$limit); ?>">Prev</a><?php } ?>
				</div>
			</td>
			<td align="right">
				<div id="next">
					<a href="<?php echo "$url?offset=" . ($offset+$limit); ?>">Next</a>
				</div>
			</td>
		</tr>
	</table>
</div>