# Elgg's Views System

The view system is the primary templating engine in Elgg and renders
all output.  Views are short, parameterized PHP scripts for displaying
output that can be regsitered, overridden, or extended.  The view type
determines the output format and location of the files that renders the view.

Elgg uses a two step process to render full output
to make it easy to maintain a consistent look on all pages:

 1. content-specific elements are rendered
 2. the resulting content is inserted into a layout and displayed.

A view corresponds to a single file on the filesystem and the view's
name is determined by its directory structure.  A file in
`mod/plugins/views/default/myplugin/example.php` is called by saying
(with the default viewtype): `echo elgg_view('myplugin/example');`.

View names that are registered later override those that are registered earlier.
For plugins this corresponds directly to their load order; views in plugins lower
in the list override those higher in the list.

Plugin views belong in the `views/` directory under an appropriate viewtype.
Views are automatically registered.

Views can be embedded-you can call a view from within a view.
Views can also be prepended or extended by any other view.

Any view can extend any other view if registered with
{@link elgg_extend_view()}.

Viewtypes are set by passing $_REQUEST['view'].  The viewtype
'default' is a standard HTML view.  Types can be defined on the fly
and you can get the current viewtype with {@link elgg_get_viewtype()}.

@note Internal: Plugin views are autoregistered before their init functions
are called, so the init order doesn't affect views.

@note Internal: The file that determines the output of the view is the last
registered by {@link elgg_set_view_location()}.
