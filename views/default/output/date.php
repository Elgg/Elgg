<?php
    if ($vars['value'] > 86400) {
        echo gmdate("F j, Y",$vars['value']);
    }
?>