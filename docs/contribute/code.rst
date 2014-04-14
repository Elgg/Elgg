Writing Code
############

Understand Elgg's standards and processes to get your changes accepted as quickly as possible.

License agreement
=================

By submitting a patch you are agreeing to license the code
under a `GPLv2 license`_ and `MIT license`_.

.. _GPLv2 license: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
.. _MIT license: http://en.wikipedia.org/wiki/MIT_License

Pull requests
=============

Pull requests are the best way to get code contributed to Elgg core.
The core development team uses them even for the most trivial changes.

For new features, `submit a feature request`_ or `talk to us`_ first and make
sure the core team approves of your direction before spending lots of time on code.

Good PR checklist:

-  Clear, meaningful title
-  Commit messages are in the standard commit message format
-  Detailed description
-  Includes relevant tests (unit, e2e, etc.)
-  Includes :doc:`documentation update <docs>`
-  Passes the continuous build
-  Is submitted against the correct branch:
   
   -  New features should be submitted against master. We do not introduce
      new features in bugfix branches.
   -  Bugfixes should be submitted against the latest non-master branch
      (unless the bug only appears in master).

.. _talk to us: http://community.elgg.org/groups/profile/211069/feedback-and-planning
.. _submit a feature request: :doc:`/contribute/issues`


Commit message format
=====================

Enforcing a particular format allows us to automatically build a changelog when it comes time to release.
This saves a lot of time and makes it possible to release more often.

All commit messages are required to adhere to the format below. Travis will complain if not.

.. code::
   
    type(component): summary
    
    Optional body
    Details about the solution.
    Opportunity to call out as breaking change.
    
    Closes/Fixes/Refs #123, #456, #789


Where ``type`` is one of:
 * feature
 * fix
 * docs (when *only* docs are being updated)
 * chore (refactoring, style changes, add missing tests, Travis stuff, etc.)
 * perf (any change expected to improve performance)
 * security (any change to affecting a security issue)

And ``component`` is one of:
 * i18n
 * seo
 * a11y
 * cache
 * db
 * views
 * session
 * router
 * etc...

All lines of the commit message must not be longer than 100 characters.

Here is an example of a good commit message:

.. code::

    perf(upgrade): speeds up migrating remember me codes
    
    The Remember me cookie implementation has been improved by moving codes
    to a separate table. This performs that migration in one query.
    
    Fixes #6204

To validate commit messages locally, copy or symlink the
``.scripts/validate_commit_msg.php`` to ``.git/hooks/commit-msg``
and make sure it's executable.


Coding best practices
=====================

Make your code easier to read, easier to maintain, and easier to debug.
Consistent use of these guidelines means less guess work for developers,
which means happier, more productive developers.


General coding
--------------

Don't Repeat Yourself
^^^^^^^^^^^^^^^^^^^^^

If you are copy-pasting code, you are doing something wrong.
If you find a block of code that you want to use multiple times, make a
function.  If you find views that are identical except for a single value,
pull it out into a generic view that takes an option.


Embrace SOLID and GRASP
^^^^^^^^^^^^^^^^^^^^^^^

These are `principles for OO design`__.

__ http://nikic.github.io/2011/12/27/Dont-be-STUPID-GRASP-SOLID.html

Whitespace is free
^^^^^^^^^^^^^^^^^^

Don't be afraid to use it to separate blocks of code.
Use a single space to separate function params and string concatenation.

Variable names
^^^^^^^^^^^^^^

Use self-documenting variable names.  ``$group_guids`` is better than ``$array``.

Avoid double-negatives. Prefer ``$enable = true`` to ``$disable = false``.

Functions
^^^^^^^^^
Where possible, have functions/methods return a single type.
Use empty values such as array(), "", or 0 to indicate no results.

Functions not throwing an exception on error should return FALSE upon failure.

Functions returning only boolean should be prefaced with ``is_`` or ``has_``
(eg, ``elgg_is_logged_in()``, ``elgg_has_access_to_entity()``).

Ternary syntax
^^^^^^^^^^^^^^

Acceptable only for single-line, non-embedded statements.

Use comments effectively
^^^^^^^^^^^^^^^^^^^^^^^^

Good comments describe the "why."  Good code describes the "how."  Ex:

Bad:

.. code:: php

	// increment $i only when the entity is marked as active.
	foreach($entities as $entity) {
		if ($entity->active == TRUE) {
			$i++;
		}
	}

Good:

.. code:: php

	// find the next index for inserting a new active entity.
	foreach($entities as $entity) {
		if ($entity->active == TRUE) {
			$i++;
		}
	}

Commit effectively
^^^^^^^^^^^^^^^^^^

Err on the side of atomic commits.
One revision with many changes is scary and difficult to review.


PHP guidelines
--------------

These are the required coding standards for Elgg core and all bundled plugins.
Plugin developers are strongly encouraged to adopt these standards.

Developers should first read the `PSR-2 Coding Standard Guide`__.

__ https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md

Elgg's standards differ from and extend PSR-2 in the following ways:

2.1 Basic Coding Standard

* 	Compliance with PSR-1 is encouraged, but not required.

2.3. Lines

* 	Consider refactoring code (e.g. introduce variables) if lines reach over
	100 characters.

2.4. Indenting

* 	Indent using one tab character, not spaces.

4.1. Extends and Implements

* 	Opening braces for classes must go on the same line.

4.3. Methods

* 	Opening braces for methods (and functions) must go on the same line.

The following is not mentioned in PSR-2

Documentation
^^^^^^^^^^^^^

* 	Include PHPDoc comments on functions and classes (all methods; declared
	properties when appropriate).

* 	Annotate classes, methods, properties, and functions with ``@access private``
	unless they are intended for public use, are already of limited visibility,
	or are within a class already marked as private.

* 	Use ``//`` or ``/* */`` when commenting.

* 	Use only ``//`` comments inside function/method bodies.

Naming
^^^^^^

* 	Use underscores to separate words in the names of functions, variables,
	and properties.

* 	Names of functions for public use must begin with ``elgg_``.

* 	All other function names must begin with ``_elgg_``.

* 	The names of all classes and interfaces must use underscores as namespace
	separators and be within the Elgg namespace. (``Elgg_Cache_LRUCache``)

* 	Name globals and constants in ``ALL_CAPS`` (``ACCESS_FRIENDS``, ``$CONFIG``).

Miscellaneous
^^^^^^^^^^^^^

* 	Use PHP 5.2-compatible syntax in Elgg versions before 1.10.

* 	Do not use PHP shortcut tags (``<?`` or ``<?=`` or ``<%``).

* 	When creating strings with variables, use double-quoted strings and wrap
	variables with braces only when necessary.

	Bad (hard to read, misuse of quotes and {}s):
	
	.. code:: php
	
		echo 'Hello, '.$name."!  How is your {$time_of_day}?";
		
	Good:
	
	.. code:: php
	
		echo "Hello, $name!  How is your $time_of_day?"; 


CSS guidelines
--------------

Use shorthand where possible
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Bad:

.. code:: css

	background-color: #333333;
	background-image:  url(...);
	background-repeat:  repeat-x;
	background-position:  left 10px;
	padding: 2px 9px 2px 9px;

Good:

.. code:: css

	background: #333 url(...) repeat-x left 10px;
	padding: 2px 9px;

Use hyphens, not underscores
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Bad:

.. code:: css

    .example_class {}

Good:

.. code:: css

    .example-class {}

One property per line
^^^^^^^^^^^^^^^^^^^^^

Bad:

.. code:: css

	color: white;font-size: smaller;

Good:

.. code:: css

	color: white;
	font-size: smaller;

Property declarations
^^^^^^^^^^^^^^^^^^^^^

These should be spaced like so: `property: value;`

Bad:

.. code:: css

		color:value;
		color :value;
		color : value;

Good:

.. code:: css

	color: value;

Vendor prefixes
^^^^^^^^^^^^^^^

 * Group vendor-prefixes for the same property together
 * Longest vendor-prefixed version first
 * Always include non-vendor-prefixed version
 * Put an extra newline between vendor-prefixed groups and other properties

Bad:

.. code:: css

	-moz-border-radius: 5px;
	border: 1px solid #999999;
	-webkit-border-radius: 5px;
	width: auto;

Good:

.. code:: css

	border: 1px solid #999999;

	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;

	width: auto;

Group subproperties
^^^^^^^^^^^^^^^^^^^

Bad:

.. code:: css

	background-color: white;
	color: #0054A7;
	background-position: 2px -257px;

Good:

.. code:: css

	background-color: white;
	background-position: 2px -257px;
	color: #0054A7;


Javascript guidelines
---------------------

Same formatting standards as PHP apply.

All functions should be in the elgg namespace.

Function expressions should end with a semi-colon.

.. code:: javascript

	elgg.ui.toggles = function(event) {
		event.preventDefault();
		$(target).slideToggle('medium');
	};


Deprecating APIs
================

Occasionally, functions and classes must be deprecated in favor of newer
replacements.  Since 3rd party plugin authors rely on a consistent API,
backward compatibility must be maintained, but will not be maintained
indefinitely as plugin authors are expected to properly update their
plugins.  In order to maintain backward compatibility, deprecated APIs will
follow these guidelines:

* 	Incompatible API changes cannot occur between bugfix versions
	(1.6.0 - 1.6.1).

* 	API changes between minor versions (1.6 - 1.7) must maintain backward
	compatibility for at least 2 minor versions.  (1.7 and 1.8. See
	procedures, below.)

* 	Bugfixes that change the API cannot be included in bugfix versions.

* 	API changes between major versions (1.0 - 2.0) can occur without regard to
	backward compatibility.

The procedure for deprecating an API is as follows:

* 	The first minor version (1.7) with a deprecated API must include a wrapper
	function/class (or otherwise appropriate means) to maintain backward
	compatibility, including any bugs in the original function/class.
	This compatibility layer uses elgg_deprecated_notice('...', 1.7) to log
	that the function is deprecated.

* 	The second minor version (1.8) maintains the backward compatibility
	layer, but elgg_deprecated_notice() will produce a visible warning.

* 	The third minor version (1.9) removes the compatibility layer.  Any use of
	the deprecated API should be corrected before this.

The general timeline for two minor releases is 8 to 12 months.
