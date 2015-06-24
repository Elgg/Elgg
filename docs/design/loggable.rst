Loggable
========

Loggable is an interface inherited by any class that wants events relating to its member objects to be saved to the system log. ``ElggEntity`` and ``ElggExtender`` both inherit ``Loggable``.

Loggable defines several class methods that are used in saving to the default system log, and can be used to define your own (as well as for other purposes):

- ``getSystemLogID()`` Return a unique identifier for the object for storage in the system log. This is likely to be the object's GUID
- ``getClassName()`` Return the class name of the object
- ``getType()`` Return the object type
- ``getSubtype()`` Get the object subtype
- ``getObjectFromID($id)`` For a given ID, return the object associated with it

Database details
----------------

The default system log is stored in the ``system_log`` :doc:`database table <database>`. It contains the following fields:

- **id** - A unique numeric row ID
- **object_id** - The GUID of the entity being acted upon
- **object_class** - The class of the entity being acted upon (eg ElggObject)
- **object_type** - The type of the entity being acted upon (eg object)
- **object_subtype** - The subtype of the entity being acted upon (eg blog)
- **event** - The event being logged (eg create or update)
- **performed_by_guid** - The GUID of the acting entity (the user performing the action)
- **owner_guid** - The GUID of the user which owns the entity being acted upon
- **access_id** - The access restriction associated with this log entry
- **time_created** - The UNIX epoch timestamp of the time the event took place
