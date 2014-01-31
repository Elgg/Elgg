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

**validate, input**

**geocode, location**

**diagnostics:report, all**

**debug, log**

**format, friendly:title**

**format, friendly:time**

**format, strip_tags**

**output:before, page**
    In elgg_view_page(), this filters $vars before it's passed to the page shell
    view (page/\*). To stop sending the X-Frame-Options header, unregister the
    handler _elgg_views_send_page_headers() from this hook.

**output, page**
    In elgg_view_page(), this filters the output return value

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

**login:forward, user**
    Allow changing the URL to which the user will be forwarded after login

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

**get_sql, access**
    Allows altering the SQL clauses used in _elgg_get_access_where_sql()

Views
=====

**view, <view_name>**
    Allows altering the returned content of the view

**layout, page**
    Allows altering the layout name in elgg_view_layout()

**display, view**
    deprecated in 1.8! Use view, (view) instead

**shell, view**
    Allows altering the page shell name in elgg_view_page()

**head, page**
    Allows altering $vars['head'] in elgg_view_page()

Other
=====

**default, access**
    Allows altering the return value of get_default_access()

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

**maintenance:allow, url**
    Allows whitelisting URLs to non-admins during maintenance mode

Plugins
=======

File
----

**simple_type, file**
    Allows you to change the return value of file_get_simple_type()

Embed
-----

**embed_get_items, <active_section>**

**embed_get_sections, all**

**embed_get_upload_sections, all**

HTMLawed
--------

**allowed_styles, htmlawed**

**config, htmlawed**

Members
-------

**members:list, <page_segment>**
    To handle the page /members/$page_segment, handle this hook and return the HTML of the list.

**members:config, tabs**
    This hook is used to assemble an array of tabs to be passed to the navigation/tabs view
    for the members pages.

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
