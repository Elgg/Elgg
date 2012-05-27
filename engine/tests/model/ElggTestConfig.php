<?php
/**
 *  Copyright (C) 2011-2012 Quanbit Software S.A.
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
 */

/**
 * The $CONFIG global variable controls, among other things, the DB connection configuration.
 * 
 * These $CONFIG DB parameters are set in the engine/settings.php file, which is called by the elgg startup process.
 * For this plugin to work we must temporarily use a different DB (the test DB) and that means changing the parameters
 * used by elgg to connect to the DB. However we don't want to change any of the elgg core files or the settings.php file. 
 * 
 * To achieve this we create a global $CONFIG variable before it is created by the elgg engine and store an instance
 * of ElggTestConfig class, where the test DB credentials are stored as read-only parameters. When the settings.php file is 
 * called the "real" DB credentials are applied to the $CONFIG varibale, but are ignored due to the __set() implementation.
 * As a result the test DB parameters are kept in the $CONFIG global without changing a single bit in the elggs core funcionality.
 *  
 * We need this class to be Serializable since that is the way parameters are shared in PHPUnit when runing tests in different
 * processes.  
 *  
 * @author andres
 */

class ElggTestConfig implements Serializable
{
	/**
	 * The read-only variables 
	 * 
	 * @var array
	 */
	private $variables;
	
	/**
	 * Initialize the instance with a set ok key=>value pairs,
	 * which will be treated as read-only attributes.
	 * 
	 * @param array $readOnlyAttributes - The mapping between variable names and values.
	 */
	public function __construct($readOnlyAttributes = array())
	{
		$this->variables = array();
		foreach ($readOnlyAttributes as $name => $value) 
		{
			$this->variables[$name] = $value;
		}
	}

	public static function fromFile($iniFile)
	{
		$file = new ElggTestConfigFile($iniFile);
		$mappings = $file->getMappings();
		return new ElggTestConfig($mappings);
	}
	
	/**
	 * Answer the value of a read-only variable if present.
	 * 
	 * @param string $name - The variable name
	 * @return mixed - The associated value or NULL if absent.
	 */
	public function __get($name) 
	{
		if (isset($this->variables[$name]))
		{
			return $this->variables[$name];
		}
		else
			{
				return null;	
			}
    }
	
	/**
	 * Allow to set only values that weren't assigned before.
	 * 
	 * @param string $name - The attribute name
	 * @param mixed $value - The attribute value
	 * @return mixed
	 */
	public function __set($name, $value) 
	{
		if (!isset($this->variables[$name]))
		{
			$this->$name = $value;
		}
		return $value;
	}
	
	/**
	 * For consistency reasons, answer if an object's
	 * attribute is set.
	 * 
	 * @param string $name
	 */
	public function __isset($name) 
	{
		$selfVars = get_object_vars($this);
        return isset($selfVars[$name]) || isset($this->variables[$name]);
    }
    
//    public function override($varname, $value)
//    {
//    	if (isset($this->$varname))
//    	{
//    		unset($this->$varname);
//    	}
//    	$this->variables[$varname] = $value;
//    }
//    
//    public function makeFieldsPermanent()
//    {
//		$vars = get_object_vars($this);
//		unset($vars['variables']);
//		foreach($vars as $key => $val)
//		{
//			$this->override($key, $val);
//		} 
//    }
    
    /**
     * A little help for properly serializing this object.
     * 
     * @see Serializable::serialize()
     */
    public function serialize() 
    {
        return serialize($this->variables);
    }
    
    /**
     * A little help for properly unserializing this object.
     * 
     * @see Serializable::unserialize()
     */
    public function unserialize($data) 
    {
        $this->variables = unserialize($data);
    }
}