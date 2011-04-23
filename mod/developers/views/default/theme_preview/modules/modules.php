<?php 

$ipsum = elgg_view('developers/ipsum');

?>
<div class="elgg-grid">
	<div class="elgg-col elgg-col-1of2">
		<div class="pam">
			<?php
				echo elgg_view_module('aside', 'Aside (.elgg-module-aside)', $ipsum);
				echo elgg_view_module('popup', 'Popup (.elgg-module-popup)', $ipsum);
			?>
		</div>
	</div>
	<div class="elgg-col elgg-col-1of2">
		<div class="pam">
			<?php
				echo elgg_view_module('info', 'Info (.elgg-module-info)', $ipsum);
				echo elgg_view_module('featured', 'Featured (.elgg-module-featured)', $ipsum);
			?>
		</div>
	</div>
</div>