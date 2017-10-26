<?php

namespace Elgg;

/**
 * Abstract class for testing view output
 */
abstract class ViewRenderingTestCase extends IntegrationTestCase {

	public function up() {
		$user = $this->getRandomUser();
		_elgg_services()->session->setLoggedInUser($user);
		elgg_set_page_owner_guid($user->guid);
	}

	public function down() {
		_elgg_services()->session->removeLoggedInUser();
		elgg_set_page_owner_guid(null);
	}

	/**
	 * Returns an array of view names to test with given default view vars
	 * @return array
	 */
	abstract public function getViewNames();

	/**
	 * Returns default view vars to testing rendering
	 * @return array
	 */
	abstract public function getDefaultViewVars();

	/**
	 * An array of views to test
	 * @return array
	 */
	public function viewListProvider() {
		$provides = [];

		$data = _elgg_services()->views->getInspectorData();

		foreach ($data['locations'] as $viewtype => $views) {
			foreach ($views as $view => $path) {
				if (in_array($view, $this->getViewNames())) {
					$provides[] = [$view, $viewtype];
				}
			}
		}

		return $provides;
	}

	/**
	 * Assert expected view output
	 *
	 * @param string $expected  Expected string
	 * @param string $view      View to test
	 * @param array  $view_vars View vars
	 * @param string $message   Error message
	 *
	 * @return void
	 */
	public function assertViewOutput($expected, $view, $view_vars = [], $viewtype = 'default', $message = '') {
		$actual = $this->view($view, $view_vars, $viewtype);
		$this->assertXmlStringEqualsXmlString($expected, $actual, $message);
	}

	/**
	 * @dataProvider viewListProvider
	 */
	public function testCanRenderViewWithEmptyVars($view, $viewtype) {
		if (!elgg_view_exists($view, $viewtype)) {
			$this->markTestSkipped("View '$view' does not exist");
		}
		try {
			$output = $this->view($view, [], $viewtype);
			$this->assertInternalType('string', $output);
		} catch (\Exception $e) {
			$msg = "View '$view' in '$viewtype' viewtype must be updated to validate parameters it reads from \$vars array";
			$this->markTestIncomplete($msg);
		}
	}

	/**
	 * @dataProvider viewListProvider
	 */
	public function testCanRenderViewWithVars($view, $viewtype) {
		if (!elgg_view_exists($view, $viewtype)) {
			$this->markTestSkipped("View '$view' does not exist");
		}
		try {
			$output = $this->view($view, $this->getDefaultViewVars(), $viewtype);
			$this->assertInternalType('string', $output);
		} catch (\Exception $e) {
			$msg = "View '$view' in '$viewtype' viewtype must be updated to validate parameters it reads from \$vars array";
			$this->markTestIncomplete($msg);
		}
	}

	/**
	 * Render a view using a correct elgg_view_* function
	 */
	public function view($view, array $vars = [], $viewtype = 'default', array $component_vars = []) {
		list($component, $subview) = explode('/', $view, 2);

		switch ($component) {
			case 'form' :
				$prev_viewtype = elgg_set_viewtype($viewtype);
				$output = elgg_view_form($subview, $component_vars, $vars);
				elgg_set_viewtype($prev_viewtype);
				return $output;

			default:
				return elgg_view($view, $vars, $viewtype);
		}
	}

}
