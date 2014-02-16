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
    handler _elgg_views_send_header_x_frame_options() from this hook.

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
    Filters the URL to which the user will be forwarded after login

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
    Filters the SQL clauses used in _elgg_get_access_where_sql()

Views
=====

**view, <view_name>**
    Filters the returned content of views

**layout, page**
    In elgg_view_layout(), filters the layout name

**display, view**
    Deprecated in 1.8! Use view, (view) instead

**shell, view**
    In elgg_view_page(), filters the page shell name

**head, page**
    In elgg_view_page(), filters $vars['head']

Other
=====

**default, access**
    In get_default_access(), filters the return value

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
    In file_get_simple_type(), filters the return value

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

**search_types, get_types**

**search_types, get_queries**
    Before a search this filters the types queried. This can be used to reorder
    the display of search results.

