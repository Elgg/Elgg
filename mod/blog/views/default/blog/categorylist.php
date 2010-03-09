<?php
	$list = elgg_view('categories/list',$vars);
	if (!empty($list)) {
?>

	<div class="blog_categories">
		<?php echo $list; ?>
	</div>

<?php

	}

?>