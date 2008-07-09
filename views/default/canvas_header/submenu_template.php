<?php

	if (isset($vars['selected']) && $vars['selected'] == true) {
		$selected = "class=\"selected\"";
	} else {
		$selected = "";
	}

?>
<li <?php echo $selected; ?>><a href="<?php echo $vars['href']; ?>"><?php echo $vars['label']; ?></a></li>