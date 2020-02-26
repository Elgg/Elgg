<?php

$ipsum = elgg_view('developers/ipsum');

?>
<div class="elgg-grid">
	<div class="elgg-col elgg-col-1of2">
		<div class="pam">
			<?php
			echo elgg_view_module('aside', 'Aside (.elgg-module-aside)', $ipsum, [
				'menu' => elgg_view('output/url', [
					'href' => false,
					'text' => elgg_echo('add'),
				])]);
			echo elgg_view_module('popup', 'Popup (.elgg-module-popup)', $ipsum);
			?>
		</div>
	</div>
	<div class="elgg-col elgg-col-1of2">
		<div class="pam">
			<?php
			echo elgg_view_module('info', 'Info (.elgg-module-info)', $ipsum, ['menu' => elgg_view_icon('plus')]);
			echo elgg_view_module('info', 'Info (.elgg-module-info)', $ipsum, [
				'menu' => elgg_view_menu('info-module', [
					'items' => [
						[
							'name' => 'item1',
							'text' => 'text',
							'href' => 'link',
							'class' => 'elgg-button elgg-button-action',
						],
					],
				]),
			]);
			echo elgg_view_module('featured', 'Featured (.elgg-module-featured)', $ipsum);
			?>
		</div>
	</div>
</div>
