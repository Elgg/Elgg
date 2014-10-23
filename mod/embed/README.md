Embed plugin
============

Contents
--------

1. Overview
2. Adding a Tab
3. Populating a Select Tab
4. Populating an Upload Tab
5. Other WYSIWYG Editors and Embed

	
1. Overview
-----------

The Embed plugin is a simple way to allow users to link to or embed
their personal network content or third party resources in any text area.

The Embed plugin adds a menu item to the longtext menu. Clicking on this
link pops up a lightbox. The lightbox supports lists of content for insertion
and uploading new content.


2. Adding a Tab
---------------

The Embed plugin uses the menu system to manage its tabs. Use
elgg_register_menu_item() for the embed menu to add a new tab like this:

```php
$item = ElggMenuItem::factory(array(
	'name' => 'file',
	'text' => elgg_echo('file'),
	'priority' => 10,
	'data' => array(
		'options' => array(
			'type' => 'object',
			'subtype' => 'file',
		),
	),
));

elgg_register_menu_item('embed', $item);
```

Parameters:

* name: The unique name of the tab.
* text: The text shown on the tab
* priority: Placement of the tab.
* data: An array of parameters for creating the tab and its content.
		When listing content using the embed list view, pass the options for the
		elgg_list_entities() function as 'options'.
		When using a custom view for listing content or for uploading new
		content, pass the view name as 'view'.

See the file plugin for examples of registering both tab types.


3. Populating a Content Select Tab
----------------------------------

Nothing should be required other than setting the options parameter array
when registering the tab. See the view embed/item to see how an entity is
rendered.

If creating a custom list, the `<li>` elements must have a class of .embed-item.
The HTML content that is inserted must use the class .embed-insert.


4. Populating an Upload Tab
---------------------------

The view that is registered must be defined. It must include a form for
uploading the content. The form must .elgg-form-embed. Somewhere in the view
must be a hidden input field with the name embed_hidden with its value be
the name of the tab to forward the user to when uploading is complete.

See the view `embed/file_upload/content` for an example

	
5. Other WYSIWYG Editors and Embed
----------------------------------

Embed ships with support for the default input/longtext textarea.
Plugins replacing this view are expected to include JaVascript to
allow embed to work with the new editors.

To add custom JavaScript into the Embed plugin's `elgg.embed.insert()` function,
override the view `embed/custom_insert_js`. The textarea jQuery object is
available as the variable textArea and the content to be inserted is the
variable content. See the TinyMCE plugin for an example of this view.