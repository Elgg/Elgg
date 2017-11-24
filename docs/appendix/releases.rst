Release Policy
##############

What to expect when upgrading Elgg.

We adhere to `semantic versioning`_.

.. _semantic versioning: http://semver.org

Follow the blog to `stay up to date on the latest releases`__.

__ https://community.elgg.org/blog/all

Patch/Bugfix Releases (2.1.x)
-----------------------------
Every two weeks.

Bugfix releases are made regularly to make sure Elgg stays stable, secure, and bug-free.
The higher the third digit, the more tested and stable the release is.

Since bugfix release focus on fixing bugs and not making major changes,
themes and plugins should work from bugfix release to bugfix release.


Minor/Feature Releases (2.x.0)
------------------------------
Every three months.

Whenever we introduce new features, we'll bump the middle version number.
These releases aren't as mature as bugfix release, but are considered stable and useable.

We make every effort to be backward compatible in these releases,
so plugins should work from minor release to minor release.

However, plugins might need to be updated to make use of the new features.


Major/Breaking Releases (x.0.0)
-------------------------------
Every year.

Inevitably, improving Elgg requires breaking changes and a new major release is made.
These releases are opportunities for the core team to make strategic, breaking changes to the underlying platform.
Themes and plugins from older versions are not expected to work without modification on different major releases.

We may remove deprecated APIs, but we will not remove APIs without first deprecating them.

Elgg's dependencies may be upgraded by their major version or removed entirely.
We will not remove any dependences before a major release, but we do not "deprecate"
dependencies or issue any warnings before removing them.

Your package, plugin, or app should declare its own dependencies directly so that
this does not cause a problem.

Alphas, Betas, and Release Candidates
-------------------------------------

Before major releases (and sometimes before feature releases), the core team will
offer a pre-release version of Elgg to get some real-world testing and feedback
on the release. These are meant for testing only and should not be used on a live
site.

SemVer 2.0 does not define a particular meaning for pre-releases, but we approach
alpha, beta, and rc releases with these general guidelines:

An ``-alpha.X`` pre-release means that there are still breaking changes planned,
but the feature set of the release is frozen. No new features or breaking changes
can be proposed for that release.

A ``-beta.X`` pre-release means that there are no known breaking changes left to
be included, but there are known regressions or critical bugs left to fix.

An ``-rc.X`` pre-release means that there are no known regressions or critical
bugs left to be fixed. This version could become the final stable version of
Elgg if no new blockers are reported.

Backwards compatibility
-----------------------

Some parts of the system need some additional clarification if we are talking about being backwards compatible.
Everything that is considered public API needs to adhere to the backwards compatibility rules that are part of `semantic versioning`_.

Views
=====

 - View names are API.
 - View arguments ($vars array) are API.
 - Removing views or renaming views follows API deprecation policies.
 - Adding new views requires a minor version change.
 - View output is not API and can be changed between patch releases.
