<?php

namespace Elgg;

interface Route {

	/**
	 * Validates the accessibility of the route
	 * Returns alternative response object for errors/forwards
	 * 
	 * @return \Elgg\Http\Response
	 */
	public function validate();

	/**
	 * Returns route identifier
	 * @return string
	 */
	public function getIdentifier();

	/**
	 * Returns URL segments
	 * @return array
	 */
	public function getSegments();

	/**
	 * Returns page owner entity
	 * @return \ElggEntity|false
	 */
	public function getPageOwner();

	/**
	 * Returns entity that this page is dedicated to
	 * @return \ElggEntity|false
	 */
	public function getPageEntity();

	/**
	 * Returns HTMl content of the layout
	 */
	public function getTitle();

	/**
	 * Returns HTML content of the page (layout)
	 *
	 * We could implement a wrapper here to not return HTML of elgg_list_entities(),
	 * e.g. return new ElggList($options). The resources/default views in different
	 * viewtypes can wrap accordingly.
	 *
	 * Same could work for entity, e.g. return $this->getPageEntity().
	 * Resource view would then use elgg_view_entity($entity, ['full_view' => true]);
	 * We would want to update full entity listing to include 'responses' parameter
	 * like we do in river.
	 * 
	 * @return string
	 */
	public function getContent();

	/**
	 * Return HTML of the sidebar
	 * @return string|false
	 */
	public function getSidebar();

	/**
	 * Returns HTML of the alternative sidebar
	 * @return string|false
	 */
	public function getSidebarAlt();

	/**
	 * Returns an array of breadcrumbs
	 * @return array
	 */
	public function getBreadcrumbs();

	/**
	 * Returns an array of predefined filter tabs
	 * @return array
	 */
	public function getFilter();

	/**
	 * Returns name of the layout to use
	 * @return string
	 */
	public function getLayout();

	/**
	 * Returns title of the HTML document
	 * @return string
	 */
	public function getPageTitle();

	/**
	 * Returns name of the page shell to use
	 * @return string
	 */
	public function getPageShell();
	
}
