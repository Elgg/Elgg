List of plugin hooks in core
############################

.. toctree::
   :maxdepth: 2

System hooks
============

**index, system**

**email, system**

**page_owner, system**

**siteid, system**

**gc, system**

**unit_test, system**

**diagnostics:report, system**

**search_types, get_types**

**cron, <period>**

**display, view**
    deprecated in 1.8! Use view, (view) instead

**view, <view_name>**

**validate, input**

**geocode, location**

**diagnostics:report, all**

**debug, log**

**format, friendly:title**

**format, friendly:time**

**format, strip_tags**

**output, page**

**register, menu:<menu_name>**

**prepare, menu:<menu_name>**

**add, river**

User hooks
==========

**usersettings:save, user**

**unvalidated_login_attempt, user**

**unvalidated_requestnewpassword, user**

**access:collections:write, user**

**registeruser:validate:username, all**

**registeruser:validate:password, all**

**registeruser:validate:email, all**

**session:get, <key>**

**register, user**

Object hooks
============

**comments, <entity_type>**

**comments:count, <entity_type>**

**likes:count, <entity_type>**

Action hooks
============

**action, <action>**

**action_gatekeeper:permissions:check, all**

**forward, <reason>**

Permission hooks
================

**container_permissions_check, <entity_type>**

**permissions_check, <entity_type>**

**permissions_check, widget_layout**

**permissions_check:metadata, <entity_type>**

**permissions_check:comment, <entity_type>**

**permissions_check:annotate**

**fail, auth**

**session:get, <key>**

**api_key, use**

**access:collections:read, user**

**access:collections:write, user**

**access:collections:addcollection, collection**

**access:collections:deletecollection, collection**

**access:collections:add_user, collection**

**access:collections:remove_user, collection**

Other
=====

**entity:icon:url, <entity_type>**

**file:icon:url, override**

**entity:annotate, <entity_type>**

**import, all**

**export, all**

**object:notifications, <object_subtype>**

**notify:entity:message, <entity_type> or is it <object_subtype>**

**plugin:usersetting, user**

**plugin:setting, plugin**

**profile:fields, group**

**profile:fields, profile**

**widget_settings, <widget_handler>**

**get_list, default_widgets**

**rest, init**

**public_pages, walled_garden**

**volatile, metadata**

Plugins
=======

Embed
-----

**embed_get_items, <active_section>**

**embed_get_sections, all**

**embed_get_upload_sections, all**

HTMLawed
--------

**allowed_styles, htmlawed**

**config, htmlawed**

Twitter API
-----------

**login, twitter_api**

**new_twitter_user, twitter_service**

**first_login, twitter_api**

**authorize, twitter_api**

**plugin_list, twitter_service**

Reported Content
----------------

**reportedcontent:add, system**

**reportedcontent:archive, system**

**reportedcontent:delete, system**

**reportedcontent:add, <entity_type>**

**reportedcontent:archive, <entity_type>**

**reportedcontent:delete, <entity_type>**

Search
------

**search, <type>:<subtype>**

**search, tags**

**search, <type>**
