-- Move plugin settings to private store
INSERT INTO `prefix_private_settings` (entity_guid, name, `value`) 
 SELECT e.guid as guid, name_string.string as name, value_string.string as `value` FROM `prefix_entities` e 
	JOIN `prefix_metadata` name_val ON e.guid=name_val.entity_guid
	JOIN `prefix_metastrings` name_string ON name_val.name_id = name_string.id
	JOIN `prefix_metastrings` value_string ON name_val.value_id = value_string.id
 WHERE
	e.type='object' AND
	e.subtype in (SELECT id from `prefix_entity_subtypes` WHERE subtype='plugin' and type='object');

-- Delete previous settings
CREATE TEMPORARY TABLE __upgrade_2008112001_1 (
 SELECT distinct meta.id as id from `prefix_metadata` meta 
	JOIN `prefix_private_settings` settings ON meta.entity_guid = settings.entity_guid
	JOIN `prefix_entities` e ON e.guid = meta.entity_guid
	JOIN `prefix_entity_subtypes` subtypes ON subtypes.id = e.subtype
 WHERE
	e.type='object' AND
	subtypes.subtype = 'plugin'
);

DELETE FROM `prefix_metadata` WHERE id in (SELECT id from __upgrade_2008112001_1);

DROP TABLE __upgrade_2008112001_1;