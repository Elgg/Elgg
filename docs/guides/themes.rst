Themes
######

Customize the look and feel of Elgg.

A theme is a type of :doc:`plugin </admin/plugins>` that overrides display aspects of Elgg.

This guide assumes you are familiar with:
 * :doc:`/admin/plugins`
 * :doc:`views`

.. contents:: Contents
	:local:
	:depth: 2

Theming Principles and Best Practices
=====================================

**No third-party CSS frameworks**
	Elgg does not use a CSS framework, because such frameworks lock users into a specific HTML markup, which in the end makes it much harder for plugins to collaborate on the appearance.
	What's `is-primary` in one theme, might be something else in the other. Having no framework allows plugins to alter appearance using pure css, without having to overwrite views and
	append framework-specific selectors to HTML markup elements.

.. code-block:: html

	/* BAD */
	<div class="box has-shadow is-inline">
		This is bad, because if the plugin wants to change the styling, it will have to either write really specific css
		clearing all the attached styles, or replace the view entirely just to modify the markup
	</div>

	/* GOOD */
	<div class="box-role">
		This is good, because a plugin can just simply add .box-role rule
	</div>
	<style>
		.box-role {
		 padding: 1rem;
		 display: inline-block;
		 box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
		}
	</style>


**8-point grid system**
	Elgg uses an `8-point grid system <https://builttoadapt.io/intro-to-the-8-point-grid-system-d2573cde8632>`, so sizing of elements, their padding, margins etc is done in increments and fractions of `8px`.
	Because our default font-size is ``16px``, we use fractions of `rem`, so ``0.5rem = 8px``.
	8-point grid system makes it a lot easier for developers to collaborate on styling elements: we no longer have to think if the padding should be ``5px`` or ``6px``.

.. code-block:: css

	/* BAD */
	.menu > li {
		margin: 2px 2px 2px 0;
	}

	.menu > li > a {
		padding: 3px 5px;
	}

	/* GOOD */
	.menu > li > a {
		padding: 0.25rem 0.5rem;
	}


**Mobile first**
	We write mobile-first CSS.
	We use two breakpoints: ``50rem`` and ``80rem`` (800px and 1280px at 16px/rem).

.. code-block:: css

	/* BAD: mobile defined in media blocks, different display types */

	.menu > li {
		display: inline-block;
	}
	@media screen and (max-width: 820px) {
		.menu > li {
			display: block;
			width: 100%;
		}
	}

	/* GOOD: mobile by default. Media blocks style larger viewports. */

	.menu {
		display: flex;
		flex-direction: column;
	}
	@media screen and (min-width: 50rem) {
		.menu {
			flex-direction: row;
		}
	}


**Flexbox driven**
	Flexbox provides simplicity in stacking elements into grids. Flexbox is used for everything from menus to layout elements.
	We avoid ``float`` and ``clearfix`` as they are hard to collaborate on and create lots of room for failure and distortion.

.. code-block:: css

	/* BAD */
	.heading:after {
		visibility: hidden;
		height: 0;
		clear: both;
		content: " ";
	}
	.heading > h2 {
		float: left;
	}
	.heading > .controls {
		float: right;
	}

	/* GOOD */
	.heading {
		display: flex;
		justify-content: flex-end;
	}
	.heading > h2 {
		order: 1;
		margin-right: auto;
	}
	.heading > .controls {
		order: 2;
	}

**Symmetrical**
	We maintain symmetry.

.. code-block:: css

	/* BAD */
	.row .column:first-child {
		margin-right: 10px;
	}

	/* GOOD */
	.row {
		margin: 0 -0.5rem;
	}
	.row .column {
		margin: 0.5rem;
	}

**Simple color transitions**
	We maintain 4 sets of colors for text, background and border: ``soft``, ``mild``, ``strong`` and ``highlight``.
	When transitioning to hover or active state, we go one level up, e.g. from ``soft`` to ``mild``, or use ``highlight``.
	When transition to inactive or disabled state, we go one level down.

**Increase the click area**
	When working with nested anchors, we increase the click area of the anchor, rather than the parent

.. code-block:: css

	/* BAD */
	.menu > li {
		margin: 5px;
		padding: 5px 10px;
	}

	/* GOOD */
	.menu > li {
		margin: 0.5rem;
	}
	.menu > li > a {
		padding: 0.5rem 1rem;
	}

**No z-index 999999**
	z-indexes are incremented with a step of 1.

**Wrap HTML siblings**
	We make sure that there are no orphaned strings within a parent and that siblings are wrapped in a way that they can be targeted by CSS.

.. code-block:: html

	/* BAD */
	<label>
		Orphan
		<span>Sibling</span>
	</label>

	/* GOOD */
	<label>
		<span>Sibling</span>
		<span>Sibling</span>
	</label>


.. code-block:: html

	/* BAD */
	<div>
		<h3>Title</h3>
		<p>Subtitle</p>
		<div class="right">This goes to the right</div>
	</div>

	/* GOOD */
	<div>
		<div class="left">
		 <h3>Title</h3>
		 <p>Subtitle</p>
		</div>
		<div class="right">This goes to the right</div>
	</div>


Create your plugin
==================

Create your plugin as described in the :doc:`developer guide </guides/index>`.

-  Create a new directory under mod/
-  Create a new elgg-plugin.php
-  Create a composer.json file describing your theme.

Customize the CSS
=================

The css is split into several files based on what
aspects of the site you're theming. This allows you to tackle them one
at a time, giving you a chance to make real progress without getting
overwhelmed.

Here is a list of the existing CSS views:

 * elements/buttons.css: Provides a way to style all the different kinds of buttons your site will use. There are 5 kinds of buttons that plugins will expect to be available: action, cancel, delete, submit, and special.
 * elements/chrome.css: This file has some miscellaneous look-and-feel classes.
 * elements/components.css: This file contains many “css objects” that are used all over the site: media block, list, gallery, table, owner block, system messages, river, tags, photo, and comments.
 * elements/forms.css: This file determines what your forms and input elements will look like.
 * elements/icons.css: Contains styles for the icons and avatars used on your site.
 * elements/layout.css: Determines what your page layout will look like: sidebars, page wrapper, main body, header, footer, etc.
 * elements/modules.css: Lots of content in Elgg is displayed in boxes with a title and a content body. We called these modules. There are a few kinds: info, aside, featured, dropdown, popup, widget. Widget styles are included in this file too, since they are a subset of modules.
 * elements/navigation.css: This file determines what all your menus will look like.
 * elements/typography.css: This file determines what the content and headings of your site will look like.
 * rtl.css: Custom rules for users viewing your site in a right-to-left language.
 * admin.css: A completely separate theme for the admin area (usually not overridden).
 * elgg.css: Compiles all the core elements/\* files into one file (DO NOT OVERRIDE).
 * elements/core.css: Contains base styles for the more complicated “css objects”. If you find yourself wanting to override this, you probably need to report a bug to Elgg core instead (DO NOT OVERRIDE).
 * elements/reset.css: Contains a reset stylesheet that forces elements to have the same default

CSS variables
-------------

Elgg uses CssCrush for preprocessing CSS files. This gives us the flexibility of using global CSS variables.
Plugins should, wherever possible, use global CSS variables, and extend the core theme with their plugin variables, so they
can be simply altered by other plugins.

To add or alter variables, use the ``vars:compiler, css`` hook. Note that you may need to flush the cache to see your
changes in action.

For a list of default core variables, see ``engine/theme.php``.

.. _guides/theming#css-vars:

View extension
--------------

There are two ways you can modify views:

The first way is to add extra stuff to an existing view via the ``views_extensions``
section within your elgg-plugin.php definition.

For example, the following configuration will add mytheme/css to Elgg's core css file:

.. code-block:: php

	<?php
		return [
			'view_extensions' => [
				'elgg.css' => [
					'mytheme/css' => [],
				],
			],
		];

View overloading
----------------

Plugins can have a view hierarchy, any file that exists here will
replace any files in the existing core view hierarchy... so for example,
if my plugin has a file:

``/mod/myplugin/views/default/elements/typography.css``

it will replace:

``/views/default/elements/typography.css``

But only when the plugin is active.

This gives you total control over the way Elgg looks and behaves. It
gives you the option to either slightly modify or totally replace
existing views.

Icons
-----

As of Elgg 2.0 the default Elgg icons come from the FontAwesome_ library. 
You can use any of these icons by calling:  

``elgg_view_icon('icon-name');``

``icon-name`` can be any of the `FontAwesome icons`_ without the ``fa-``-prefix.

By default you will get the solid styled variant of the icons. Postfixing the icon name with ``-solid``, ``-regular`` or ``-light`` allows you to target a specific style.
Be advised; the light styled variant is only available as a FontAwesome Pro licensed icon.

.. _FontAwesome: http://fontawesome.io/
.. _FontAwesome icons: http://fontawesome.io/icons/

Tools
=====

We've provided you with some development tools to help you
with theming: Turn on the “Developers” plugin and go to the “Theme
Preview” page to start tracking your theme's progress.

Customizing the front page
==========================
The main Elgg index page is served via a resource view.

Therefore, you can override it by providing your own resource file in ``your_plugin/views/default/resources/index.php``.
