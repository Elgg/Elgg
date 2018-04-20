Error Handling
==============

Under the hood, Elgg uses Monolog_ for logging errors to the server's error log (and stdout for CLI commands).

.. _Monolog: https://github.com/Seldaek/monolog


Monolog_ comes with a number of tools that can help administrators keep track of errors and debugging information.

You can add custom handlers (see Monolog_ documentation for a full list of handlers):

.. code-block:: php

	// Add a new handler to notify a given email about a critical error
	elgg()->logger->pushHandler(
		new \Monolog\Handler\NativeMailerHandler(
			'admin@example.com',
			'Critical error',
			'no-reply@mysite.com',
			\Monolog\Logger::CRITICAL
		)
	);