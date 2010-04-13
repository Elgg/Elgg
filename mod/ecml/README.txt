ECML - Elgg Custom Markup Language

CONTENTS:
	1.  Overview
	2.  Using ECML Keywords
		2.1  Built-in keywords
		2.2  Entities
		2.3  Views
	3.  Custom ECML Keywords
	4.  Hints and Quirks


1.  OVERVIEW

	ECML adds support for an extensible keyword	system that allows users
	to quickly add elements and embed media in their content.  The ECML
	control panel can be used to granualarly allow ECML keywords in certain
	contexts and views.


2.  USING ECML KEYWORDS

	All ECML keywords are surrounded by two square brackets: [[ and ]].
	Some keywords, like views and entity lists, take optional parameters.

	Example:
		[[user_list]] -- Display up to 10 newest users.

		[[user_list: list_type=online]] -- Display up to 10 active users.

		[[user_list: list_type=online, only_with_avatars=true]] -- Display
			up to 10 active users who have uploaded avatars.


2.1  BUILT-IN KEYWORDS

	ECML includes a few built-in keywords to get you started:
		[[entity]] - Displays a list of users.

		[[view]] - Shows the total members in your site, the currently
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

