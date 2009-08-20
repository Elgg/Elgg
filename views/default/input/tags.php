<?php 

	/**
	 * Elgg tag input
	 * Displays a tag input field
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

	 * @link http://elgg.org/
	 * 
	 * @uses $vars['value'] The current value, if any
	 * @uses $vars['js'] Any Javascript to enter into the input tag
	 * @uses $vars['internalname'] The name of the input field
	 * @uses $vars['value'] An array of tags
	 * @uses $vars['class'] Class override
	 */

	$class = $vars['class'];
	if (!$class) $class = "input-tags";

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
<input type="text" <?php if ($vars['disabled']) echo ' disabled="yes" '; ?><?php echo $vars['js']; ?> name="<?php echo $vars['internalname']; ?>" <?php if (isset($vars['internalid'])) echo "id=\"{$vars['internalid']}\""; ?> value="<?php echo htmlentities($tags, ENT_QUOTES, 'UTF-8'); ?>" class="<?php echo $class; ?>"/> 