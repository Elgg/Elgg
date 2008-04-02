<?php 

    $tags = "";
    
    if (!empty($vars['value']) && is_array($vars['value'])) {
        foreach($vars['value'] as $tag) {
            
            if (!empty($tags)) {
                $tags .= ", ";
            }
            $tags .= $tag->tag;
            
        }
    }
    
?>
<input type="text" <?php echo $vars['js']; ?> name="<?php echo $vars['internalname']; ?>" value="<?php echo $tags; ?>" class="input-tags"/> 