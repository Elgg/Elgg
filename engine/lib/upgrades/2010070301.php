<?php

/**
 * Group join river view has been renamed
 */

$query = "UPDATE {$CONFIG->dbprefix}river SET view='river/relationship/member/create'
			WHERE view='river/group/create' AND action_type='join'";
update_data($query);
