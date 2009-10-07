SET NAMES utf8;

REPLACE INTO `prefix_metastrings` (id, string) 
	SELECT id, unhex(hex(convert(string using latin1))) 
	FROM `prefix_metastrings`;

REPLACE INTO `prefix_groups_entity` (guid, name, description) 
	SELECT guid, unhex(hex(convert(name using latin1))), unhex(hex(convert(description using latin1)))
	FROM `prefix_groups_entity`;

REPLACE INTO `prefix_objects_entity` (guid, title, description)
	SELECT guid, unhex(hex(convert(title using latin1))), unhex(hex(convert(description using latin1)))
	FROM `prefix_objects_entity`;

REPLACE INTO `prefix_users_entity` (guid, name, username, password, salt, email, language, code, 
	banned, last_action, prev_last_action, last_login, prev_last_login)
		SELECT guid, unhex(hex(convert(name using latin1))), username, password, salt, email, language, code,
			banned, last_action, prev_last_action, last_login, prev_last_login
		FROM `prefix_users_entity`;
