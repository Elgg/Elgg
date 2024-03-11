Temporary Bin
#############

The Temporary Bin temporarily stores content that has been deleted from a site.
When an entity with the soft_deletable capability is deleted, it is marked as soft deleted in the database and hidden from view.

.. contents:: Contents
   :local:
   :depth: 1

Database changes
----------------

To accomodate the temporary bin, new columns have been added to the Elgg database.
The Entities and the Annotations tables have been given two extra columns:

-  **soft\_deleted** If this is 'yes' an entity is marked for soft deletion, 
   if 'no' (default) the entity is visibile within the regular site.
   If the bin plugin is enabled, soft deleted content is stored in the Temporary Bin.
-  **time\_soft\_deleted** Unix timestamp of when the entity was soft deleted.

Columns have been added in the migration file ``/engine/schema/migrations/20230606155735_add_columns_to_entities_and_annotations_tables.php``.


Defining an entity as soft deletable
------------------------------------

Entities can be eligible for soft deletion by giving them the relevant capability.
The entities with soft_deletable capability are as follows:

 - group
 - blog
 - bookmarks
 - file
 - page

This can be set ``/mod/{entity}/elgg-plugin.php``. Like in Blog, in the following example:

.. code-block:: php

    <?php
    return [
        'entities' => [
            [
                'type' => 'object',
                'subtype' => 'blog',
                'class' => 'ElggBlog',
                'capabilities' => [
                    'commentable' => true,
                    'searchable' => true,
                    'likable' => true,
                    'soft_deletable' => true
                ],
            ],
        ]
    ]

Giving an entity the soft_deletable capability will reroute its ``delete()`` action 
to the ``softDelete()`` action in ``/actions/entity/delete.php``.

.. code-block:: php

    <?php

    $soft_deletable_entities = elgg_entity_types_with_capability('soft_deletable');


    $non_recursive_delete = (bool) get_input('recursive', true);
    if ($entity->soft_deleted === 'no' && $entity->hasCapability('soft_deletable')) {
	    if (!$entity->softDelete($deleter_guid)) {
		    return elgg_error_response(elgg_echo('entity:delete:fail', [$display_name]));
	    }
    } else {
	    if (!$entity->delete($non_recursive_delete)) {
		    return elgg_error_response(elgg_echo('entity:delete:fail', [$display_name]));
	    }
    }

This will check to see if an entity can and is not already soft deleted. If so, it will set the **soft\_deleted** column
in the database to 'yes' and update the value of **time\_soft\_deleted** to a Unix timestamp of the current time.

Otherwise it will delete the entity with a ``delete()`` action, either recursively or non-recursively.

Soft Delete
-----------

The ``softDelete()`` method of ``/engine/classes/ElggEntity.php/`` is responsible for the 
actual database update of the **soft\_deleted** and **time\_soft\_deleted** columns. These are set to 'yes' and to a current Unix
timestamp, respectively.

.. code-block:: php

    <?php
	$guid = (int) $this->guid;

	if ($recursive) {
		elgg_call(ELGG_IGNORE_ACCESS | ELGG_HIDE_DISABLED_ENTITIES, function () use ($deleter_guid, $guid) {
			$base_options = [
				'wheres' => [
					function(QueryBuilder $qb, $main_alias) use ($guid) {
						return $qb->compare("{$main_alias}.guid", '!=', $guid, ELGG_VALUE_GUID);
					},
				],
				'limit' => false,
				'batch' => true,
				'batch_inc_offset' => false,
			];

			foreach (['owner_guid', 'container_guid'] as $db_column) {
				$options = $base_options;
				$options[$db_column] = $guid;

				$subentities = elgg_get_entities($options);
				/* @var $subentity \ElggEntity */
				foreach ($subentities as $subentity) {
					$subentity->addRelationship($guid, 'soft_deleted_with');
					get_entity($deleter_guid)->addRelationship($subentity->guid, 'deleted_by');
					$subentity->softDelete($deleter_guid, true);
				}
			}
		});
	}

	get_entity($deleter_guid)->addRelationship($this->guid, 'deleted_by');

	$this->disableAnnotations();

	$soft_deleted = _elgg_services()->entityTable->softDelete($this);

	$this->updateTimeSoftDeleted();

	if ($soft_deleted) {
		$this->invalidateCache();

		$this->attributes['soft_deleted'] = 'yes';

		_elgg_services()->events->triggerAfter('soft_delete', $this->type, $this);
	}

	return $soft_deleted;
	}

If ``$recurvise`` is true, base options for retrieving subentities linked to the entity are setup. 
Iterations over the columns 'owner_guid' and 'container_guid' are done and ``elgg_get_entities()`` 
is called to find linked subentities to the current entity based on the options set. For each found subentity,
``soft_deleted_with`` and ``deleted_by`` relationships to the current entity and logged in user are added. 
The **soft\_deleted** and **time\_soft\_deleted** values of linked subentities and the entity itself are then updated
and the ``soft_deleted`` attribute set.


Temporary Bin page
------------------

The Temporary Bin page is populated by soft deleted content of which the logged in user is the owner.

To display content on the Temporary Bin page, the page fetches a list of all entities that have the relationship of 'deleted_by' attached to the current user

.. code-block:: php

	$list_params = [
	'relationship' => 'deleted_by',
	'type_subtype_pairs' => elgg_entity_types_with_capability('soft_deletable'),
	'inverse_relationship' => false,
	'no_results' => true
	];

	if (!elgg_is_admin_logged_in()) {
		$list_params['owner_guid'] = elgg_get_logged_in_user_guid();
	}

	$content = elgg_call(ELGG_SHOW_SOFT_DELETED_ENTITIES, function () use ($list_params) {
		return elgg_list_entities($list_params);
	});

This call will fetch all existing entities that are soft deleted and should be viewable in the temporary bin page.


.. code-block:: php

	echo elgg_view_page(
		elgg_echo('collection:object:bin'),
		elgg_view_layout('admin', [
			'title' => elgg_echo('collection:object:bin'),
			'content' => $content,
			'filter_id' => 'admin',
		])
	);

The content will then be passed through ``elgg_view_page()`` to display the content properly on the page


There are several actions that can be done by the user to restore or permanently delete content.
These actions are defined by whether the entity is a group or not.
These actions are created in the generic ``/engine/classes/Elgg/Menus/Entity.php`` class


- Restore: this action is created for every entity and for every entity which their container is not soft deleted

.. code-block:: php

	if (!($container->soft_deleted === 'yes')) {
		$return[] = \ElggMenuItem::factory([
			'name' => 'restore',
			'icon' => 'settings',
			'text' => elgg_echo('Restore'),
			'title' => elgg_echo('restore:this'),
			'href' => elgg_generate_action_url('entity/restore', [
				'deleter_guid' => elgg_get_logged_in_user_guid(),
				'guid' => $entity->guid,
			]),
			'confirm' => elgg_echo('restoreconfirm'),
			'priority' => 900,
		]);
	}

- Delete: the basic action for every entity. this uses the default delete action to permanently delete entities

.. code-block:: php

	$return[] = \ElggMenuItem::factory([
		'name' => 'delete',
		'icon' => 'delete',
		'text' => elgg_echo('Delete'),
		'title' => elgg_echo('delete:this'),
		'href' => elgg_generate_action_url('entity/delete', [
			'deleter_guid' => elgg_get_logged_in_user_guid(),
			'guid' => $entity->guid,
		]),
		'confirm' => elgg_echo('deleteconfirm'),
		'priority' => 950,
	]);

- Restore and Move: specifically for entities that belong to a group(either active or soft_deleted)

This option is always there for group owned entities, but is forced whenever the owning group is soft deleted

.. code-block:: php

    if (!($container instanceof \ElggUser)) {
        $return[] = \ElggMenuItem::factory([
            'name' => 'restore and move',
            'icon' => 'arrow-up',
            'text' => elgg_echo('Restore and Move'),
            'title' => elgg_echo('restore:this'),
            'href' => elgg_http_add_url_query_elements('ajax/form/entity/chooserestoredestination', [
                'address' => $entity->getURL(),
                'title' => $entity->getDisplayName(),
                'entity_guid' => $entity->guid,
                'deleter_guid' => elgg_get_logged_in_user_guid(),
                'entity_owner_guid' => $entity->owner_guid,
            ]),
            'link_class' => 'elgg-lightbox', // !!
            'priority' => 800,
    	]);
	}

- Restore Non-Recursively: to restore a group but still leave the owned content soft deleted

.. code-block:: php

	if ($entity instanceof \ElggGroup) {
		$return[] = \ElggMenuItem::factory([
			'name' => 'restore non-recursive',
			'icon' => 'arrow-up',
			'text' => elgg_echo('Restore Non-Recursively'),
			'title' => elgg_echo('restore:this'),
			'href' => elgg_generate_action_url('entity/restore', [
				'deleter_guid' => elgg_get_logged_in_user_guid(),
				'guid' => $entity->guid,
				'recursive' => false
			]),
			'confirm' => elgg_echo('restoreconfirm'),
			'priority' => 800,
		]);
	}

Restore / Restore Non-Recursively
---------------------------------

Clicking the restore button on an entity in the temporary bin will invoke ``/actions/entity/restore.php``.

.. code-block:: php

    <?php
    $recursive = (bool) get_input('recursive', true);

    $entity = elgg_call(ELGG_SHOW_SOFT_DELETED_ENTITIES, function () use ($guid){
	return get_entity($guid);
    });

This call will fetch the entity based on the $guid of the entity. A check is done to see if the entity should be restored recursively

.. code-block:: php

    <?php
    if ($entity->getSoftDeleted() === 'yes') {
	    if (!$entity->restore($recursive)) {
		    return elgg_error_response(elgg_echo('entity:restore:fail', [$display_name]));
	    }
    }

If ``getSoftDeleted()`` confirms that the entity is soft deleted, the entity will be restored either recurisvely or non-recursively.
The ``restore()`` function of ``/engine/classes/ElggEntity.php/`` is then called.

The ``restore()`` function is responsible for the resetting the **soft\_deleted** and **time\_soft\_deleted** database
columns to 'no' and '0', respectively. 

.. code-block:: php

    <?php
	$result = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES | ELGG_SHOW_SOFT_DELETED_ENTITIES, function() use ($recursive) {

		$result = _elgg_services()->entityTable->restore($this);

		$this->enableAnnotations();

		if ($recursive) {
			$soft_deleted_with_it = elgg_get_entities([
				'relationship' => 'soft_deleted_with',
				'relationship_guid' => $this->guid,
				'inverse_relationship' => true,
				'limit' => false,
				'batch' => true,
				'batch_inc_offset' => false,
			]);

			foreach ($soft_deleted_with_it as $e) {
				$e->restore($recursive);
				$e->removeRelationship($this->guid, 'soft_deleted_with');
				$e->removeAllRelationships('deleted_by', true);
			}
		}

		return $result;
	});
	$this->removeAllRelationships('deleted_by', true);

	if ($result) {
		$this->attributes['soft_deleted'] = 'no';
		_elgg_services()->events->triggerAfter('restore', $this->type, $this);
	}

	return $result;
	}

The ``restore($this)`` of the ``entityTable`` updates the **soft\_deleted** and **time\_soft\_deleted** database
values for the current entity. If ``$recursive`` is true, entities with a ``soft_deleted_with`` relationship
to the current entity are also called and restored. 
Relationships ``soft_deleted_with`` and ``deleted_by``are then removed and attributes reset.

Restore and Move
----------------

Clicking the restore-and-move button on an entity in the temporary bin will call the form ``views/default/forms/entity/chooserestoredestination.php``.
This form is registered as an Ajax view within ``engine/classes/Elgg/Application/SystemEventHandlers.php``:

.. code-block:: php

    <?php
	elgg_register_ajax_view('forms/entity/chooserestoredestination');

On call, the form will display the options for new container of the entity to be restored, based on the logged in user.
Always present is the option to set the owner as the new container of the entity (e.g., it will not be contained in any groups):

.. code-block:: php

    <?php
    $destination_container_names = [$entity_owner_guid => 'assign back to creator'];

If the user is an admin, he will have the rights to all active groups. If the user is a mere user, he will have the rights
to only the groups that he had joined.

.. code-block:: php

    <?php
    if (elgg_is_admin_logged_in()) {
        $soft_deleted_groups = elgg_get_entities([
        	'type' => 'group',
        	'inverse_relationship' => false,
        	'sort_by' => [
        		'property' => 'name',
        		'direction' => 'ASC',
        	],
        	'no_results' => elgg_echo('groups:none'),
        ]);
    } else {
        $soft_deleted_groups = elgg_get_entities([
        	'type' => 'group',
        	'relationship' => 'member',
        	'relationship_guid' => elgg_get_logged_in_user_guid(),
        	'inverse_relationship' => false,
        	'sort_by' => [
        		'property' => 'name',
        		'direction' => 'ASC',
        	],
        	'no_results' => elgg_echo('groups:none'),
        ]);
    }

This options, when appended together, will be displayed on the form and saved as ``destination_container_guid``.
Also passed in the form are GUIDs of the entity and the deleter.

.. code-block:: php

    <?php
    $fields = [
        [
            '#type' => 'select',
            '#label' => elgg_echo('Destination group'),
            'required' => true,
            'name' => 'destination_container_guid',
            'options_values' => $destination_container_names,
        ],
        [
            '#type' => 'hidden',
            'name' => 'entity_guid',
            'value' => $entity_guid,
        ],
        [
            '#type' => 'hidden',
            'name' => 'deleter_guid',
            'value' => $deleter_guid,
        ],
    ];

When the user clicks 'Confirm', the form forwards its variables to the corresponding restore-and-move action at
``actions/entity/chooserestoredestination.php``. The action reads these variables using ``get_input`` function:

.. code-block:: php

    <?php
    $guid = (int) get_input('entity_guid');
    $deleter_guid = (int) get_input('deleter_guid');
    $destination_container_guid = (int) get_input('destination_container_guid');

If the received entity in indeed soft deleted and can be restored, the action will then proceed to restore the entity,
as seen in the previous section, then overrides the old container with the destination one:

.. code-block:: php

    <?php
    if (!$entity->restore(false)) {
        return elgg_error_response(elgg_echo('entity:restore:fail', [$display_name]));
    }

    if (!($entity->overrideEntityContainerID($entity->guid, $entity->type, $entity->subtype, $destination_container_guid))) {
        return elgg_error_response(elgg_echo('entity:restore:fail', [$display_name]));
    }

Delete
------

Clicking the delete action on an entity from the temporary bin will invoke the ``/actions/entity/delete.php`` action.
As discussed in the 'Defining an entity as soft deletable section', a check is done to see
if the entity is soft deleted. As it always will be when the action is called from the temporary bin, the ``delete()`` method
of ``/engine/classes/ElggEntity.php/`` will be called. Since this is a core Elgg feature it will not be further elaborated on here.
Entities are then permanently deleted from the database.

Automated Cron Clean-up / Retention Period
------------------------------------------

In ``/engine/events.php``, the cron tasks for the Elgg system are defined. Added to the daily tasks is a call which cleans up
aged soft deleted content, older than an admin defined period.

.. code-block:: php

    <?php
    'cron' => [
	'daily' => [
		\Elgg\Database\RemoveSoftDeletedEntitiesHandler::class => [],
	    ],
    ]

This invokes ``engine/classes/Elgg/Database/RemoveSoftDeletedEntitiesHandler.php`` which contains the clean up logic.

.. code-block:: php

    <?php
    $entities = elgg_call(ELGG_SHOW_SOFT_DELETED_ENTITIES, function (){
		return elgg_get_entities([
			'type_subtype_pairs' => elgg_entity_types_with_capability('soft_deletable'),
			'limit' => false,
			'wheres' => [
				function(QueryBuilder $qb, $main_alias) {
					return $qb->compare("{$main_alias}.soft_deleted", '=', 'yes', ELGG_VALUE_STRING);
				},
				function(QueryBuilder $qb, $main_alias) {
                    $grace_period = elgg_get_config('bin_cleanup_grace_period',30);
					return $qb->compare("{$main_alias}.time_soft_deleted", '<', \Elgg\Values::normalizeTimestamp('-'.$grace_period.' days'));
				}
			],
		]);
	});

	foreach ($entities as $entity) {
		$entity->delete();
	}

An ``elgg_call()`` is performed to retrieve all entities which have the ``soft_deletable`` capability, and which
have a **soft\_deleted** value of 'yes' and **time\_soft\_deleted** Unix value which is aged more than retention (grace) period.
These entities are then deleted from the database.

The retention period can be edited from the administrators Site Settings page. It is saved as a config setting in the Elgg_Config table.
It has a default value of 30 days.
This is done in ``actions/admin/site/settings.php``

.. code-block:: php

    $bin_cleanup_grace_period = get_input('bin_cleanup_grace_period', 30);
    if ($bin_cleanup_grace_period === '') {
        $bin_cleanup_grace_period = 30;
    }

    elgg_save_config('bin_cleanup_grace_period', (int) $bin_cleanup_grace_period);
