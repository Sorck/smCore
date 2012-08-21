<?php

/**
 * smCore BBCode Parser
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
 * Portions created by the Initial Developer are Copyright (C) 2011
 * the Initial Developer. All Rights Reserved.
 */

namespace smCore\Parsers\Driver;

class BBC
{
	protected $_options;
	/**
	 * 
	 * @param array $options
	 */
	public function __construct(array $options = array())
	{
		$this->_options = array_merge(array(), $options);
	}
	
	public function parse($content)
	{
		// can we really be bothered with an empty value?
		if(empty($content))
			return $content;
		// no opening BBCode tags means nothing to parse
		if(strpos($content, '[') === false)
			return $content;
		$current_position = 0;
		$final_position = strlen($content)-1;
		$code_to_return = '';
		$open_tags = array();$i = 0;
		while($current_position <= $final_position && $i < 5) {$i++;
			$backup_position = $current_position;
			// get our next opening tag
			$current_position = strpos($content, '[', $backup_position+1);
			// make sure we're not on or before our previous tag
			if($current_position <= $backup_position)
			    $current_position = $final_position;
			if($current_position === false)
			{
				// make sure to add everything between $backup_position and the end of the content
				// close all our open tags
				while(sizeof($open_tags) !== 0)
				{
				    $code_to_return .= '</' . array_pop($open_tags) . '>';
				}echo 'other';
			}
			// is it a close tag?
			if(isset($content[$current_position+1]) && $content[$current_position+1] == '/')
			{
				// get the tag name
				$n = 0;
				while(isset($content[$current_position+1+$n]) && var_dump(preg_match("[a-zA-Z]", $content[$current_position+1+$n]))) {
				    $n++;
				}
				// if the tag is the last item on the $open_tags stack then close it
				// else close all tag items on the stack
			}
			#if($backup_position === $current_position)
			#var_dump($current_position);
			echo "cycle $current_position";
				$current_position++;
		}exit;
		return $code_to_return;
	}
}