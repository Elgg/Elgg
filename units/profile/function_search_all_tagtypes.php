<?php

    foreach($data['profile:details'] as $profiletype) {
        if ($profiletype->field_type == "keywords") {
            $data['search:tagtypes'][] = $profiletype->internal_name;
        }
    }

?>