<?php
namespace Elgg;

class TimerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var Timer
	 */
	private $timer;

	public function setUp() {
		$this->timer = new Timer();
	}

	public function testCapturesTimes() {
		$this->timer->begin([]);
		$this->timer->begin(['1']);
		$this->timer->begin(['1', 'a']);
		$this->timer->end(['1']);
		$this->timer->end([]);

		$times = $this->timer->getTimes();
		$begin = Timer::MARKER_BEGIN;
		$end = Timer::MARKER_END;

		$this->assertEquals([$begin, 1, $end], array_keys($times));
		$this->assertEquals([$begin, 'a', $end], array_keys($times[1]));
		$this->assertEquals([$begin], array_keys($times[1]['a']));

		$this->assertIsATime($times[$begin]);
		$this->assertIsATime($times[1][$begin]);
		$this->assertIsATime($times[1]['a'][$begin]);
		$this->assertIsATime($times[1][$end]);
		$this->assertIsATime($times[$end]);
	}

	public function assertIsATime($val) {
		list($one, $two) = explode(' ', $val);
		$this->assertTrue(is_numeric($one) && is_numeric($two));
	}

	public function testCanDetectEnd() {
		$this->assertFalse($this->timer->hasEnded([]));

		$this->timer->begin([]);
		$this->assertFalse($this->timer->hasEnded([]));

		$this->timer->end([]);
		$this->assertTrue($this->timer->hasEnded([]));

		$this->timer->end(['1', 'a']);
		$this->assertTrue($this->timer->hasEnded(['1', 'a']));
	}
}
