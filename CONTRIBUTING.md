## DISCLAIMERS

 * **SECURITY ISSUES SHOULD BE REPORTED TO security @ elgg . org!** Please do not post any security issues on github.
 * Support requests belong on the [Community site][2]. Tickets with support requests will be closed. 
 * We cannot make any guarantees as to when your ticket will be resolved or your PR merged. 

## Pull requests

We love pull requests! Here's how to get your patch accepted as quickly as possible:

Before submitting a pull request:

 * **By submitting a pull request you are agreeing to license the code under a [GPLv2 license][3] and [MIT license][4].**
 * For new features, submit a feature request or [talk to us](http://community.elgg.org/groups/profile/211069/feedback-and-planning) and make sure the core team approves of your direction.

Good PR checklist:

 * Clear, meaningful title
 * Correctly formatted [commit message](https://github.com/Elgg/Elgg/issues/5976)
 * Detailed description
 * Includes relevant tests (unit, e2e, etc.)
 * Includes documentation update
 * Passes the continuous build
 * Is submitted against the correct branch:
   * New features should be submitted against master. We do not introduce new features in bugfix branches.
   * Bugfixes should be submitted against the latest non-master branch (unless the bug only appears in master).

## Bug reports

Before submitting a bug report:

 * Search for an existing ticket on the issue you're having. Add any extra info there.
 * Verify the problem is reproducible
   * On the latest version of Elgg
   * With all third-party plugins disabled

Good bug report checklist:

 * Expected behavior and actual behavior
 * Clear steps to reproduce the problem
 * The version of Elgg you're running
 * Browsers affected by this problem
 * Post bug report using [Github issues](https://github.com/Elgg/Elgg/issues)

## Feature requests

Before submitting a feature request:

 * Check the [community site][2] for a plugin that has the features you need.
 * Consider if you can [develop a plugin][8] that does what you need.
 * Search through the closed tickets to see if someone else suggested the same feature, but got turned down.
   You'll need to be able to explain why your suggestion should be considered this time.
   
Good feature request checklist:

 *  Detailed explanation of the feature
 *  Real-life use-cases
 *  Proposed API

 [2]: http://community.elgg.org
 [3]: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 [4]: http://en.wikipedia.org/wiki/MIT_License
 [6]: https://github.com/Elgg/Elgg/issues/new
 [7]: http://docs.elgg.org/wiki/Development/Contributing/Patches
 [8]: http://docs.elgg.org/wiki/Plugin_development  
 [9]: https://github.com/Elgg/Elgg/tree/master/docs/coding_standards
