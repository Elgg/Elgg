Roadmap
#######

What direction is the project going? What exciting new features are coming soon?

We do not publish detailed roadmaps, but it’s possible to get a sense for our general direction
by utilizing the following resources:

* Our `feedback and planning group`_ is used to host early discussion about what will be worked on next.
* Our `Github milestones`_ represent a general direction for the future releases of Elgg.
  This is the closest thing to a traditional roadmap that we have.
* `Github pull requests`_ will give you a good idea of what’s currently being developed,
  but nothing is sure until the PR is actually checked in.
* We use the `developer blog`_ to post announcements of features that have recently been checked in to our development branch,
  which gives the surest indication of what features will be available in the next release.


.. _feedback and planning group: http://community.elgg.org/groups/profile/211069/feedback-and-planning
.. _Github milestones: https://github.com/Elgg/Elgg/issues/milestones
.. _Github pull requests: https://github.com/elgg/elgg/pulls
.. _developer blog: https://community.elgg.org/blog/all

Values
======

We have several overarching goals/values that affect the direction of Elgg.
Enhancements generally must promote these values in order to be accepted.

Accessibility
-------------

Elgg-based sites should be usable by anyone anywhere. That means we'll always strive to make Elgg:

 * Device-agnostic -- mobile, tablet, desktop, etc. friendly
 * Language-agnostic -- i18n, RTL, etc.
 * Capability-agnostic -- touch, keyboard, screen-reader friendly

Testability
-----------

We want to **make manual testing unnecessary** for core developers, plugin authors, and site administrators
by promoting and enabling fast, automated testing at every level of the Elgg stack.

We think APIs are broken if they require plugin authors to write untestable code.
We know there are a lot of violations of this principle in core currently and are working to fix it.

We look forward to a world where the core developers do not need to do any manual testing to verify the correctness of code contributed to Elgg.
Similarly, we envision a world where site administrators can upgrade and install new plugins with confidence that everything works well together.


TODO: other goals/values?

FAQ
===

When will feature X be implemented?
-----------------------------------
We cannot promise when features will get implemented because
new features are checked into Elgg only when someone is motivated enough
to implement the feature and submit a pull request.
The best we can do is tell you to look out for what features
existing developers have expressed interest in working on.

The best way to ensure a feature gets implemented is to discuss it with the core team and implement it yourself.
See our :doc:`/contribute/index` guide if you're interested. We love new contributors!

Do not rely on future enhancements if you're on the fence as to whether to use Elgg.
Evaluate it given the current feature set.
Upcoming features will almost certainly not materialize within your timeline.

When is version X.Y.Z going to be released?
-------------------------------------------
The next version will be released when the core team feels it's ready and has time to cut the release.
http://github.com/Elgg/Elgg/issues/milestones will give you some rough ideas of timeline.
