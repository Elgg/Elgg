<table width="100%" class="menubar">
		<tr>
			<td>
				<?php

				$menu = get_register('menu');
				if (is_array($menu) && sizeof($menu) > 0) {
					foreach($menu as $item) {
						if (sizeof($item->children) > 0 ) {
							foreach($item->children as $subitem) {
?>
	<a href="<?php echo $item->value ?>"><?php echo $item->name; ?></a> |
<?php
							}
						}
					}
				}
				
				?>
			</td>
		</tr>
	</table>