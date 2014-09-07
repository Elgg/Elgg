<?php

class Elgg_Access_AclTest extends PHPUnit_Framework_TestCase {

	/**
	 * Ignoring access permissions globally shouldn't affect the results
	 * when fetching the ACLs that user belongs to.
	 */
	public function testIgnoringAccessDoesntAffectFetchingReadACLs() {
		/**
		 * How to test:
		 *  1. Create an ElggUser
		 *  2. Create an ACL and add the user to it
		 *  3. Create a second ACL that the user doesn't belong to
		 *  4. Assert that fetching user's ACLs returns only the first ACL
		 *  5. Set ACLs to be ignored
		 *  6. Assert that fetching user's ACLs still return only the first ACL
		 */
		$this->markTestIncomplete();
	}

	/**
	 * Ignoring access permissions globally shouldn't affect the results
	 * when checking whether a specific user has read access to an entity.
	 */
	public function testIgnoringAccessDoesntAffectReadPermissionCheck() {
		/**
		 * How to test:
		 *  1. Create an ElggUser
		 *  2. Create an ACL that the user doesn't belong to
		 *  3. Create an entity that uses the ACL
		 *  4. Assert that read permission check returns false for the user
		 *  5. Set ACLs to be ignored
		 *  6. Assert that read permission check still returns false for the user
		 */
		$this->markTestIncomplete();
	}

	/**
	 * Fetching ACLs that a user belongs to should return the same results
	 * regardless if that particular user is logged in or not.
	 */
	public function testReadAclsAreCorrectForLoggedOutUser() {
		/**
		 * How to test:
		 *  1. Create an ElggUser
		 *  2. Create an ACL that the user belongs to
		 *  3. Sign in the user
		 *  4. Assert that fetching the user's ACLs returns the ACL
		 *  5. Log out the user
		 *  6. Assert that fetching the user's ACLs returns the ACL
		 */
		$this->markTestIncomplete();
	}

}
