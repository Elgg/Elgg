<?php

/**
 * @access private
 */
class ThemeSandboxObject extends ElggObject {
	
	/**
	 * {@inheritDoc}
	 */
	public function getTimeCreated() {
		return time();
	}
}
