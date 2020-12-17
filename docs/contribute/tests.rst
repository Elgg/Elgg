Writing tests
#############

.. contents:: Contents
   :local:
   :depth: 2

Vision
======

We want to *make manual testing unnecessary* for core developers, plugin authors, and site administrators by promoting and enabling fast, 
automated testing at every level of the Elgg stack.

We look forward to a world where the core developers do not need to do any manual testing to verify the correctness of code contributed to Elgg. 
Similarly, we envision a world where site administrators can upgrade and install new plugins with confidence that everything works well together.

Running Tests
=============

Elgg Core Test Suite
--------------------

Currently our tests are split in two pieces:
 * PHPUnit tests are located in ``/tests/phpunit`` -- these are split between unit tests and integration tests.

Since we have a ``phpunit.xml`` configuration at the root of Elgg, testing should be as easy as:

.. code-block:: sh

	git clone http://github.com/Elgg/Elgg
	cd Elgg
	phpunit

Plugin tests
------------

Ideally plugins are configured in such a way that they can be unit-tested much like Elgg core. Plugin developers are free to implement their 
own methods for unit testing, but we encourage everyone to make it as easy as Elgg core:

.. code-block:: sh

	git clone http://github.com/developer/elgg-plugin plugin
	cd plugin
	phpunit

End-to-end tests
----------------

Since Elgg plugins have so much power to override, filter, and modify Elgg's and other plugins' behavior, it's important to be able to run 
end-to-end tests on a staging server with your final configuration before deploying to production.

.. note::
	
	ToDo: Make it easy to run all Elgg integration and acceptance tests from the admin area given the current plugin configuration.
	(without worrying about database corruption!)

Motivation
==========

Briefly, the wins we expect from testing are:
 * Increased confidence in the system.
 * More freedom to refactor.
 * Built-in, up-to-date documentation.

We love community contributions, but in order to maintain stability we cannot accept outside contributions without first verifying their 
correctness. By promoting automated testing, the core developers can avoid the hassle of manual verification before accepting patches. It also 
means that external developers don't have to spend time earning trust with the core team. If a patch comes in and has tests to verify it, then we 
can be confident it works without worrying about the reputation of the contributor. 

Note that these benefits can also extend to the plugins repository. Site owners are encouraged to "test plugins thoroughly" before deploying them 
on a production site. As of March 2013, this translates to manually verifying all the features that the plugin promises to offer. But Elgg provides 
a huge number of features, and it's not reasonable to test for *all* of them on *every browser* you want to support on *every device* you want to 
support. But what if plugin developers could write tests for their plugins and site owners could just run the tests for all installed plugins to 
verify the functionality is maintained? Then they wouldn't be limited to just picking plugins from "trusted" developers or "stable" releases. 
They could see that, indeed, nothing broke when they upgraded that critical plugin from 1.3 to 2.5, and push the upgrade to production with 
confidence.

The reason this isn't happening today is because Elgg itself is not easily testable at this level yet. We want to change that.

Strategy
========

We have several guiding principles that we think will be helpful in bringing our vision into reality.

In short, we are advocating:
 * Continuous integration -- if GitHub checks aren't happy, we're not happy
 * Dependency injection -- For creating highly testable, modular code
 * BDD -- Tests should verify features and provide documentation, not rehash the Class API

Continuous Integration
----------------------

We run all of our tests on GitHub Actions so that we can get real time feedback on the correctness of incoming pull requests and development as 
it progresses. **If the GitHub checks aren't passing, we don't commit to the repo.** This empowers us to merge pull requests in at a rapid pace, so long as 
they pass the tests. It also allows us to reject pull requests without detailed investigation if they do not pass the tests. We can get past 
the "does it work or not" question and talk about the things that humans need to talk about: API design, usefulness to the project, whether it 
belongs in core or a plugin, etc. We want as many features as possible provided by Elgg core to be verified automatically by tests running on GitHub Actions.

Dependency Injection
--------------------

In order to maximize testability, **all dependencies need to be explicit**. Global functions, Singletons, and service locators are death for 
testability because it's impossible to tell what dependencies are hiding under the covers, and it's even harder to mock out those dependencies. 
Mocking is critical because you want your unit tests to test only one class at a time. Test failures in a TestCase should not result due to 
brokenness in a dependency; test failures should only indicate brokenness in the class under test. This makes everything much easier to debug. 
As of March 2013, most of Elgg still assumes and uses global state, and that has made Elgg and Elgg plugins historically very difficult to test. 
Fortunately we are moving in the opposite direction now, and a lot of work in Elgg 1.9 has gone into refactoring core components to be more 
dependency injectable. We are already reaping the benefits from that effort.

Behavior-Driven Development
---------------------------

For us this means **we name tests for features rather than methods**. When you test for features, you are encouraged to write fewer, smaller, 
logical tests. When a test fails, we can know exactly what feature is compromised. Furthermore, when naming your tests for features, the list of 
tests provides documentation on what features the system supports. Documentation is something that is typically very troublesome to keep up to 
date, but when documentation and verification are one and the same, it becomes very easy to keep the documentation up to date.

Consider these two test methods:
 * ``testRegister()``
 * ``testCanRegisterFilesAsActionHandlers()``

From just looking at the names, ``testRegister`` tells you that the class under test probably has a method named register. If this test passes, 
it presumably verifies that it is behaving correctly, but doesn't tell you what correct behavior entails, or what the original author of the test 
was intending to verify. If that method has multiple correct uses that you need to test for, this terse naming convention also encourages you to 
write a very long test which tests for all conditions and features of said method. Test failure could be caused by any one of those uses being 
compromised, and it will take more time to figure out where the true problem lies.

On the other hand, ``testCanRegisterFilesAsActionHandlers`` tells you that there are these things called "actions" that need to be "handled" and 
that files can be registered as valid handlers for actions. This introduces newcomers to project terminology and communicates clearly the intent 
of the test to those already familiar with the terminology.

For a good example of what we're looking for, check out ``/tests/phpunit/Elgg/ViewServiceTest.php``
