<?php

    $val = trim($vars['value']);
    if (!empty($val)) {
	    if (substr_count($val, "http://") == 0) {
	        $val = "http://" . $val;
	    }
	    echo "<a href=\"{$val}\" target=\"_blank\">{$val}</a>";
    }

?>