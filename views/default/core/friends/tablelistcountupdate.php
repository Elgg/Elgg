<?php
/**
 * Elgg friends picker count updater
 * Updates the friends count on a collection
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['count'] The count
 * @uses $vars['friendspicker'] The friendspicker counter number
 */

?>
<?php //@todo JS 1.8: no ?>
<script language="text/javascript">
	$("#friends_membership_count<?php echo $vars['friendspicker']; ?>").html("<?php echo $vars['count']; ?>");
</script>
