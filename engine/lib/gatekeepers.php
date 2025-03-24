<?php
/**
 * Holds all gatekeeper functions
 */

use Elgg\Exceptions\Http\Gatekeeper\GroupToolGatekeeperException;

/**
 * Checks if a group has a specific tool enabled.
 * Forward to the group if the tool is disabled.
 *
 * @param string   $tool_name  The name of the group tool to check
 * @param null|int $group_guid The group that owns the page. If not set, this
 *                             will be pulled from elgg_get_page_owner_guid().
 *
 * @return void
 * @throws GroupToolGatekeeperException
 * @since 3.0.0
 */
function elgg_group_tool_gatekeeper(string $tool_name, ?int $group_guid = null): void {
	$group_guid = $group_guid ?? elgg_get_page_owner_guid();
	
	$group = get_entity($group_guid);
	if (!$group instanceof \ElggGroup) {
		return;
	}
	
	if ($group->isToolEnabled($tool_name)) {
		return;
	}
	
	$ex = new GroupToolGatekeeperException();
	$ex->setRedirectUrl($group->getURL());
	$ex->setParams([
		'entity' => $group,
		'tool' => $tool_name,
	]);
	
	throw $ex;
}

/**
 * Validates if the HMAC signature of the current request is valid
 * Issues 403 response if signature is invalid
 *
 * @return void
 * @throws \Elgg\Exceptions\HttpException
 */
function elgg_signed_request_gatekeeper(): void {
	if (\Elgg\Application::isCli()) {
		return;
	}
	
	_elgg_services()->urlSigner->assertValid(elgg_get_current_url());
}

/**
 * Used at the top of a page to mark it as logged in users only.
 *
 * @return void
 * @throws \Elgg\Exceptions\Http\Gatekeeper\LoggedInGatekeeperException
 * @since 1.9.0
 */
function elgg_gatekeeper(): void {
	_elgg_services()->gatekeeper->assertAuthenticatedUser();
}

/**
 * Used at the top of a page to mark it as admin only.
 *
 * @return void
 * @throws \Elgg\Exceptions\Http\Gatekeeper\AdminGatekeeperException
 * @since 1.9.0
 */
function elgg_admin_gatekeeper(): void {
	_elgg_services()->gatekeeper->assertAuthenticatedAdmin();
}

/**
 * Can the viewer see this entity?
 *
 * Tests if the entity exists and whether the viewer has access to the entity
 * if it does. If the viewer cannot view this entity, it forwards to an
 * appropriate page.
 *
 * @param int         $guid              Entity GUID
 * @param null|string $type              Optional required entity type
 * @param null|string $subtype           Optional required entity subtype
 * @param bool        $validate_can_edit flag to check canEdit access
 *
 * @return void
 *
 * @throws Exception
 * @throws \Elgg\Exceptions\Http\EntityNotFoundException
 * @throws \Elgg\Exceptions\Http\EntityPermissionsException
 * @throws \Elgg\Exceptions\HttpException
 * @since 1.9.0
 */
function elgg_entity_gatekeeper(int $guid, ?string $type = null, ?string $subtype = null, bool $validate_can_edit = false): void {
	$entity = _elgg_services()->gatekeeper->assertExists($guid, $type, $subtype);
	_elgg_services()->gatekeeper->assertAccessibleEntity($entity, null, $validate_can_edit);
}

/**
 * Require that the current request be an XHR. If not, execution of the current function
 * will end and a 400 response page will be sent.
 *
 * @return void
 * @throws \Elgg\Exceptions\Http\Gatekeeper\AjaxGatekeeperException
 * @since 1.12.0
 */
function elgg_ajax_gatekeeper(): void {
	_elgg_services()->gatekeeper->assertXmlHttpRequest();
}
