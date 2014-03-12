<?php

$query = "
	UPDATE {$CONFIG->dbprefix}users_entity
	SET `code` = ''
	WHERE `admin` = 'yes'
";
update_data($query);
