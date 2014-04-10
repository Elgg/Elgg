Contributing
############

Participating in making Elgg great.

Report bugs
===========

See https://github.com/elgg/elgg/blob/master/CONTRIBUTING.md

Submit Pull Requests
====================

See https://github.com/elgg/elgg/blob/master/CONTRIBUTING.md


Write Documentation
===================

New documentation should fit stylistically with the rest of Elgg's docs.


Follow the existing document organization
-----------------------------------------
It's not necessarily the One True Way to organize the docs, but consistency is better than randomness.


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

about/*
^^^^^^^
More detailed/meta/background information about the project (history, roadmap, contributing, etc.)



Use "Elgg" in a grammatically correct way
-----------------------------------------
Elgg is not an acronym, so writing it in all caps (ELGG or E-LGG) is incorrect. Please don’t do this.

In English, Elgg does not take an article when used as a noun. Here are some examples to emulate:
 * “I’m using Elgg to run my website”
 * “Install Elgg to get your community online”

When used as an adjective, the article applies to the main noun, so you should use one. For example:
 * "Go to the Elgg community website to get help."
 * "I built an Elgg-based network yesterday"

This advice may not apply in languages other than English.


Avoid first person pronouns
---------------------------
Refer to the reader as “you.” Do not include yourself in the normal narrative.

Before:

    When we’re done installing Elgg, we’ll look for some plugins!

After:

    When you’re done installing Elgg, look for some plugins!

To refer to yourself (avoid this if possible), use your name and write in the third person.
This clarifies to future readers/editors whose opinions are being expressed.

Before:

    I think the best way to do X is to use Y.

After:

    Evan thinks the best way to do X is to use Y.


Eliminate fluff
---------------

Before:

    If you want to use a third-party javascript library within the Elgg framework, you should take care to call the ``elgg_register_js`` function to register it.

After:

    To use a third-party javascript library, call ``elgg_register_js`` to register it.


Do not remind the reader to contribute
--------------------------------------
Focus on addressing the topic at hand. Do not solicit bug fixes.
If they want to contribute to the project, they will come to this section to learn how.
Our users don't want to be constantly reminded how needy we feel.


Prefer absolute dates over relative ones
----------------------------------------
It is not easy to tell when a particular sentence or paragraph was written, so relative dates quickly become meaningless.
Absolute dates also give the reader a good indication of whether a project has been abandoned, or whether some advice might be out of date.

Before:

    Recently the foo was barred. Soon, the baz will be barred too.

After:

    Recently (as of September 2013), the foo was barred.
    The baz is expected to be barred by October 2013.


Become a supporter
==================

Benefits
--------
For only $50 per year for individuals or $150 per year for organizations,
you can get listed as a supporter on `our supporters page`_.
Elgg supporters are listed there unless they request not to be.

.. _our supporters page: http://elgg.org/supporters.php

All funds raised via the Elgg supporters network go directly into core Elgg development
and infrastructure provision (elgg.org, github, etc.).
It is a great way to help with Elgg development!

Supporters are able to put this official logo on their site if they wish:

.. image:: elgg-supporters.gif
   :alt: Elgg Supporter


Disclaimer
----------
We operate a no refund policy on supporter subscriptions.
If you would like to withdraw your support, go to PayPal and cancel your subscription.
You will not be billed the following year.

Being an Elgg Supporter does not give an individual or organization the right to impersonate,
trade as or imply they are connected to the Elgg project.
They can, however, mention that they support the Elgg project.

If you have any questions about this disclaimer, email info@elgg.org.

We reserve the right to remove or refuse a listing without any prior warning at our complete discretion.
There is no refund policy.

If there is no obvious use of Elgg, your site will be linked to with "nofollow" set.

Sign up
-------
If you would like to become an Elgg supporter:

 * read the disclaimer_ above
 * on the supporters page, `subscribe via PayPal`__
 * send an email to info@elgg.org with:
 
   * the date you subscribed
   * your name (and organization name, if applicable)
   * your website
   * your Elgg community profile
 

__ http://elgg.org/supporter.php

Once all the details have been received, we will add you to the appropriate list. Thanks for your support!
