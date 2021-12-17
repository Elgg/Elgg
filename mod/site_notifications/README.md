# Site Notifications

In Elgg 1.5-1.8, site notifications were provided by the Messages plugin. In Elgg
1.9 they were moved to a separate plugin.

## Note for developers

The cron based cleanup of (un)read site notifications removes the entities directly from the database.
It isn't using ``$entity->delete()`` to help with performance. This means that no events are triggered for 
the entities which are removed during the cleanup.
