ECML - Elgg Customizable Markup Language

CONTENTS:
	1.  Overview
	2.  Security
	3.  Using ECML Keywords
		3.1  Utility keywords 'entity' and 'view'
		3.2  Embedded 3rd party media
	4.  Custom ECML Keywords
	5.  Hints and Quirks


1.  OVERVIEW

	ECML adds support for an extensible keyword	system that allows users
	to quickly add elements and embed media in their content.  The ECML
	control panel can be used to granularly allow ECML keywords in certain
	contexts and views.


2.  SECURITY

	From the ECML control panel in the administration section the
	administrator can select which sections of the site support
	ECML.  For each section registered to display ECML, the administrator
	can select which keywords to deny.  For example, this is useful to
	prevent users from inserting an Elgg view into a blog page.

3.  USING ECML KEYWORDS

	All ECML keywords are surrounded by two square brackets: [[ and ]].
	Some keywords, like views and entity lists, take optional parameters.

	Examples:
		[[userlist]] -- Display up to 10 newest users.
		[[youtube src="http://www.youtube.com/watch?v=kCpjgl2baLs"]] -- Embed a YouTube video.
		[[view src="input/text"]] -- Display a textarea


3.1  UTILITY KEYWORDS 'entity' AND 'view'

	ECML includes a few built-in keywords to get you started.  They are
	mainly for embedding content from 3rd party sites, but also include
	two utility views to help non-programmers quickly display content.

	The two utility keywords available are [[entity]] and [[view]].

	[[entity]] - Displays a list of entities using similar arguments to the
	elgg_get_entities() function.  See documentation for that function for
	the full list of supported arguments and default values.

	Additional / changed parameters supported by keywords:
		* owner: The username owner. (You can still use owner_guid)

	Example: Displays a list of all blog posts by the user named 'admin':
		[[entity type=object subtype=blog owner=admin]]

	Example: Displays newest group created:
		[[entity type=object subtype=group limit=1]]


	[[view]] - Displays a valid Elgg view passing any optional parameters to
	the view.

	Example: Display a text input:
		[[view src="input/text"]]

	Example: Display a textarea with a default value:
		[[view src="input/longtext" value="This is an example of a quoted value!"]]


3.2  EMBEDDED 3RD PARTY MEDIA

	ECML provides support for embedding media from a few of the most common
	media sites:

	* Youtube -- [[youtube src="URL"]]
	* Vimeo -- [[vimeo src="URL"]]
	* Slideshare -- [[slideshare id="ID"]] (NB: Use the "wordpress.com" embed
	link surrounded by two [[s and ]]s.)


4  CUSTOM ECML KEYWORDS (AKA "THE 'C' in ECML)

	Plugins can add their own ECML keywords.  Each keyword must be bound to
	a valid view.  Almost all functionality in custom keywords could be
	implemented using the 'view' keyword, but custom keywords provide a
	simple way for non-techy users to include ready-made views without
	the fuss of knowing what they're doing.

	To register your own ECML keywords, reply to the 'get_keywords'
	hook of type 'ecml' and append to the passed array with a key that is
	your keyword name and a value that is an array of a view, a description,
	and usage instructions.

	Optionally, the array can pass a 'restricted' => array() value of views
	that this keyword is valid in.  This is not overrideable by the admin
	interface and is useful for forcing security on possibly dangerous
	keywords.

	Arguments passed to the keyword are accessible to the keyword view via
	the $vars array.  It is	the responsibility of the custom view to parse
	these arguments.

	The below example creates the 'buttonizer' keyword that turns the user's
	text into an HTML button.  It uses the view at 'buttonizer/ecml/buttonizer.'

	How a user will call the keyword:

		[[buttonizer text="This is my button!"]]

	How to implement this in a plugin:

		buttonizer/start.php:
			register_plugin_hook('get_keywords', 'ecml', 'buttonizer_ecml_keywords');

			function buttonizer_ecml_keywords($hook, $type, $value, $params) {
				$value['buttonizer'] = array(
					'view' => 'buttonizer/ecml/buttonizer',
					'description' => 'Makes your text a button!  What could be better?',
					'usage' => 'Use [[buttonizer text="My text"]] to make "My text" a button!'
				);

				return $value;
			}

		buttonizer/views/default/buttonizer/ecml/buttonizer.php:
			$text = $vars['text'];

			echo elgg_view('input/button', array(
				'value' => $text,
				'type' => 'button'
			));

	This is exactly the same as saying:

		[[view src="buttonizer/ecml/buttonizer" text="This is my button!"]]

	or even:

		[[view src="input/button" value="This is my button!" type="button"]]

	but is much simpler for the user.


5.  HINTS AND QUIRKS

	* A custom keyword is slightly more complicated to implement, but is
	much simpler for the end user to use.

	* A custom keyword is safer.  Since ECML can be disabled for certain
	views, many administrators disable the entity and view keywords in
	user-accessible pages like blogs, pages, and files.

	* Custom keywords can contain only alphanumeric characters.

	* To pass a complex argument to a keyword, quote the value.

	* If making a custom keyword, avoid underscores in your params.  They
	look funny.

