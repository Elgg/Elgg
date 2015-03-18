CKEditor WYSIWYG plugin
========================

Configuration options
----------------------
CKEditor configuration defines toolbar options and skinning. A plugin can modify the
configuration object by extending (appending to) the view js/elgg/ckeditor/config and
rendering JavaScript code to alter the local variable "config".

The view output will be added to the view js/ckeditor.

See http://docs.ckeditor.com/#!/api/CKEDITOR.config for options documentation.

Content CSS
------------
The content CSS is stored in the view css/wysiwyg. This view is extended by the
css/elements/reset and css/elements/typography views so that content appears the
same when editing and viewing. It also contains all the CSS applied to the 
elgg-output class for the same reason (see the typography view).

Hints
------------
 * If your theme does not use borders with tables, enable the CKEditor showborders plugin.

