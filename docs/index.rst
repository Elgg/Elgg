Elgg Documentation (|version|)
##############################

Elgg_ (:download:`pronunciation <_elgg_files/How_to_say_Elgg.mp3>`) is an open source rapid development framework for socially aware web applications.
It is a great fit for building any app where users log in and share information.

Features
========

 * **Well-documented core API** that allows developers to kick start their new project with a simple learning curve
 * **Composer** is the package manager of choice that greatly simplifes installation and maintenance of Elgg core and plugins
 * **Flexible system of hooks and events** that allows plugins to extend and modify most aspects of application's functionality and behavior
 * **Extendable system of views** that allows plugins to collaborate on application's presentation layer and built out complex custom themes
 * **Cacheable system of static assets** that allows themes and plugins to serve images, stylesheets, fonts and scripts bypassing the engine
 * **User authentication** is powered by pluggable auth modules, which allow applications to implement custom authentication protocols
 * **Security** is ensured by built-in anti CSRF validation, strict XSS filters, HMAC signatures, latest cryptographic approaches to password hashing
 * **Client-side API** powered by asynchronous JavaScript modules via RequireJS and a build-in Ajax service for easy communication with the server
 * **Flexible entity system** that allows applications to prototype new types of content and user interactions
 * **Opinionated data model** with a consolidated API layer that allows the developers to easily interface with the database
 * **Access control system** that allows applications to build granular content access policies, as well as create private networks and intranets
 * **Groups** - out of the box support for user groups
 * **File storage** powered by flexible API that allows plugins to store user-generated files and serve/stream them without booting the engine
 * **Notifications service** that allows applications to subscribe users to on-site and email notifications and implement integrations with other their-party services
 * **RPC web services** that can be used for complex integrations with external applications and mobile clients
 * **Internationalization** and localization of Elgg applications is simple and can be integrated with third-party services such as Transifex
 * **Elgg community** that can help with any arising issues and hosts a repository of **1000+ open source plugins**

Under the hood:

 * Elgg is a modular OOP framework that is driven by DI services
 * NGINX or Apache compatible
 * Symfony2 HTTP Foundation handles requests and responses
 * RequireJS handles AMD
 * Zend Mail handles outgoing email
 * htmLawed XSS filters
 * DBAL
 * Phinx database migrations
 * CSS-Crush for CSS preprocessing
 * Imagine for image manipulation
 * Persistent caching with Memcached and/or Redis
 * Error handling with Monolog

Examples
========

It has been used to build `all kinds of social apps`__:

__ https://elgg.org/showcase

 * open networks (similar to Facebook)
 * topical (like the Elgg Community)
 * private/corporate intranets
 * dating
 * educational
 * company blog

This is the canonical documentation for the Elgg_ project.

.. _Elgg: http://elgg.org

Continue Reading
================

.. toctree::
	:maxdepth: 1

	intro/index
	admin/index
	guides/index
	tutorials/index
	design/index
	contribute/index
	appendix/index