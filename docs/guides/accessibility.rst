Accessibility
=============

This page aims to list and document accessibility rules and best practices, to help core and plugins developpers to make Elgg the most accessible social engine framework that everyone dreams of.

.. note::

   This is an ongoing work, please contribute on `Github <https://github.com/Elgg/Elgg>`_ if you have some skills in this field!

Resources + references
----------------------

- `Official WCAG Accessibility Guidelines Overview <https://www.w3.org/WAI/standards-guidelines/wcag/glance/>`_
- `Official WCAG Accessibility Guidelines <https://www.w3.org/TR/WCAG/>`_
- `Resources for planning and implementing for accessibility <https://www.w3.org/WAI/planning/>`_
- `Practical tips from the W3C for improving accessibility <https://www.w3.org/WAI/planning/interim-repairs/>`_
- `Preliminary review of websites for accessibility <https://www.w3.org/WAI/test-evaluate/preliminary/>`_
- `Tools for checking the accessibility of websites <https://www.w3.org/WAI/ER/tools/>`_
- `List of practical techniques for implementing accessibility <https://www.w3.org/TR/WCAG20-TECHS/Overview.html#contents>`_ (It would be great if someone could go through this and filter out all the ones that are relevant to Elgg)

Tips for implementing accessibility
-----------------------------------

- All accessibility-related tickets reported to trac should be tagged with "a11y", short for "accessibility"
- Use core views such as ``output/*``, and ``input/*`` to generate markup, since we can bake a11y concerns into these views
- All images should have a descriptive ``alt`` attribute. Spacer or purely decorative graphics should have blank ``alt`` attributes
- All ``<a>`` tags should have text or an accessible image inside. Otherwise screen readers will have to read the URL, which is a poor experience ``<a>`` tags should contain descriptive text, if possible, as opposed to generic text like "Click here"
- Markup should be valid
- Themes should not reset "outline" to nothing. ``:focus`` deserves a special visual treatment so that handicapped users can know where they are

Tips for testing accessibility
------------------------------

- Use the tools linked to from the resources section. `Example report for community.elgg.org on June 16, 2012 <http://try.powermapper.com/Reports/a6276098-0883-4d04-849e-8c05999812f2/report/map.htm>`_
- Try different font-size/zoom settings in your browser and make sure the theme remains usable
- Turn off css to make sure the sequential order of the page makes sense

Documentation objectives and principles
---------------------------------------

- Main accessibility rules
- collect and document best practices
- Provide code examples
- Keep the document simple and usable
- Make it usable for both beginner developpers and experts (from most common and easiest changes to elaborate techniques)