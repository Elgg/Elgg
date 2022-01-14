<?php

namespace Elgg\Views;

use Elgg\I18n\DateTime;

/**
 * @group ViewRendering
 * @group ViewsService
 * @group DateTime
 */
class DateOutputTest extends ViewRenderingTestCase {

	public function up() {
		parent::up();

		$this->date = new DateTime();
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

		$output = elgg_format_element('time', [
			'datetime' => $this->date->format('c'),
		], $this->date->formatLocale($this->format));

		$this->assertViewOutput($output, 'output/date', [
			'value' => $this->date,
			'format' => $this->format,
		]);
	}

	public function testCanRenderTime() {
		$format = 'g:ia';
		
		$output = elgg_format_element('time', [
			'datetime' => $this->date->format('c'),
		], $this->date->formatLocale($format));

		$this->assertViewOutput($output, 'output/time', [
			'value' => $this->date,
			'format' => $format,
		]);
	}
}
