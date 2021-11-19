Adding a Service to Elgg
########################

The :doc:`services guide </guides/services>` has general information about using Elgg services.

To add a new service object to Elgg:

#. Annotate your class as ``@internal`` if it is an internal service.
#. Open the class ``Elgg\Di\InternalContainer`` and/or ``Elgg\Di\PublicContainer``.
#. Add a ``@property-read`` annotation for your service at the top. This allows IDEs and static code
   analyzers to understand the type of the property when using ``_elgg_services()`` or ``elgg()``.
#. Register your service in ``engine\internal_services.php`` or ``engine\public_services.php`` using autowiring or with a factory.

Inject your dependencies
------------------------

Elgg uses PHP-DI for registering and resolving services. 
Dependencies can be autowired (based on the typehinted constructor argument services can be injected) or a service can be constructed in a factory. 

.. note::

    For more information about PHP-DI visit their `website`.
    
.. _website: https://php-di.org/


Making a service part of the public API
---------------------------------------

If your service is meant for use by plugin developers:

#. Make an interface ``Elgg\Services\<Name>`` that contains only those methods needed in the public API.
#. Have your service class implement that interface.
#. For methods that are in the interface, move the documentation to the interface. You can simply use
   ``{@inheritdoc}`` in the PHPDocs of the concrete class methods.
#. Document your service in ``docs/guides/services.rst`` (this file).
#. Open the PHPUnit test ``Elgg\ApplicationTest`` and add your service key to the ``$names`` array
   in ``testServices()``.
#. Open the class ``Elgg\Application``.
#. Add ``@property-read`` declaration to document your service, but use your **interface** as the type,
   *not* your service class name.

Now your service will be available via property access on the ``Elgg\Application`` instance:

.. code-block:: php

    // using the public foo service
    $three = elgg()->foo->add(1, 2);

.. note::

    For examples, see the ``config`` service, including the interface ``Elgg\Services\Config``
    and the concrete implementation ``Elgg\Config``.

Service Life Cycle and Factories
================================

By default, services registered on the service provider are "shared", meaning the service provider
will store the created instance for the rest of the request, and serve that same instance to all
who request the property.

If you need developers to be able to construct objects that are pre-wired to Elgg services, you may
need to add a public factory method to ``Elgg\Application``. Here's an example that returns a new
instance using internal Elgg services:

.. code-block:: php

    public function createFoo($bar) {
        $logger = $this->services->logger;
        $db = $this->services->db;
        return new Elgg\Foo($bar, $logger, $db);
    }
