<?php
/**
 * Dupplicated tags in htmlawed
 */
class HtmLawedDuplicateTagsTest extends ElggCoreUnitTest {

    /**
     * Called before each test object.
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Called before each test method.
     */
    public function setUp() {
    }
    
    /**
     * Called after each test method.
     */
    public function tearDown() {
        // do not allow SimpleTest to interpret Elgg notices as exceptions
        $this->swallowErrors();
    }
    
    /**
     * Called after each test object.
     */
    public function __destruct() {
        elgg_set_ignore_access($this->ia);
        // all __destruct() code should go above here
        parent::__destruct();
    }
    
    public function testNotDuplicateTags() {
        $filter_html = '<ul><li>item</li></ul>';    
        set_input('test', $filter_html);
        
        $expected = $filter_html;
        $result = get_input('test');
        $this->assertEqual($result, $expected);
    }
}