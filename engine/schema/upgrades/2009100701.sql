SET NAMES utf8;

LOCK TABLES `prefix_metastrings` WRITE;
/*!40000 ALTER TABLE `prefix_metastrings` DISABLE KEYS */;
REPLACE INTO `prefix_metastrings` (id, string) 
	SELECT id, unhex(hex(convert(string using latin1))) 
	FROM `prefix_metastrings`;
/*!40000 ALTER TABLE `prefix_metastrings` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `prefix_groups_entity` WRITE;
/*!40000 ALTER TABLE `prefix_groups_entity` DISABLE KEYS */;
REPLACE INTO `prefix_groups_entity` (guid, name, description) 
	SELECT guid, unhex(hex(convert(name using latin1))), unhex(hex(convert(description using latin1)))
	FROM `prefix_groups_entity`;
/*!40000 ALTER TABLE `prefix_groups_entity` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `prefix_objects_entity` WRITE;
/*!40000 ALTER TABLE `prefix_objects_entity` DISABLE KEYS */;	
REPLACE INTO `prefix_objects_entity` (guid, title, description)
	SELECT guid, unhex(hex(convert(title using latin1))), unhex(hex(convert(description using latin1)))
	FROM `prefix_objects_entity`;
/*!40000 ALTER TABLE `prefix_objects_entity` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `prefix_users_entity` WRITE;
/*!40000 ALTER TABLE `prefix_users_entity` DISABLE KEYS */;
REPLACE INTO `prefix_users_entity` (guid, name, username, password, salt, email, language, code, 
	banned, last_action, prev_last_action, last_login, prev_last_login)
		SELECT guid, unhex(hex(convert(name using latin1))), username, password, salt, email, language, code,
			banned, last_action, prev_last_action, last_login, prev_last_login
		FROM `prefix_users_entity`;
/*!40000 ALTER TABLE `prefix_users_entity` ENABLE KEYS */;
UNLOCK TABLES;
