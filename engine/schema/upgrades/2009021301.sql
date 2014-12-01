-- Changing the ACLs on existing groups
-- $db->from('entities', $e)
--    ->where($e->type->equals('group'))
--    ->update([$e->access_id => 2])
UPDATE `prefix_entities` SET access_id=2 WHERE type='group';