Writing Code
############

Understand Elgg's standards and processes to get your changes accepted as quickly as possible.

.. contents:: Contents
   :local:
   :depth: 1

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

For new features, `submit a feature request <https://github.com/Elgg/Elgg/issues>`__ or `talk to us`_ first and make
sure the core team approves of your direction before spending lots of time on code.

.. _talk to us: http://community.elgg.org/groups/profile/211069/feedback-and-planning

Checklists
----------

Use these markdown checklists for new PRs on github to ensure high-quality contributions
and help everyone understand the status of open PRs.

Bugfix PRs:

.. code-block:: text

 - [ ] Commit messages are in the standard format
 - [ ] Includes regression test
 - [ ] Includes documentation update (if applicable)
 - [ ] Is submitted against the correct branch
 - [ ] Has LGTM from at least one core developer

Feature PRs:

.. code-block:: text

 - [ ] Commit messages are in the standard format
 - [ ] Includes tests
 - [ ] Includes documentation
 - [ ] Is submitted against the correct branch
 - [ ] Has LGTM from at least two core developers


Choosing a branch to submit to
------------------------------

The following table assumes the latest stable release is 2.1.

============================== ============================================
Type of change                 Branch to submit against
============================== ============================================
Security fix                   Don't! Email security@elgg.org for guidance.
Bug fix                        1.12 (or 2.1 if the 1.12 fix is too complex)
Performance                    2.x
Deprecation                    2.x
Minor feature                  2.x
Major feature                  master
Has any breaking change        master
============================== ============================================

If you're not sure which branch to submit against, just ask!

The difference between minor and major feature is subjective and up to the core team.

Commit message format
---------------------

We require a particular format to allow releasing more often, and with improved changelogs and source history. Just
follow these steps:

1. Start with the ``type`` by selecting the *last category which applies* from this list:

   * **docs** - *only* docs are being updated
   * **chore** - this include refactoring, code style changes, adding missing tests, CI stuff, etc.
   * **perf** - the primary purpose is to improve performance
   * **fix** - this fixes a bug
   * **deprecate** - the change deprecates any part of the API
   * **break** - the change breaks any part of the API
   * **feature** - this adds a new user-facing or developer feature
   * **removed** - this removes a user-facing or developer feature
   * **security** - the change affects a security issue in any way. *Please do not push this commit to any public repo.* Instead contact security@elgg.org.

   E.g. if your commit refactors to fix a bug, it's still a "fix". If that bug is security-related, however, the type
   must be "security" and you should email security@elgg.org before proceeding. When in doubt, make your best guess
   and a reviewer will provide guidance.

2. In parenthesis, add the ``component``, a short string which describes the subsystem being changed.

   Some examples: ``views``, ``i18n``, ``seo``, ``a11y``, ``cache``, ``db``, ``session``, ``router``, ``<plugin_name>``.

3. Add a colon, a space, and a brief ``summary`` of the changes, which will appear in the changelog.

   No line may exceed 100 characters in length, so keep your summary concise.

   ================================================ ======================================================================================================
   Good summary                                     Bad summary (problem)
   ================================================ ======================================================================================================
   page owners see their own owner blocks on pages  bug fix (vague)
   bar view no longer dies if 'foo' not set         updates views/default/bar.php so bar view no longer... (redundant info)
   narrows river layout to fit iPhone               alters the river layout (vague)
   elgg_foo() handles arrays for $bar               in elgg_foo() you can now pass an array for $bar and the function will... (move detail to description)
   removes link color from comments header in river fixes db so that... (redundant info)
   requires non-empty title when saving pages       can save pages with no title (confusingly summarizes old behavior)
   ================================================ ======================================================================================================

4. (recommended) Skip a line and add a ``description`` of the changes. Include the motivation for making them, any info
   about back or forward compatibility, and any rationale of why the change had to be done a certain way. Example:

       We speed up the Remember Me table migration by using a single INSERT INTO ... SELECT query instead of row-by-row.
       This migration takes place during the upgrade to 1.9.

   Unless your change is trivial/obvious, a description is required.

5. If the commit resolves a GitHub issue, skip a line and add ``Fixes #`` followed by the issue number. E.g.
   ``Fixes #1234``. You can include multiple issues by separating with commas.

   GitHub will auto-close the issue when the commit is merged. If you just want to reference an issue, use
   ``Refs #`` instead.

When done, your commit message will have the format:

.. code-block:: text

	type(component): summary

	Optional body
	Details about the solution.
	Opportunity to call out as breaking change.

	Closes/Fixes/Refs #123, #456, #789


Here is an example of a good commit message:

.. code-block:: text

    perf(upgrade): speeds up migrating remember me codes

    We speed up the Remember Me table migration by using a single INSERT INTO ... SELECT query instead of row-by-row.
    This migration takes place during the upgrade to 1.9.

    Fixes #6204

Rewriting commit messages
-------------------------
If your PR does not conform to the standard commit message format, we'll ask you to rewrite it.

To edit just the last commit:

1. Amend the commit: ``git commit --amend`` (git opens the message in a text editor).
2. Change the message and save/exit the editor.
3. Force push your branch: ``git push -f your_remote your_branch`` (your PR with be updated).
4. Rename the PR title to match

Otherwise you may need to perform an interactive rebase:

1. Rebase the last N commits: ``git rebase -i HEAD~N`` where N is a number.
   (Git will open the ``git-rebase-todo`` file for editing)
2. For the commits that need to change, change ``pick`` to ``r`` (for reword) and save/exit the editor.
3. Change the commit message(s), save/exit the editor (git will present a file for each commit that needs rewording).
4. ``git push -f your_remote your_branch`` to force push the branch (updating your PR).
5. Rename the PR title to match


.. _contribute/code#standards:

Coding Standards
================

Elgg uses set of standards that are based partially on PEAR and PSR2 standards. You can view the ruleset in ``vendor/elgg/sniffs/elgg.xml``.

To check your code for standard violations (provided you have installed Elgg with dev dependencies), run:

.. code-block:: sh

    phpcs --standard=vendor/elgg/sniffs/elgg.xml -s path/to/dir/to/check


To automatically fix fixable violations, run:

.. code-block:: sh

    phpcbf --standard=vendor/elgg/sniffs/elgg.xml path/to/dir/to/fix


.. _contribute/code#testing:

Testing
=======

Elgg has automated tests for both PHP and JavaScript functionality. All new contributions are required to come with appropriate tests.

.. seealso::

	:doc:`tests`

General guidelines
------------------

Break tests up by the behaviors you want to test and use names that describe the behavior. E.g.:

* Not so good: One big method ``testAdd()``.

* Better: Methods ``testAddingZeroChangesNothing`` and ``testAddingNegativeNumberSubtracts``

Strive for :ref:`componentized designs <contribute/code#solid>` that allow testing in isolation, without large dependency graphs or DB access. Injecting dependencies is key here.

PHP Tests
---------

PHPUnit
^^^^^^^

Located in ``engine/tests/phpunit``, this is our preferred test suite. It uses no DB access, and has only superficial access to the entities API.

* We encourage you to create components that are testable in this suite if possible.
* Consider separating storage from your component so at least business logic can be tested here.
* Depend on the ``Elgg\Filesystem\*`` classes rather than using PHP filesystem functions.

Testing interactions between services
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Ideally your tests would construct your own isolated object graphs for direct manipulation, but this isn't always possible.

If your test relies on Elgg's Service Provider (``_elgg_services()`` returns a ``Elgg\Di\ServiceProvider``), realize that it maintains a singleton instance for most services it hands out, and many services keep their own local references to these services as well.

Due to these local references, replacing services on the SP within a test often will not have the desired effect. Instead, you may need to use functionality baked into the services themselves:

* The ``events`` and ``hooks`` services have methods ``backup()`` and ``restore()``.
* The ``logger`` service has methods ``disable()`` and ``enable()``.

Jasmine Tests
-------------

Test files must be named ``*Test.js`` and should go in either ``js/tests/`` or next
to their source files in ``views/default/**.js``. Karma will automatically pick up
on new ``*Test.js`` files and run those tests.

Test boilerplate
----------------

.. code-block:: js

	define(['elgg'], function(elgg) {
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

You will need to have nodejs and yarn installed.

First install all the development dependencies:

.. code-block:: sh

   yarn

Run through the tests just once and then quit:

.. code-block:: sh

   yarn test

You can also run tests continuously during development so they run on each save:

.. code-block:: sh

   karma start js/tests/karma.conf.js

Debugging JS tests
^^^^^^^^^^^^^^^^^^

You can run the test suite inside Chrome dev tools:

.. code-block:: sh

   yarn run chrome

This will output a URL like ``http://localhost:9876/``.

#. Open the URL in Chrome, and click "Debug".
#. Open Chrome dev tools and the Console tab.
#. Reload the page.

If you alter a test you'll have to quit Karma with ``Ctrl-c`` and restart it.

Coding best practices
=====================

Make your code easier to read, easier to maintain, and easier to debug.
Consistent use of these guidelines means less guess work for developers,
which means happier, more productive developers.


General coding
--------------

Don't Repeat Yourself
^^^^^^^^^^^^^^^^^^^^^

If you are copy-pasting code a significant amount of code, consider whether there's an opportunity to reduce
duplication by introducing a function, an additional argument, a view, or a new component class.

E.g. If you find views that are identical except for a single value, refactor into a single view
that takes an option.

**Note:** In a bugfix release, *some duplication is preferrable to refactoring*. Fix bugs in the simplest
way possible and refactor to reduce duplication in the next minor release branch.

.. _contribute/code#solid:

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

Interface names
^^^^^^^^^^^^^^^

Use the pattern ``Elgg\{Namespace}\{Name}``.

Do not include an ``I`` prefix or an ``Interface`` suffix.

We do not include any prefix or suffix so that we're encouraged to:

 * name implementation classes more descriptively (the "default" name is taken).
 * type-hint on interfaces, because that is the shortest, easiest thing to do.

Name implementations like ``Elgg\{Namespace}\{Interface}\{Implementation}``.

Functions
^^^^^^^^^

Where possible, have functions/methods return a single type.
Use empty values such as ``array()``, ``""``, or ``0`` to indicate no results.

Be careful where valid return values (like ``"0"``) could be interpreted as empty.

Functions not throwing an exception on error should return ``false`` upon failure.

.. note::

	Particularly low-level, non-API functions/methods (e.g. entity_row_to_elggstar),
	which should not fail under normal conditions, should throw instead of returning false. 

Functions returning only boolean should be prefaced with ``is_`` or ``has_``
(eg, ``elgg_is_logged_in()``, ``elgg_has_access_to_entity()``).

Ternary syntax
^^^^^^^^^^^^^^

Acceptable only for single-line, non-embedded statements.

Minimize complexity
^^^^^^^^^^^^^^^^^^^

Minimize nested blocks and distinct execution paths through code. Use
`Return Early`__ to reduce nesting levels and cognitive load when reading code.

__ http://www.mrclay.org/2013/09/18/when-reasonable-return-early/

Use comments effectively
^^^^^^^^^^^^^^^^^^^^^^^^

Good comments describe the "why."  Good code describes the "how." E.g.:

Bad:

.. code-block:: php

	// increment $i only when the entity is marked as active.
	foreach ($entities as $entity) {
		if ($entity->active) {
			$i++;
		}
	}

Good:

.. code-block:: php

	// find the next index for inserting a new active entity.
	foreach ($entities as $entity) {
		if ($entity->active) {
			$i++;
		}
	}

Always include a comment if it's not obvious that something must be done in a certain way. Other
developers looking at the code should be discouraged from refactoring in a way that would break the code.

.. code-block:: php

    // Can't use empty()/boolean: "0" is a valid value
    if (elgg_is_empty($str)) {
        throw new \Elgg\Exceptions\Http\BadRequestException(elgg_echo('foo:string_cannot_be_empty'));
    }

Commit effectively
^^^^^^^^^^^^^^^^^^

* Err on the side of `atomic commits`__ which are highly focused on changing one aspect of the system.
* Avoid mixing in unrelated changes or extensive whitespace changes. Commits with many changes are scary and
  make pull requests difficult to review.
* Use visual git tools to craft `highly precise and readable diffs`__.

__ http://en.wikipedia.org/wiki/Atomic_commit#Atomic_Commit_Convention
__ http://www.mrclay.org/2014/02/14/gitx-for-cleaner-commits/

Include tests
~~~~~~~~~~~~~

When at all possible :ref:`include unit tests <contribute/code#testing>` for code you add or alter.

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

* Annotate classes, methods, properties, and functions with ``@internal``
  unless they are intended for public use, are already of limited visibility,
  or are within a class already marked as ``@internal``.

* Use ``//`` or ``/* */`` when commenting.

* Use only ``//`` comments inside function/method bodies.

Naming
^^^^^^

* Use underscores to separate words in the names of functions, variables,
  and properties. Method names are camelCase.

* Names of functions for public use must begin with ``elgg_``.

* All other function names must begin with ``_elgg_``.

* Name globals and constants in ``ALL_CAPS`` (``ACCESS_PUBLIC``, ``$CONFIG``).

Miscellaneous
^^^^^^^^^^^^^

For PHP requirements, see ``composer.json``.

Do not use PHP shortcut tags ``<?`` or ``<%``.
It is OK to use ``<?=`` since it is always enabled as of PHP 5.4.

When creating strings with variables:

* use double-quoted strings
* wrap variables with braces only when necessary.

Bad (hard to read, misuse of quotes and {}s):

.. code-block:: php

	echo 'Hello, '.$name."!  How is your {$time_of_day}?";

Good:

.. code-block:: php

	echo "Hello, $name!  How is your $time_of_day?";

Remove trailing whitespace at the end of lines.

Value validation
^^^^^^^^^^^^^^^^

When working with user input prepare the input outside of the validation method.

Bad:

.. code-block:: php

	function validate_email($email) {
		$email = trim($email);

		// validate
	}

	$email = get_input($email);

	if (validate_email($email)) {
		// the validated email value is now out of sync with an actual input
	}

Good:

.. code-block:: php

	function validate_email($email) {
		// validate
	}

	$email = get_input($email);
	$email = trim($email);

	if (validate_email($email)) {
		// green light
	}

Use exceptions
^^^^^^^^^^^^^^

Do not be afraid to use exceptions. They are easier to deal with than mixed function output:

Bad:

.. code-block:: php

	/**
	* @return string|bool
	*/
	function validate_email($email) {
		if (empty($email)) {
			return 'Email is empty';
		}

		// validate

		return true;
	}

Good:

.. code-block:: php

	/**
	* @return void
	* @throws \Elgg\Exceptions\InvalidArgumentException
	*/
	function validate_email($email) {
		if (empty($email)) {
			throw new \Elgg\Exceptions\InvalidArgumentException('Email is empty');
		}

		// validate and throw if invalid
	}

Documenting return values
^^^^^^^^^^^^^^^^^^^^^^^^^

Do not use ``@return void`` on methods that return a value or null.

Bad:

.. code-block:: php

	/**
	* @return bool|void
	*/
	function validate_email($email) {
		if (empty($email)) {
			return;
		}

		// validate

		return true;
	}

Good:

.. code-block:: php

	/**
	* @return bool|null
	*/
	function validate_email($email) {
		if (empty($email)) {
			return null;
		}

		// validate

		return true;
	}


CSS guidelines
--------------

Save the css in files with a ``.css`` extension.

Use shorthand where possible
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Bad:

.. code-block:: css

	background-color: #333333;
	background-image:  url(...);
	background-repeat:  repeat-x;
	background-position:  left 10px;
	padding: 2px 9px 2px 9px;

Good:

.. code-block:: css

	background: #333 url(...) repeat-x left 10px;
	padding: 2px 9px;

Use hyphens, not underscores
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Bad:

.. code-block:: css

    .example_class {}

Good:

.. code-block:: css

    .example-class {}
    
.. note::

	You should prefix your ids and classnames with text that identifies your plugin.

One property per line
^^^^^^^^^^^^^^^^^^^^^

Bad:

.. code-block:: css

	color: white;font-size: smaller;

Good:

.. code-block:: css

	color: white;
	font-size: smaller;

Property declarations
^^^^^^^^^^^^^^^^^^^^^

These should be spaced like so: ``property: value;``

Bad:

.. code-block:: css

	color:value;
	color :value;
	color : value;

Good:

.. code-block:: css

	color: value;

Vendor prefixes
^^^^^^^^^^^^^^^

 * Group vendor-prefixes for the same property together
 * Longest vendor-prefixed version first
 * Always include non-vendor-prefixed version
 * Put an extra newline between vendor-prefixed groups and other properties

Bad:

.. code-block:: css

	-moz-border-radius: 5px;
	border: 1px solid #999999;
	-webkit-border-radius: 5px;
	width: auto;

Good:

.. code-block:: css

	border: 1px solid #999999;

	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;

	width: auto;

Group subproperties
^^^^^^^^^^^^^^^^^^^

Bad:

.. code-block:: css

	background-color: white;
	color: #0054A7;
	background-position: 2px -257px;

Good:

.. code-block:: css

	background-color: white;
	background-position: 2px -257px;
	color: #0054A7;
	


Javascript guidelines
---------------------

Same formatting standards as PHP apply.

All functions should be in the ``elgg`` namespace.

Function expressions should end with a semi-colon.

.. code-block:: js

	elgg.ui.toggles = function(event) {
		event.preventDefault();
		$(target).slideToggle('medium');
	};


Deprecating APIs
================

Occasionally functions and classes must be deprecated in favor of newer
replacements. Since 3rd party plugin authors rely on a consistent API,
backward compatibility must be maintained, but will not be maintained
indefinitely as plugin authors are expected to properly update their plugins.
In order to maintain backward compatibility, deprecated APIs will follow
these guidelines:

* Minor version (1.x) that deprecates an API must include a wrapper
  function/class (or otherwise appropriate means) to maintain backward
  compatibility, including any bugs in the original function/class.
  This compatibility layer uses ``elgg_deprecated_notice('...', '1.11')``
  to log that the function is deprecated.

* The next major revision (2.0) removes the compatibility layer.
  Any use of the deprecated API should be corrected before this.
