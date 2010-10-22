<?php
/**
 * Elgg friend collections required hidden fields for js friends picker form
 *
 * @package Elgg
 * @subpackage Core
 */

if (isset($vars['collection'])) {
?>

	<input type="hidden" name="collection_id" value="<?php echo $vars['collection']->id; ?>" />

<?php
}