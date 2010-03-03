<?php

	if (isset($vars['entity']) && $vars['entity'] instanceof ElggEntity) {

?>

<script type="text/javascript">
			
			$(document).ready( function() {
			
				$("#pagetree<?php echo $vars['entity']->getGUID(); ?>").click();
			
			});
			
</script>

<?php

	}

?>