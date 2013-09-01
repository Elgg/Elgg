Elgg JavaScript Unit Tests
==========================

Elgg uses [Karma](http://karma-runner.github.io/0.8/index.html) with
[Jasmine](http://pivotal.github.io/jasmine/) to run JS unit tests.

Installation
------------

Install nodejs.

Install karma and phantomjs:

```
npm install -g karma
npm install -g phantomjs
```

Run the test suite
------------------

```
karma start js/tests/karma.conf.js --single-run
```

Run the tests on each file change
---------------------------------

```
karma start js/tests/karma.conf.js
```

Adding tests
------------
Test files must be named `*Test.js` and should go in either `js/tests/` or next
to their source files in `views/default/js`. Karma will automatically pick up
on new *Test.js files and run those tests. 

Test boilerplate
----------------

```js
define(function(require) {
	
	var elgg = require('elgg');
	
	describe("This new test", function() {
		it("fails automatically", function() {
			expect(true).toBe(false);		
		});
	});
});
```

Testing plugins
---------------
TODO
