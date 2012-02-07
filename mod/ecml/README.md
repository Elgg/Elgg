Elgg Customizable Markup Language
=================================

Overview
---------------
ECML is a lightweight markup language for inserting content into posts and comments.
It uses square brackets to set off its tags like so:

```
[youtube src="http://www.youtube.com/watch?v=kCpjgl2baLs"]
```

Each tag consists of a keyword and an optional set of attributes. This plugin is
responsible for parsing the tags. Other plugins handle rendering specific tags
into HTML.

Developers
---------------
A plugin can register for a custom keyword by registering for the 'render:&lt;keyword&gt;',
'ecml' plugin hook where &lt;keyword&gt; is the name of the keyword. Any attributes
are passed in the $params array for the plugin hook. The handler is expected to use
the keyword and attributes to generate HTML and return it to replace the tag in the
final web page.
