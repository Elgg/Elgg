Elgg Sql
========

A SQL-inspired DBAL and query builder.

Features:
 - Security: Automatic escaping of untrusted input
 - Correctness: Automatic table prefixing (never again forget to prepend `$CONFIG->dbprefix`!)
 - Type safety: Not possible to construct invalid queries
 - Familiarity: Looks very similar to plain MySQL queries
   - most keywords appear in the same order as if you had written the query string by hand
   - minimal extra PHP fluff 
 - Abstracted: Can be used to access different sql backends (i.e. mysql + sqlite)

Downsides:
 - Can't represent every valid SQL query

Examples
--------

Basic select with simple where clause.

```php
// Old API
$escaped_string = sanitize_string($string);
$query = "
SELECT *
FROM `{$CONFIG->dbprefix}metastrings`
WHERE string = '$escaped_string'
";
$db->getData($query);

// New API
$sqlDb->from('metastrings', $m)
	->where($m->string->equals($string))
	->select('*');
```

More complex select with a left join
```php
$query = "SELECT am.access_collection_id"
	. " FROM {$this->CONFIG->dbprefix}access_collection_membership am"
	. " LEFT JOIN {$this->CONFIG->dbprefix}access_collections ag ON ag.id = am.access_collection_id"
	. " WHERE am.user_guid = $user_guid AND (ag.site_guid = $site_guid OR ag.site_guid = 0)";

$aclMembershipTable
	->fromSelf($am)
	->leftJoin($aclsTable, $ag)->on($ag->id->equals($am->access_collection_id))
	->where(
		$am->user_guid->equals($user_guid)
		->and(
			$ag->site_guid->equals($site_guid)
			->or($ag->site_guid->equals(0))
		)
	)->select($am->access_collection_id);
```

Select with parenthesized OR clause (Conjunction of disjunctions)

```php
$query = "SELECT ag.id FROM {$this->CONFIG->dbprefix}access_collections ag ";
$query .= "WHERE ag.owner_guid = $user_guid AND (ag.site_guid = $site_guid OR ag.site_guid = 0)";

$accessCollectionsTable
	->fromSelf($ag)
	->where($ag->owner_guid->equals($user_guid)->and(
		$ag->site_guid->equals($site_guid)->or($ag->site_guid->equals(0))
	)->select($ag->id);
```

// DELETE EXAMPLES

$query = "DELETE FROM {$this->CONFIG->dbprefix}access_collection_membership"
		. "WHERE access_collection_id = {$collection_id}";
$aclMembershipTable->fromSelf($acl)
	->where($acl->access_collection_id->equals($collection_id))
	->delete();


// INSERT EXAMPLES
$query = "INSERT INTO {$CONFIG->dbprefix}metastrings (string) VALUES ('$escaped_string')";

$metastringsTable->insert([
	'string' => $string,
]);

// Basic insert
$q = "INSERT INTO {$this->CONFIG->dbprefix}access_collections
	SET name = '{$name}',
		owner_guid = {$owner_guid},
		site_guid = {$site_guid}";

$acls->insert([
	'name' => $name,
	'owner_guid' => $owner_guid,
	'site_guid' => $site_guid,
]);

// Do we need ON DUPLICATE KEY UPDATE if the behavior is a noop anyways?
$q = "INSERT INTO {$this->CONFIG->dbprefix}access_collection_membership
	SET access_collection_id = $collection_id, user_guid = $user_guid
	ON DUPLICATE KEY UPDATE user_guid = user_guid";

$aclMembershipTable->insert([
	'access_collection_id' => $collection_id,
	'user_guid' => $user_guid,
], [
	'user_guid' => $aclMembershipTable->column('user_guid'),
]);


$query = "INSERT into {$this->CONFIG->dbprefix}metadata"
	. " (entity_guid, name_id, value_id, value_type, owner_guid, time_created, access_id)"
	. " VALUES ($entity_guid, '$name_id','$value_id','$value_type', $owner_guid, $time, $access_id)";

$metadataTable->insert([
	'entity_guid' => $entity_guid,
	'name_id' => $name_id,
	'value_id' => $value_id,
	'value_type' => $value_type,
	'owner_guid' => $owner_guid,
	'time_created' => $time,
	'access_id' => $access_id,
]);



// If ok then add it
$query = "UPDATE {$this->CONFIG->dbprefix}metadata"
	. " set name_id='$name_id', value_id='$value_id', value_type='$value_type', access_id=$access_id,"
	. " owner_guid=$owner_guid where id=$id";

$metadataTable
	->fromSelf($m)
	->where($m->id->equals($id))
	->update([
		'name_id' => $name_id,
		'value_id' => $value_id,
		'value_type' => $value_type,
		'access_id' => $access_id,
		'owner_guid' => $owner_guid,
	]);

$query = "update {$db_prefix}metadata set access_id = {$access_id} where entity_guid = {$guid}";

$metadatTable
	->fromSelf($m)
	->where($m->entity_guid->equals($guid))
	->update([
		'access_id' => $access_id
	]);

$query = "SELECT * from {$this->CONFIG->dbprefix}metadata"
	. " WHERE entity_guid = $entity_guid and name_id=" . elgg_get_metastring_id($name) . " limit 1";

$metadataTable->fromSelf($m)
	->where($m->entity_guid->equals($entity_guid)->and($m->name_id->equals(elgg_get_metastring_id($name))))
	->limit(1)
	->select('*');
	

// Querying relationships table with mapper callback
$guid = (int)$guid;
$where = ($inverse_relationship ? "guid_two='$guid'" : "guid_one='$guid'");
$query = "SELECT * from {$this->CONFIG->dbprefix}entity_relationships where {$where}";
$result = _elgg_services()->db->getData($query, "row_to_elggrelationship");

$result = $relationshipsTable->fromSelf($r);
	->where(($inverse_relationship ? $r->guid_two : $r->guid_one)->equals($guid))
	->select('*')
	->map(function($row) { return row_to_elggrelationship($row); });



$query = "INSERT INTO {$this->CONFIG->dbprefix}entity_relationships
		(guid_one, relationship, guid_two, time_created)
		VALUES ($guid_one, '$relationship', $guid_two, $time)"

$relationshipsTable->insert([
	'guid_one' => $guid_one,
	'relationship' => $relationship,
	'guid_two' => $guid_two,
	'time_created' => $time,
]);
