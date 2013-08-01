<?php

/**
 * smCore Abstract Model
 *
 * @package smCore
 * @author smCore Dev Team
 * @license MPL 1.1
 * @version 1.0 Alpha
 *
 * The contents of this file are subject to the Mozilla Public License Version 1.1
 * (the "License"); you may not use this package except in compliance with the
 * License. You may obtain a copy of the License at http://www.mozilla.org/MPL/
 *
 * The Original Code is smCore.
 *
 * The Initial Developer of the Original Code is the smCore project.
 *
 * Portions created by the Initial Developer are Copyright (C) 2013
 * the Initial Developer. All Rights Reserved.
 */

namespace smCore;

abstract class Model implements ArrayAccess
{
    protected $_data = array();
    
    /**
     * setData - Set the modules data.
     * 
     * @return null
     */
	abstract public function setData(array $data);
    
    /**
     * save - Use a storage object to save the current models data
     * 
     * @return null
     */
    abstract public function save();
    
    public function setRawData(array $data)
    {
    	foreach ($data as $key => $value)
		{
			$this->_data[$key] = $value;
		}
	}
    
    /**
     * ArrayAccess - implementation for empty/isset/array_key_exists/etc.
     *
	 * @param mixed $offset
	 *
	 * @return boolean
	 */
	public function offsetExists($offset)
	{
		return isset($this->_data[$offset]);
	}

	/**
	 * ArrayAccess - implementation for getting data via array syntax
	 *
	 * @param mixed $offset Name of the key, usually a string
	 *
	 * @return boolean
	 */
	public function offsetGet($offset)
	{
		if (array_key_exists($offset, $this->_data))
		{
			return $this->_data[$offset];
		}

		return false;
	}

	/**
	 * ArrayAccess - implementation for setting data via array syntax
	 *
	 * @param mixed $offset Name of the key, usually a string
	 * @param mixed $value  
	 */
	public function offsetSet($offset, $value)
	{
		if ('password' === $offset)
		{
			throw new Exception('User passwords cannot be set via array access.');
		}

		$this->_data[$offset] = $value;
	}

	/**
	 * ArrayAccess - implementation for unsetting data via array syntax
	 *
	 * @param mixed $offset Name of the key, usually a string
	 */
	public function offsetUnset($offset)
	{
		unset($this->_data[$offset]);
	}
}