<?php

namespace Elgg\Controllers;

use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Http\Request;
use Elgg\IntegrationTestCase;

class CommentEntityRedirectorIntegrationTest extends IntegrationTestCase {

	/**
	 * @var \ElggComment
	 */
	protected $comment;
	
	/**
	 * @var \ElggObject
	 */
	protected $entity;
	
	/**
	 * @var \ElggUser
	 */
	protected $owner;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->owner = $this->createUser();
		elgg()->session->setLoggedInUser($this->owner);
		
		$this->entity = $this->createObject([
			'owner_guid' => $this->owner->guid,
			'container_guid' => $this->owner->guid,
			'access_id' => ACCESS_PRIVATE,
		]);
		
		$this->comment = $this->createObject([
			'subtype' => 'comment',
			'owner_guid' => $this->owner->guid,
			'container_guid' => $this->entity->guid,
			'access_id' => ACCESS_PUBLIC,
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		
		if ($this->comment instanceof \ElggComment) {
			$this->comment->delete();
		}
		
		if ($this->entity instanceof \ElggObject) {
			$this->entity->delete();
		}
		
		if ($this->owner instanceof \ElggUser) {
			$this->owner->delete();
		}
		
		elgg()->session->removeLoggedInUser();
	}
	
	protected function createService(Request $request) {
		$request->_integration_testing = true;
		
		$app = self::createApplication([
			'isolate' => true,
			'request' => $request,
		]);
		
		$app->_services->session->setLoggedInUser($this->owner);
		
		// keep this inline with the route declaration in /engine/routes.php
		$app->_services->routes->register('view:object:comment', [
			'path' => '/comment/view/{guid}/{container_guid?}',
			'controller' => \Elgg\Controllers\CommentEntityRedirector::class,
		]);
		
		$app->_services->routes->register("view:object:{$this->entity->subtype}", [
			'path' => '/object/view/{guid}',
			'handler' => function (Request $request) {
				return elgg_ok_response('Entity found');
			},
		]);
	}
	
	protected function executeRequest(Request $request) {
		$request->_integration_testing = true;
		
		ob_start();
		
		$t = false;
		$response = false;
		try {
			_elgg_services()->router->route($request);
			$response = _elgg_services()->responseFactory->getSentResponse();
		} catch (\Throwable $t) {
			// just catching
		}
		
		ob_get_clean();
		
		if ($t instanceof \Throwable) {
			throw $t;
		}
		
		return $response;
	}
	
	public function testUnknownCommentUnknownContainer() {
		$request = $this->prepareHttpRequest(elgg_generate_url('view:object:comment', [
			'guid' => 123456789,
			'container_guid' => 987654321,
		]));
		
		$this->createService($request);
		
		$this->expectException(EntityNotFoundException::class);
		$this->expectErrorMessage(elgg_echo('generic_comment:notfound'));
		$this->executeRequest($request);
	}
	
	public function testUnknownCommentKnownContainer() {
		$request = $this->prepareHttpRequest(elgg_generate_url('view:object:comment', [
			'guid' => 123456789,
			'container_guid' => $this->entity->guid,
		]));
		
		$this->createService($request);
		$response = $this->executeRequest($request);
		
		$this->assertTrue($response->isRedirect($this->entity->getURL()));
	}
	
	public function testInaccessableCommentContainer() {
		$request = $this->prepareHttpRequest(elgg_generate_url('view:object:comment', [
			'guid' => $this->comment->guid,
			'container_guid' => $this->entity->guid,
		]));
		
		$this->createService($request);
		
		$other_user = $this->createUser();
		elgg()->session->setLoggedInUser($other_user);
		
		$this->expectException(EntityNotFoundException::class);
		$this->expectErrorMessage(elgg_echo('generic_comment:notfound'));
		$this->executeRequest($request);
		
		$other_user->delete();
	}
	
	public function testCommentRedirect() {
		$request = $this->prepareHttpRequest(elgg_generate_url('view:object:comment', [
			'guid' => $this->comment->guid,
			'container_guid' => $this->entity->guid,
		]));
		
		$this->createService($request);
		$response = $this->executeRequest($request);
		
		$this->assertTrue($response->isRedirect($this->entity->getURL() . "#elgg-object-{$this->comment->guid}"));
	}
}
