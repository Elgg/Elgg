<?php

	$run_result .= "<select name=\"". $parameter[0] . "\">";

	if (sizeof($data['access']) > 0) {
		foreach($data['access'] as $access) {
			if ($parameter[1] == $access[1] && $parameter[1] != "") {
				$selected = ' selected="selected" ';
			} else {
				$selected = "";
			}
			$run_result .= <<< END
	<option value="{$access[1]}" {$selected}>
		{$access[0]}
	</option>
END;
		}
	}

	$run_result .= "</select>";
	
?>