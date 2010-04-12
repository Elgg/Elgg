Site pages - Quickly generate static pages and customize the front page, CSS, and
HTML metatags.

CONTENTS:
	1.  Overview
	2.  Using Front Page Keywords
		2.1  Built-in keywords
		2.2  Entities
		2.3  Views
	3.  Custom Front Page Keywords
	4.  Hints and Quirks


1.  OVERVIEW

	Site Pages provides a simple way to create static content for About, Terms,
	and Privacy pages, and also allows simple modifications of the logged in
	and logged out views, as well as CSS and meta description and tags for SEO.

	The biggest feature of Site Pages is its support for an extensible keyword
	system that allows end-users to quickly add elements to the front page of
	their site. 


2.  USING FRONT PAGE KEYWORDS

	Keywords are specially formatted strings that can be used on the logged in
	and logged out front pages to include lists of objects, views, or plugin-
	supplied content.  All keywords are surrounded by two brackets: 
	[[keyword]].  Some keywords, like views and entity lists, take optional 
	parameters.

	When editing the front pages, a list of available keywords appears in the 
	sidebar.


2.1  BUILT IN KEYWORDS

	Site Pages includes a few built-in keywords to get you started:
		[[login_box]] - This keyword is required on the logged out page to
						Allow users to log into your site.

		[[site_stats]] - Shows the total members in your site, the currently
						active members, and other fun stuff.


2.2  Entities

	You can generate a list of entities by using the [[entity]] keyword.  This
	keyword takes similar arguments to the elgg_get_entities() function.  See
	documentation in that function for a complete list.

	Additional / changed parameters supported by keywords:
	* owner: The username owner. (You can still use owner_guid)

	Example: To generate a list of all blog posts by the user named 'admin':
		[[entities: type=object, subtype=blog, owner=admin]]

	Example: To show newest group created:
		[[entities: type=object, subtype=group, limit=1]]


2.1 Views

	Keywords support outputting arbitrary views with the [[view]] keyword and
	supports passing arguments as name=value pairs.

	Example: Output a text input field with a default value:
		[[view: input/text, value=This is a test!]]

	NB: Do NOT quote the name or values when passing them.  Also, as of 1.8
	using commas or = in the name or value is unsupported.


3.0  CUSTOM FRONT PAGE KEYWORDS

	Plugins can add their own keywords by replying to the 'get_keywords' hook
	of type 'sitepages.'  Each keyword must be bound to a valid view.  Almost 
	all functionality in custom keywords could be implemented using the 'view' 
	keyword, but custom keywords provide a simple way for non-techy users to 
	include ready-made views without the fuss of knowing what they're doing.

	Custom keywords support arguments in the same format as views and entities.
	These arguments are passed to the custom view via the $vars array.  It is
	the responsibility of the custom view to parse these arguments.

	The below example creates the 'my_plugin_keyword' keyword that displays the
	view at 'my_plugin/keyword_view.'  This is exactly the same as saying 
	[[view: my_plugin/keyword_view]] but much simpler for the user.

	Example:
		register_plugin_hook('get_keywords', 'sitepages', 'my_plugin_keywords');

		function my_plugin_keywords($hook, $type, $value, $params) {
			$value['my_plugin_keyword'] = array(
				'view' => 'my_plugin/keyword_view',
				'description' => 'Provides the awesome My Plugin keyword'
			);

			return $value;
		}


4.  HINTS AND QUIRKS

	* A custom keyword is slightly more complicated to implement, but is 
	much simpler for the end user to use.

	* Custom keywords can contain only alphanumeric and the underscore
	character.

	* All keywords have limited support for passing arguments but the arguments
	cannot contain '=' or ','.  If you need complicated arguments for a custom
	keyword, it's better to split the functionality into multiple keywords and
	views instead of requiring complicated arguments.
	
