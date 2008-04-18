<?php 

	/**
	 * Elgg tag input
	 * Displays a tag input field
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['value'] The current value, if any
	 * @uses $vars['js'] Any Javascript to enter into the input tag
	 * @uses $vars['internalname'] The name of the input field
	 * @uses $vars['value'] An array of tags
	 * 
	 */

    $tags = "";
    if (!empty($vars['value'])) {
    	if (is_array($vars['value'])) {
	        foreach($vars['value'] as $tag) {
	            
	            if (!empty($tags)) {
	                $tags .= ", ";
	            }
	            if (is_string($tag)) {
	            	$tags .= $tag;
	            } else {
	            	$tags .= $tag->value;
	            }
	            
	        }
    	} else {
    		$tags = $vars['value'];
    	}
    }
    
?>
<input type="text" <?php echo $vars['js']; ?> name="<?php echo $vars['internalname']; ?>" value="<?php echo htmlentities($tags); ?>" class="input-tags"/> 