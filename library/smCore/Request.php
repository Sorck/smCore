<?php

/**
 * smCore Request Class
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

namespace smCore;

use Inspekt_Cage;

class Request
{
	protected $_url;
	protected $_method = 'GET';
	protected $_is_xml_http_request = false;
	protected $_path;
	protected $_format;
	protected $_has_get_params = false;
	protected $_subdomain = 'www';

	public function __construct()
	{
		$input = Application::get('input');

		$this->_method = $input->server->getAlpha('REQUEST_METHOD');
		$this->_is_xml_http_request = $input->server->getAlpha('X_REQUESTED_WITH') == 'XMLHttpRequest' || $input->get->keyExists('xmlHttpRequest') || $input->post->keyExists('xmlHttpRequest');

		// Get the app-relative path requested
		$this->_parsePath();
	}

	public function getUrl()
	{
		return $this->_url;
	}

	public function getPath()
	{
		return $this->_path;
	}

	public function getRequestMethod()
	{
		return $this->_method;
	}

	public function getFormat()
	{
		return $this->_format;
	}

	public function getSubdomain()
	{
		return $this->_subdomain;
	}

	public function isXmlHttpRequest()
	{
		return $this->_is_xml_http_request;
	}

	public function hasGetParams()
	{
		return $this->_has_get_params;
	}

	public function _parsePath()
	{
		if (isset($_SERVER['HTTP_X_REWRITE_URL']))
		{
			// IIRF rewrites for IIS
			$this->_url = $_SERVER['HTTP_X_REWRITE_URL'];
		}
		else if (isset($_SERVER['REQUEST_URI']))
		{
			// This covers both Apache and nginx
			$this->_url = $_SERVER['REQUEST_URI'];
		}
		else if (!empty($_SERVER['IIS_WasUrlRewritten']) && !empty($_SERVER['UNENCODED_URL']))
		{
			// Default IIS is checked after IIRF so that IIRF takes precedence
			$this->_url = $_SERVER['UNENCODED_URL'];
		}
		else
		{
			$this->_url = '';
		}

		if ($_SERVER['HTTP_HOST'] != $_SERVER['SERVER_NAME'])
		{
			$this->_subdomain = rtrim(str_replace($_SERVER['SERVER_NAME'], '', $_SERVER['HTTP_HOST']), '.');
		}

		$_GET = array();

		if (!empty($this->_url))
		{
			if (false !== strpos($this->_url, '?', 1))
			{
				$query = substr($this->_url, strpos($this->_url, '?', 1) + 1);
				$this->_path = substr($this->_url, 0, strpos($this->_url, '?', 1));

				if (!empty($query))
				{
					$this->_has_get_params = true;

					$parameters = explode(';', str_replace('&', ';', $query));

					foreach ($parameters as $parameter)
					{
						if (false !== strpos($parameter, '='))
						{
							$_GET[substr($parameter, 0, strpos($parameter, '='))] = substr($parameter, strpos($parameter, '=') + 1);
						}
						else
						{
							$_GET[$parameter] = '';
						}
					}
				}
			}
			else
			{
				$this->_path = $this->_url;
			}

			// Find out what format this request is in
			if (substr($this->_path, -5) === '.json')
			{
				$this->_format = 'json';
			}
			else if (substr($this->_path, -4) === '.xml')
			{
				$this->_format = 'xml';
			}
			else
			{
				$this->_format = '';
			}

			// make sure there's no query string left
			$this->_path = trim($this->_path, '/?');
			
			// make sure our path is relative to our base URL so that we can be put in sub directories
			if($this->_path[0] !== '/')
				$this->_path = '/' . $this->_path;
			$this->_path = str_replace(parse_url(Settings::URL, PHP_URL_PATH), '', $this->_path);
		}

		// Rebuild the superglobals and the cages
		$_REQUEST = $_POST + $_GET;

		// Forget what $_GET actually says - overwrite it with our fake query string.
		Application::get('input')->get = Inspekt_Cage::Factory($_GET, null, '_GET', false);
		Application::get('input')->request = Inspekt_Cage::Factory($_REQUEST, null, '_GET', false);
	}
}