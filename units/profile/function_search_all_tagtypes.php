<?php

	foreach($data['profile:details'] as $profiletype) {
		if ($profiletype[2] == "keywords") {
			$data['search:tagtypes'][] = $profiletype[1];
		}
	}

?>