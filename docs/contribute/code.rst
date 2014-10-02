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

Pull requests (PRs) are the best way to get code contributed to Elgg core.
The core development team uses them even for the most trivial changes.

For new features, `submit a feature request <issues.html>`__ or `talk to us`_ first and make
sure the core team approves of your direction before spending lots of time on code.

.. _talk to us: http://community.elgg.org/groups/profile/211069/feedback-and-planning


Checklists
----------

Use these markdown checklists for new PRs on github to ensure high-quality contributions
and help everyone understand the status of open PRs.

Bugfix PRs:

.. code::

 - [ ] Commit messages are in the standard format
 - [ ] Includes regression test
 - [ ] Includes documentation update (if applicable)
 - [ ] Is submitted against the correct branch
 - [ ] Has LGTM from at least one core developer

Feature PRs:

.. code::

 - [ ] Commit messages are in the standard format
 - [ ] Includes tests
 - [ ] Includes documentation
 - [ ] Is submitted against the correct branch
 - [ ] Has LGTM from at least two core developers


Choosing a branch to submit to
------------------------------

The following table assumes the latest stable release is 1.9.

============== ====================================
Type of change Branch to submit against
============== ====================================
Security fix   1.8 (Email security@elgg.org first!)
Bug fix        1.9
Deprecation    1.x
Minor feature  1.x
Major feature  master
Breaking       master
============== ====================================

The difference between minor and major feature is subjective and up to the core team.

Commit message format
---------------------

Here is an example of a good commit message:

.. code::

    perf(upgrade): speeds up migrating remember me codes

    The Remember me cookie implementation has been improved by moving codes
    to a separate table. This performs that migration in one query.

    Fixes #6204

All commit messages are required to adhere to this format:

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
* perf (when primary purpose of change is to improve performance)
* security (any change affecting a security issue)
* deprecate (any change that deprecates any part of the API)

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

To validate commit messages locally, copy or symlink the
``.scripts/validate_commit_msg.php`` to ``.git/hooks/commit-msg``
and make sure it's executable.

Enforcing a particular format allows us to automatically build a changelog when it comes time to release.
This saves a lot of time and makes it possible to release more often.

Rewriting commit messages
-------------------------
If your PR does not conform to the standard commit message format,
we'll ask you to rewrite it:

1. Rebase last commit (Git will open the git-rebase-todo file for editing)

   ``git rebase -i HEAD~``
2. Change ``pick`` to ``r`` (for reword) and save/exit the editor.
   (Git will present a file to alter the commit message)
3. Change the commit message, save/exit the editor.
4. Force push the branch to update your PR:

   ``git push -f your_remote your_branch``

Testing
=======

Elgg has automated tests for both PHP and JavaScript functionality.
All new contributions are required to come with appropriate tests.

PHPUnit Tests
-------------

TODO

Jasmine Tests
-------------

Test files must be named ``*Test.js`` and should go in either ``js/tests/`` or next
to their source files in ``views/default/js``. Karma will automatically pick up
on new ``*Test.js`` files and run those tests.

Test boilerplate
----------------

.. code:: js

	define(function(require) {
		var elgg = require('elgg');

		describe("This new test", function() {
			it("fails automatically", function() {
				expect(true).toBe(false);
			});
		});
	});

Running the tests
-----------------
Elgg uses `Karma`_ with `Jasmine`_ to run JS unit tests.

.. _Karma: http://karma-runner.github.io/0.8/index.html
.. _Jasmine: http://pivotal.github.io/jasmine/

You will need to have nodejs and npm installed.

First install all the development dependencies:

.. code::

   npm install

Run through the tests just once and then quit:

.. code::

   npm test

You can also run tests continuously during development so they run on each save:

.. code::

   karma start js/tests/karma.conf.js



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

Use these `principles for OO design`__ to solve problems using loosely coupled
components, and try to make all components and integration code testable.

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

Functions not throwing an exception on error should return ``false`` upon failure.

Functions returning only boolean should be prefaced with ``is_`` or ``has_``
(eg, ``elgg_is_logged_in()``, ``elgg_has_access_to_entity()``).

Ternary syntax
^^^^^^^^^^^^^^

Acceptable only for single-line, non-embedded statements.

Minimize complexity
~~~~~~~~~~~~~~~~~~~

Minimize nested blocks and distinct execution paths through code. Use
`Return Early`__ to reduce cognitive load when reading code.

__ http://www.mrclay.org/2013/09/18/when-reasonable-return-early/

Use comments effectively
^^^^^^^^^^^^^^^^^^^^^^^^

Good comments describe the "why."  Good code describes the "how."  Ex:

Bad:

.. code:: php

	// increment $i only when the entity is marked as active.
	foreach ($entities as $entity) {
		if ($entity->active) {
			$i++;
		}
	}

Good:

.. code:: php

	// find the next index for inserting a new active entity.
	foreach ($entities as $entity) {
		if ($entity->active) {
			$i++;
		}
	}

Commit effectively
^^^^^^^^^^^^^^^^^^

Err on the side of atomic commits and avoid mixing in extensive whitespace changes.
One revision with many changes is scary and difficult to review.

Include tests
~~~~~~~~~~~~~

When at all possible include unit tests for code you add or alter. We use:

* PHPUnit for PHP unit tests.

* SimpleTest for PHP tests that require use of the database. Our long-term goal
  is to move all tests to PHPUnit.

* Karma for JavaScript unit tests

Naming tests
~~~~~~~~~~~~

Break tests up by the behaviors you want to test and use names that describe the
behavior. E.g.:

* Not so good: One big method `testAdd()`.

* Better: Methods `testAddingZeroChangesNothing` and `testAddingNegativeNumberSubtracts`

Keep bugfixes simple
~~~~~~~~~~~~~~~~~~~~

Avoid the temptation to refactor code for a bugfix release. Doing so tends to
introduce regressions, breaking functionality in what should be a stable release.

PHP guidelines
--------------

These are the required coding standards for Elgg core and all bundled plugins.
Plugin developers are strongly encouraged to adopt these standards.

Developers should first read the `PSR-2 Coding Standard Guide`__.

__ https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md

Elgg's standards extend PSR-2, but differ in the following ways:

* Indent using one tab character, not spaces.
* Opening braces for classes, methods, and functions must go on the same line.
* If a line reaches over 100 characters, consider refactoring (e.g. introduce variables).
* Compliance with `PSR-1`__ is encouraged, but not strictly required.

__ https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md

Documentation
^^^^^^^^^^^^^

* Include PHPDoc comments on functions and classes (all methods; declared
  properties when appropriate), including types and descriptions of all
  parameters.

* In lists of ``@param`` declarations, the beginnings of variable names and
  descriptions must line up.

* Annotate classes, methods, properties, and functions with ``@access private``
  unless they are intended for public use, are already of limited visibility,
  or are within a class already marked as private.

* Use ``//`` or ``/* */`` when commenting.

* Use only ``//`` comments inside function/method bodies.

Naming
^^^^^^

* Use underscores to separate words in the names of functions, variables,
  and properties. Method names are camelCase.

* Names of functions for public use must begin with ``elgg_``.

* All other function names must begin with ``_elgg_``.

* The names of all classes and interfaces must use underscores as namespace
  separators and be within the Elgg namespace. (``Elgg_Cache_LRUCache``)

* Name globals and constants in ``ALL_CAPS`` (``ACCESS_FRIENDS``, ``$CONFIG``).

Miscellaneous
^^^^^^^^^^^^^

Use PHP 5.2-compatible syntax in Elgg versions before 1.10.

Do not use PHP shortcut tags (``<?`` or ``<?=`` or ``<%``).

When creating strings with variables:

* use double-quoted strings
* wrap variables with braces only when necessary.

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

All functions should be in the ``elgg`` namespace.

Function expressions should end with a semi-colon.

.. code:: js

	elgg.ui.toggles = function(event) {
		event.preventDefault();
		$(target).slideToggle('medium');
	};


Deprecating APIs
================

Occasionally, functions and classes must be deprecated in favor of newer replacements.
Since 3rd party plugin authors rely on a consistent API,
backward compatibility must be maintained,
but will not be maintained indefinitely as
plugin authors are expected to properly update their plugins.
In order to maintain backward compatibility,
deprecated APIs will follow these guidelines:

* The first minor version (1.7) with a deprecated API must include a wrapper
  function/class (or otherwise appropriate means) to maintain backward compatibility,
  including any bugs in the original function/class.
  This compatibility layer uses ``elgg_deprecated_notice('...', '1.7')``
  to log that the function is deprecated.

* The following minor versions (1.8+) maintain the backward compatibility layer,
  but ``elgg_deprecated_notice()`` will produce a visible warning.

* The next major revision (2.0) removes the compatibility layer.
  Any use of the deprecated API should be corrected before this.
