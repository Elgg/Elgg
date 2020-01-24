Writing Documentation
=====================

New documentation should fit well with the rest of Elgg's docs.

.. contents:: Contents
   :local:
   :depth: 1

Testing docs locally
--------------------
Elgg has a `grunt`_ script that automatically builds the docs, opens them in a browser
window, and automatically reloads as you make changes (the reload takes just a few
seconds). You need `yarn`_ and `sphinx`_ installed to be able to use these scripts.

.. code-block:: sh

   cd path/to/elgg/
   yarn
   grunt

It's that easy! Grunt will continue running, watching the docs for changes and
automatically rebuilding.

.. note::
	
	You might need to install 'sphinxcontrib-phpdomain'. You can do this with the following command:
	`pip install -U sphinxcontrib-phpdomain`

.. _grunt: http://gruntjs.com/
.. _yarn: https://yarnpkg.com/
.. _sphinx: http://www.sphinx-doc.org/

Follow the existing document organization
-----------------------------------------
The current breakdown is not necessarily the One True Way to organize docs,
but consistency is better than randomness.


intro/*
^^^^^^^
This is everything that brand new users need to know (installation, features, license, etc.)

admin/*
^^^^^^^
Guides for administrators. Task-oriented.

guides/*
^^^^^^^^
API guides for plugin developers. Cookbook-style. Example heavy. Code snippet heavy.
Broken down by services (actions, i18n, routing, db, etc.).
This should only discuss the public API and its behavior, not implementation details or reasoning.

design/*
^^^^^^^^
Design docs for people who want to get a better understanding of how/why core is built the way it is.
This should discuss internal implementation details of the various services, what tradeoffs were made,
and the reasoning behind the final decision. Should be useful for people who want to contribute and
for communication b/w core devs.

contribute/*
^^^^^^^^^^^^
Contributors guides for the various ways people can participate in the project.

appendix/*
^^^^^^^^^^
More detailed/meta/background information about the project (history, roadmap, etc.)


Use "Elgg" in a grammatically correct way
-----------------------------------------
Elgg is not an acronym, so writing it in all caps (ELGG or E-LGG) is incorrect. Please don't do this.

In English, Elgg does not take an article when used as a noun. Here are some examples to emulate:
 * "I'm using Elgg to run my website"
 * "Install Elgg to get your community online"

When used as an adjective, the article applies to the main noun, so you should use one. For example:
 * "Go to the Elgg community website to get help."
 * "I built an Elgg-based network yesterday"

This advice may not apply in languages other than English.


Avoid first person pronouns
---------------------------
Refer to the reader as "you". Do not include yourself in the normal narrative.

Before:

    When we're done installing Elgg, we'll look for some plugins!

After:

    When you're done installing Elgg, look for some plugins!

To refer to yourself (avoid this if possible), use your name and write in the third person.
This clarifies to future readers/editors whose opinions are being expressed.

Before:

    I think the best way to do X is to use Y.

After:

    Evan thinks the best way to do X is to use Y.


Eliminate fluff
---------------

Before:

    If you want to use a third-party javascript library within the Elgg framework, you should take care to call the ``elgg_register_external_file`` function to register it.

After:

    To use a third-party javascript library, call ``elgg_register_external_file`` to register it.


Prefer absolute dates over relative ones
----------------------------------------
It is not easy to tell when a particular sentence or paragraph was written, so relative dates quickly become meaningless.
Absolute dates also give the reader a good indication of whether a project has been abandoned, or whether some advice might be out of date.

Before:

    Recently the foo was barred. Soon, the baz will be barred too.

After:

    Recently (as of September 2013), the foo was barred.
    The baz is expected to be barred by October 2013.

Do not remind the reader to contribute
--------------------------------------
Focus on addressing only the topic at hand.
Constant solicitation for free work is annoying and makes the project look needy.
If people want to contribute to the project, they can visit the contributor guide.


Internationalizing documentation
================================

When you change documentation, remember to update the documentation translation
templates before you commit:

.. code-block:: sh

   cd docs/
   make gettext

For more information, see
http://www.sphinx-doc.org/en/stable/intl.html#translating-with-sphinx-intl

Special attention
-----------------

When translating the documentation be aware of special syntax in the documentation files.

Translating links
^^^^^^^^^^^^^^^^^

 * Translate text in anonymous links (e.g., ```pronunciation`__``), but maintain the order of all anonymous links in a single block. If there are two anonymous links within a single block for translation, they must not be rearranged relative to each other.
 * Translate the text of named links (e.g., ```demo site`_``) but only if you maintain the name using the correct rST syntax. In this case that would be ```translation of "demo site" <demo site_>`_``.

Do NOT translate
^^^^^^^^^^^^^^^^

 * Anything between pipe characters should not be translated (e.g., |version|).
 * Code, unless it's a comment in the code.
