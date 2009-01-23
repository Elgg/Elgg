<?php

	/**
	 * Elgg friend collections required hidden fields for js friends picker form 
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

		if (isset($vars['collection'])) {
?>

		<input type="hidden" name="collection_id" value="<?php echo $vars['collection']->id; ?>" />

<?php

		}

?>