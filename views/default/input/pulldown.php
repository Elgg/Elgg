<select name="<?php echo $vars['internalname']; ?>" <?php echo $vars['js']; ?>>
<?php

    foreach($vars['options'] as $option) {
        if ($option != $vars['value']) {
            echo "<option>{$option}</option>";
        } else {
            echo "<option selected=\"selected\">{$option}</option>";
        }
    }

?> 
</select>