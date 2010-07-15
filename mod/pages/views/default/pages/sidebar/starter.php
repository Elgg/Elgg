<?php
/**
 * Start Pages page output
 *
 * @package ElggPages
 */

if (isset($vars['entity']) && $vars['entity'] instanceof ElggEntity) {

?>

<script type="text/javascript">
			$(document).ready( function() {
				$("#pagetree<?php echo $vars['entity']->getGUID(); ?>").click();
			});
</script>

<?php
}