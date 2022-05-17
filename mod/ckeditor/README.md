CKEditor WYSIWYG plugin
========================

Configuration options
----------------------
CKEditor default configuration is set in the view `elgg/ckeditor/config.js`. 
A plugin can modify the configuration object by replacing the view, or by registering
a hook callback function to run on the `'config', 'ckeditor'` hook.
This is where toolbar options and the skin are set.

Content CSS
------------
The content CSS is stored in the view `elgg/wysiwyg.css`. This view is extended by the
`elements/reset.css` and `elements/typography.css` views so that content appears the
same when editing and viewing. It also contains all the CSS applied to the 
elgg-output class for the same reason (see the typography view).

Hints
------------
 * If your theme does not use borders with tables, enable the CKEditor showborders plugin.
