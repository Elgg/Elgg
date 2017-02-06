CKEditor WYSIWYG plugin
========================

Configuration options
----------------------
CKEditor configuration is set in the view "elgg-ckeditor.js". The configuration object
is `elgg.ckeditor.config`. A plugin can modify the configuration object by registering
a function to run before the ckeditor.init function on the `'init', 'system'` hook.
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

