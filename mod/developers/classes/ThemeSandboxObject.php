<?php

/**
 * @internal
 */
class ThemeSandboxObject extends ElggObject {
	
	/**
	 * {@inheritDoc}
	 */
	public function getTimeCreated() {
		return time();
	}
}
