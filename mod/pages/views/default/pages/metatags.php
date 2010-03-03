<?php

		$treeguid = get_input('treeguid');
		if (empty($treeguid)) {
			$treeguid = get_input('page_guid');
		}

?>

		<script type="text/javascript" src="<?php echo $vars['url']; ?>mod/pages/javascript/jquery.treeview.js" ></script>
		<script type="text/javascript" src="<?php echo $vars['url']; ?>mod/pages/javascript/jquery.treeview.async.js" ></script>
		<script type="text/javascript">
			
			$(document).ready( function() {
				
				$("#pagesTree").treeview({
					url: "<?php echo $vars['url']; ?>mod/pages/pagesTree.php",
					currentpage: "<?php echo get_input('page_guid'); ?>"
				}, "<?php echo $treeguid; ?>")
				
			});
		</script>
