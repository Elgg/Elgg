<?php

    if (!empty($vars['value'])) {
        echo "A file has already been uploaded. To replace it, select it below:<br />";
    }

?>
<input type="file" size="30" name="<?php echo $vars['internalname']; ?>" />