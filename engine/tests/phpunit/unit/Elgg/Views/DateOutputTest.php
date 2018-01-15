<?php

namespace Elgg\Views;

/**
 * @group ViewRendering
 * @group ViewsService
 * @group DateTime
 */
class DateOutputTest extends ViewRenderingTestCase {

	public function up() {
		parent::up();

		$this->date = new \DateTime();
		$this->format = 'Y-m-d H:i';
	}


	public function getViewNames() {
		return [
			'input/date',
			'input/time',
			'output/date',
			'output/time',
		];
	}

	public function getDefaultViewVars() {
		return [
			'value' => $this->date,
			'format' => $this->format,
		];
	}

	public function testCanRenderDate() {

		$output = $this->date->format($this->format);

		$this->assertEquals($output, elgg_view('output/date', [
			'value' => $this->date,
			'format' => $this->format,
		]));
	}

	public function testCanRenderTime() {

		$format = 'g:ia';
		$output = $this->date->format($format);

		$this->assertEquals($output, elgg_view('output/time', [
			'value' => $this->date,
			'format' => $format,
		]));
	}


}
