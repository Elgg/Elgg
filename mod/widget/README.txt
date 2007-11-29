*Widget API*

The Elgg widget module is designed to have a very simple API with as much as
possible handled by the widget module itself.

If you want to create a plugin that supplies widgets, then your module needs
to provide two functions to generate HTML that displays and edits your
widgets:

module_widget_display($widget)

which displays the widget pointed to by $widget

and

module_widget_edit($widget)

which generates an edit form for the widget pointed to by $widget.

In both cases, "module" should be replaced by the name of your module.

Your edit form should not be a complete form - it can include some
introductory text and the fields you want to edit (with the names
widget_data[field_name], where "field_name" is the name of the field you want
to edit). It should not include a "form" tag or submit button - these will be
added automatically.

You can see examples of display and edit functions in the widget module itself
in mod/widget/lib.php. They, of course, are called widget_widget_display and
widget_widget_edit.

When you are creating the HTML to display and edit your widgets, you can use
the widget_get_data($field_name,$widget->ident) function to get any data
associated with your widget.

If you want to register your widgets publicly and allow users to add them to
their dashboards, then you can add a widget declaration to your module_init
function (replace "module" in module_init by the name of your module).

Here is the example from the widget module itself, which registers a simple
text widget.


$CFG->widgets->list[] = array(
        'name' => __gettext("Text box"),
        'description' => __gettext("Displays the text of your choice."),
        'type' => "text",
        'module' => "widget"
);

You can register multiple widget types for the same module and can access this
information through the $widget->widget_type field when displaying or editing
your widget.

If all you want to do is register widgets with the Elgg dashboard, then this
is probably all the API that you need to know.

*Dashboard-like Pages*

The widget system has more functionality that supports adding dashboard-like
pages to your own module.

If you want to display widgets on your own module page, then you need to
provide another function:

module_widget_display_url()

(where "module" is the name of your module), which returns the URL of the
page in your module responsible for displaying widgets.

Eg. $CFG->wwwroot.'mod/module/view_widgets.php'

You can use:

widget_create($module,$location,$location_id,$type,$owner,$access,$display_order)

to create your widget, where

- $module is the name of your module
- $location is an arbitrary string to describe the widget location
- $location_id is an arbitrary number to describe the widget location
(you can use location, location_id, or both, depending upon your
application)
- $type - the type of widget that you are creating

The remaining 3 parameters are optional:

- $owner is the user_id of the owner of the widget - if you do not provide
this or set it to 0, then the widget will be owned by the current logged in
user

- $access - defaults to 'PUBLIC'

- $display order - defaults to first if not provided or set to 0. You
can set this to a large number (eg. 10000) if you want this to be the
last widget displayed. The widgets are reshuffled as soon as they are 
inserted, so you can safely use 0 or 10000 each time you are creating a
widget and it will always go first or at the end respectively.

widget_create returns $widget->ident (the widget id).

Note: the Elgg dashboard uses location = "dashboard" and location_id = 0.
So creating a widget with these parameters is another way to place a 
widget on the owner's dashboard. Currently the dashboard system does
not use dashboards with location_id set to something other than 0, but
reserves the right to do so in future. So if you want to create your
own dashboard-like pages, use a location other than "dashboard".

To add or change widget data fields, you can use:

widget_set_data($field_name, $widget->ident, $field_value)

If the field_name record does not exist, it will be created when you set its
value.

To display widgets, you can use:

widget_page_display($owner,$location,$location_id,$count,$collapsed)

All the parameters are optional. widget_page_display() with no parameters
returns all of the widgets of the currently logged-in user.

If you set $owner to zero, the currently-logged-in user will be used as well.

$location and $location_id can be used to display widgets associated with
particular locations.

$count is used to determine the number of widgets that should be displayed per
page. Set this to 0 if you want all the widgets to be displayed on the same page.

$collapsed determines if you want the widgets to be displayed in collapsed
view (one line per widget). Set this to 1 if you want a collapsed view.

If you want to display collapsed widgets, your module needs to supply another
function,

module_widget_display_collapsed($widget)

which displays the collapsed widget pointed to by $widget

(where "module" should be replaced by the name of your module).

Keep in mind that various links to edit, delete and move your widget will
appear on the same line, so your actual collapsed display area needs to be
restricted to about 350 pixels or less.











