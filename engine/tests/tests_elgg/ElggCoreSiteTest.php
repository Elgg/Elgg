<?php
/**
 *  Copyright (C) 2011 Quanbit Software S.A.
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  
 *  IMPORTANT: The tests in this file were ported from the original
 *  elgg engine tests. Please see Elgg's README.txt, COPYRIGHT.txt 
 *  and CONTRIBUTORS.txt for copyright and contributor information.  
 */
require_once(dirname(__FILE__) . '/../model/ElggTestCase.php');


class ElggCoreSiteTest extends ElggTestCase
{
	protected $site;

	/**
	 * Called before each test method.
	 */
	public function setUp() 
	{
		parent::setUp();
		$this->site = new ElggSiteTest();
	}

	public function testElggSiteConstructor() 
	{
		$attributes = array();
		$attributes['guid'] = NULL;
		$attributes['type'] = 'site';
		$attributes['subtype'] = NULL;
		$attributes['owner_guid'] = elgg_get_logged_in_user_guid();
		$attributes['container_guid'] = elgg_get_logged_in_user_guid();
		$attributes['site_guid'] = NULL;
		$attributes['access_id'] = ACCESS_PRIVATE;
		$attributes['time_created'] = NULL;
		$attributes['time_updated'] = NULL;
		$attributes['last_action'] = NULL;
		$attributes['enabled'] = 'yes';
		$attributes['tables_split'] = 2;
		$attributes['tables_loaded'] = 0;
		$attributes['name'] = NULL;
		$attributes['description'] = NULL;
		$attributes['url'] = NULL;
		ksort($attributes);

		$entity_attributes = $this->site->expose_attributes();
		ksort($entity_attributes);

		$this->assertEquals($entity_attributes, $attributes);
	}

	public function testElggSiteSaveAndDelete() 
	{
		//Used to be: 
		// $this->assertTrue($this->site->save());
		// $this->assertTrue($this->site->delete());
		//@link http://trac.elgg.org/ticket/4108
		
		$guid = $this->site->save();
		$this->assertInternalType('int', $guid);
		$this->assertGreaterThan(0, $guid);
		
		$this->assertEquals($this->site->delete(), 1);
	}
}

class ElggSiteTest extends ElggSite 
{
	public function expose_attributes() 
	{
		return $this->attributes;
	}
}