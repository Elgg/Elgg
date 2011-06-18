Embed plugin - Point-and-click embedding using ECML.

CONTENTS:
	1. Overview
	2. Extending Embed
		1. Registering and Populating Embed Sections
		2. Registering Upload Sections
		3. Advanced Layouts
		4. Javascript
	3. Other Editors and Embed

	
1. Overview
	The Embed plugin is a simple way to allow users to link to or embed
	their personal network content or third party resources in any text area.
	
	Embed includes support for the default input/longtext view, but is easily
	extendable to include support for rich text editors like TinyMCE or CK 
	Editor.


2. Extending Embed
	The Embed plugin can be extended by other plugins using a combination
	of plugin hooks and specific views.
	
	Plugins can register a new content section or a new upload section.
	
	Plugins can also provide special views for their embed section, if
	they require something the default view doesn't provide.


2.1  Registering and Populating Embed Sections
	Plugins providing embed content should reply to two hooks: embed_get_sections:all
	and embed_get_items:$section_name.
	
	Embed emits the 'embed_get_sections' hook to populate the tabs of the modal display.
	Plugins supporting embed should reply by pushing an array element to the passed
	$value.
	
	Register plugins hooks like this:
	
		register_plugin_hook('embed_get_sections', 'all', 'my_plugin_embed_get_sections');
		
		function my_plugin_embed_get_sections($hook, $type, $value, $params) {
			$value['videolist'] = array(
				'name' => elgg_echo('my_plugin'),
				'layout' => 'list',
				'icon_size' => 'medium',
			);
		
			return $value;
		}
	
	The index of the returned array is the id used for the Embed section.
	
	Options available in the array value are:
		name => The friendly name to display in the tab
		layout => The layout style to use.  Layouts are found in the 
				embed/layouts/$layout and embed/item/$layout views.  
				Default supported layouts are list and gallery.
		icon_size => The icon size to use for in the item list.
		
		
	Embed emits the 'embed_get_items' hook to populate the embed section.  Plugins
	supporting embed should reply by returning an array of ElggEntities, which will
	be formatted by Embed.  If you need specific formatting for items, see section 2.3.
	
		register_plugin_hook('embed_get_items', 'videolist', 'videolist_embed_get_items');
		
		function my_plugin_embed_get_items($hook, $type, $value, $params) {
			$options = array(
				'owner_guid' => get_loggedin_userid(),
				'type_subtype_pair' => array('object' => 'my_plugin_type'),
				'count' => TRUE
			);
		
			if ($count = elgg_get_entities($options)) {
				$value['count'] += $count;
			
				unset($options['count']);
				$options['offset'] = $params['offset'];
				$options['limit'] = $params['limit'];
			
				$items = elgg_get_entities($options);
			
				$value['items'] = array_merge($items, $value['items']);
			}
		
			return $value;
		}
		
		Values passed in $params are:
			offset - Offset for entity list.
			limit - Limit for entity list.
			section - The current active section.
			upload_sections - Valid upload sections.
			internal_name - Internal name of the input field
			
		The function should return $value as:
			items - An array of ElggEntities
			count - The count of all available entities to embed
			
		In case other plugins want to mangle the value, be sure to
		check for existing values before appending.


2.2 Registering Upload Sections
	Embed includes a special tab, Upload, that allows users to immediatley
	upload a new item.  Like the embed sections, plugins can extend this
	to add their own upload form.
	
	Embed emits the embed_get_upload_sections:all hook to populate the
	dropdown in the upload tab.  Plugins should respond to this hook
	by returning an array with details about the upload section:  
	
		register_plugin_hook('embed_get_upload_sections', 'all', 'my_plugin_embed_get_upload_sections');
		
		function my_plugin_embed_get_upload_sections($hook, $type, $value, $params) {
			$value['my_plugin'] = array(
				'name' => elgg_echo('my_plugin'),
				'view' => 'my_plugin/forms/embed_upload'
			);
	
			return $value;
		}
		
	The array index is the ID of the upload section, and the values are:
		name - The friendly name of the upload section
		view - The view to use for the upload section's content
	
	The upload form view should use AJAX to upload a file and return
	the user to the correct section or automatically insert the new upload
	into the text area.  See Tidypics as an example. 
	
	
2.3 Advanced Layouts
	By default, Embed will automatically format items returned by 
	embed_get_items hooks.  If you need special formatting you can
	override both the content and layout views.
	
	Embed looks for a section-specific views before defaulting to built
	in formatting:
		embed/$section/content - The content of the embed section
								including all headers and navigation elements.
		embed/$section/item/$layout - The content of the embed section
								for the specific layout.  Inserted
								between navigation elements.
								
	Embed also supports adhoc layouts that can be shared among different plugins.
	To use a specific layout, register the embed section with your own layout
	and create the appropriate layout view:
	
		function my_plugin_embed_get_sections($hook, $type, $value, $params) {
			$value['videolist'] = array(
				'name' => elgg_echo('my_plugin'),
				'layout' => 'shared_embed_layout',
				'icon_size' => 'medium',
			);
		
			return $value;
		}
		
		Create the views 'embed/layouts/shared_embed_layout' and 
		'embed/items/shared_embed_layout.'  See the default list and 
		gallery layouts as examples.
		
		
2.4 Javascript
	If you use a custom layout you need to provide a way to insert
	the user's selection into the current editor.  Usually this will be
	an onClick event via Javascript.  Embed provides a helper function for
	this:
	
	elggEmbedInsertContent(content, textAreaId)
	
	Content is the pre-formatted content to insert into the text area,
	and textAreaId is the name of the text area.  This name is
	sent via GET as 'internal_name.'
	
	
3. Other Editors and Embed
	Embed ships with support for the default input/longtext textarea.
	Plugins replacing this view are expected to include Javascript to
	allow embed to work with the new editors.
	
	The elggEmbedInsertContent() JS function can be extened for custom
	text editors by extending the embed/custom_insert_js view.  Plugins 
	should extend this view with javascript code that inserts
	content into the specific text area.  Variables available within
	this view are:
		str content The content to insert.
		str textAreaId The name of the textarea to receive the content.
	
	Note: Extend this view; don't override it.  It is important to correctly
	extend this view for compatibility across multiple plugins and textarea 
	states.  Your custom JS should run without error no matter plugin order 
	or rich text editor status.  See TinyMCE as an example of how to losely 
	extend this function.