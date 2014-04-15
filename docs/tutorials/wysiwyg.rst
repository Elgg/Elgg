Integrating a Rich Text Editor
##############################

Build your own wysiwyg plugin.

Elgg is bundled with a plugin for CKEditor_, and previously shipped with TinyMCE_ support.
However, if you have a wysiwyg that you prefer, you could use this tutorial to help you build your own.

.. _CKEditor: http://ckeditor.com/
.. _TinyMCE: http://www.tinymce.com/

All forms in Elgg should try to use the provided input views located in ``views/default/input``.
If these views are used, then it is simple for plugin authors to replace a view,
in this case longtext.php, with their wysiwyg.

Create your plugin skeleton
---------------------------

You will need to create your plugin and give it a start.php file where the plugin gets initialized,
as well as a manifest.xml file to tell the Elgg engine about your plugin.

Read more in the guide about :doc:`/guides/plugins`.

Add the WYSIWYG library code
----------------------------

Now you need to upload TinyMCE into a directory in your plugin.
We strongly encourage you to put third party libraries in a “vendors” directory,
as that is standard practice in Elgg plugins and will make
your plugin much more approachable by other developers::

    mod/tinymce/vendors/tinymce/

Tell Elgg when and how to load TinyMCE
--------------------------------------

Now that you have:

 * created your start file
 * intialized the plugin
 * uploaded the wysiwyg code
 
It is time to tell Elgg how to apply TinyMCE to longtext fields.

We're going to do that by extending the input/longtext view and including some javascript.
Create a view tinymce/longtext and add the following code:

.. code:: php

    <?php

        /**
         * Elgg long text input with the tinymce text editor intacts
         * Displays a long text input field
         * 
         * @package ElggTinyMCE
         * 
         * 
         */

    ?>
    <!-- include tinymce -->
    <script language="javascript" type="text/javascript" src="<?php echo $vars['url']; ?>mod/tinymce/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
    <!-- intialise tinymce, you can find other configurations here http://wiki.moxiecode.com/examples/tinymce/installation_example_01.php -->
    <script language="javascript" type="text/javascript">
       tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo,link,unlink,image,blockquote,code",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
        extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],
    hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]"
    });
    </script>

Then, in your plugin's init function, extend the input/longtext view

.. code:: php

    function tinymce_init() {
        elgg_extend_view('input/longtext', 'tinymce/longtext');
    }

That's it! Now every time someone uses input/longtext,
TinyMCE will be loaded and applied to that textarea.