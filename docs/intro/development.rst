Developer Overview
##################

This is a quick developer introduction to Elgg. It covers the basic approach to working with
Elgg as a framework, and mentions some of the terms and technologies used.

See the :doc:`/guides/index` for tutorials or the :doc:`/design/index` for in-depth discussion on design.

Database and Persistence
========================

Elgg uses MySQL 5.5 or higher for data persistence, and maps database values into Entities (a
representation of an atomic unit of information) and Extenders (additional information and
descriptions about Entities). Elgg supports additional information such as relationships between
Entities, activity streams, and various types of settings.

Plugins
=======

Plugins change the behavior or appearance of Elgg by overriding views, or by handling events and plugin hooks.
All changes to an Elgg site should be implemented through plugins to ensure upgrading core is easy.

Actions
=======

Actions are the primary way users interact with an Elgg site. Actions are registered by plugins.

Events and Plugin Hooks
=======================

Events and Plugin Hooks are used in Elgg Plugins to interact with the Elgg engine under certain
circumstances. Events and hooks are triggered at strategic times throughout Elgg's boot and execution
process, and allows plugins to modify or cancel the default behavior.

Views
=====

Views are the primary presentation layer for Elgg. Views can be overridden or extended by Plugins.
Views are categories into a Viewtype, which hints at what sort of output should be expected by the
view.

JavaScript
==========

Elgg uses an AMD-compatible JavaScript system provided by require.js. Bundled with Elgg are jQuery
1.11.0, jQuery UI 1.10.4, jQuery Form v20140304, jQuery jeditable, and jQuery UI Autocomplete.

Plugins can load their own JS libs.

Internationalization
====================

Elgg's interface supports multiple languages, and uses `Transifex`_ for translation.

Caching
=======

Elgg uses two caches to improve performance: a system cache and SimpleCache.

.. _Transifex: https://www.transifex.com/projects/p/elgg-core/