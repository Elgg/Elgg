<?php 

    $tags = "";
    
    if (!empty($vars['value']) && is_array($vars['value'])) {
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
    }
    
?>
<input type="text" <?php echo $vars['js']; ?> name="<?php echo $vars['internalname']; ?>" value="<?php echo $tags; ?>" class="input-tags"/> 