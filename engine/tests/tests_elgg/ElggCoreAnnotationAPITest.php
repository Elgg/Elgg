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

class ElggCoreAnnotationAPITest extends ElggTestCase
{
	protected $metastrings;
	protected $object;
	
	/**
	 * Called before each test method.
	 */
	public function setUp() 
	{
		parent::setUp();
		$this->object = new ElggObject();
	}

	public function testElggGetAnnotationsCount() 
	{
		$this->object->title = 'Annotation Unit Test';
		$this->object->save();

		$guid = $this->object->getGUID();
		create_annotation($guid, 'tested', 'tested1', 'text', 0, ACCESS_PUBLIC);
		create_annotation($guid, 'tested', 'tested2', 'text', 0, ACCESS_PUBLIC);

		$count = (int)elgg_get_annotations(array(
												'annotation_names' => array('tested'),
												'guid' => $guid,
												'count' => true,
												));
		$this->assertEquals($count, 2);
	}
}