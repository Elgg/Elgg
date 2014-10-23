Site-wide categories
--------------------

NOTES FOR DEVELOPERS:

If you're not a programmer, don't worry! All the main Elgg tools
are already adapted to use categories, and a growing number of
third party Elgg tools use it too.


This plugin uses views and events hooks to allow you to easily add
site-wide categories across Elgg tools.

This is a two-line addition to any plugin.

In your edit/create form:

	echo elgg_view('input/categories', $vars);

In your object view:

	echo elgg_view('output/categories', $vars);
	
Note that in both cases, $vars['entity'] MUST be populated with
the entity the categories apply to, if it exists.