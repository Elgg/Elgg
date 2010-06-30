Embed plugin
(c) 2009 Curverider Ltd
Released under the GNU Public License version 2
http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

The embed plugin requires Elgg 1.5 (or prior to the Elgg 1.5
release, Elgg revision 2634 or above) and the file plugin.

To insert into the active editor, use elggEmbedInsert(html, textAreaName).

The default behavior searches for all textareas with name textAreaName and 
inserts the content into them.

If you need to use special embed code to insert content into a custom textarea
(like tinyMce, FCK, etc), extend (nb: EXTEND, not override) the embed/custom_insert_js
view with your custom JS.  The vars available to you are:
	str content The content to insert.
	str textAreaName The name of the textarea to receive the content.
	
It is important to correctly extend this view for compatibility across 
multiple plugins and textarea states.  Your custom JS should run without
error no matter plugin order or rich text editor status.  See TinyMCE as
an example of how to losely extend this function.