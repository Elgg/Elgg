<?php

namespace Elgg;

/**
 * Abstract class for testing view output
 */
abstract class ViewRenderingTestCase extends IntegrationTestCase {

	public function up() {
		_elgg_services()->logger->disable();
	}

	public function down() {
		_elgg_services()->logger->enable();
	}

	/**
	 * Returns name of the view to test
	 * @return string
	 */
	abstract public function getViewName();

	/**
	 * Returns default view vars to testing rendering
	 * @return array
	 */
	abstract public function getDefaultViewVars();

	public function viewtypeProvider() {
		$provides = [];

		$data = _elgg_services()->views->getInspectorData();

		foreach ($data['locations'] as $viewtype => $views) {
			foreach ($views as $view => $path) {
				if ($view == $this->getViewName()) {
					$provides[] = [$viewtype];
				}
			}
		}

		return $provides;
	}

	/**
	 * Assert expected view output
	 *
	 * @param string $expected  Expected string
	 * @param array  $view_vars View vars
	 * @param string $message   Error message
	 * @return void
	 */
	public function assertViewOutput($expected, $view_vars = [], $message = '') {
		$actual = elgg_view($this->getViewName(), $view_vars);
		$this->assertXmlStringEqualsXmlString($expected, $actual, $message);
	}

	/**
	 * @dataProvider viewtypeProvider
	 */
	public function testCanRenderViewWithEmptyVars($viewtype) {
		$output = elgg_view($this->getViewName(), [], $viewtype);
		$this->assertInternalType('string', $output);
	}

	/**
	 * @dataProvider viewtypeProvider
	 */
	public function testCanRenderViewWithVars($viewtype) {
		$output = elgg_view($this->getViewName(), $this->getDefaultViewVars(), $viewtype);
		$this->assertInternalType('string', $output);
	}

}