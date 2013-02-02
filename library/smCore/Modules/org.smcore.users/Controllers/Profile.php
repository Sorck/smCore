<?php

/**
 * smCore Users Module - Profile Controller
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

namespace smCore\Modules\Users\Controllers;

use smCore\Module\Controller;

class Profile extends Controller
{
	public function preDispatch($method)
	{
		$this->module->loadLangPackage();
	}

	public function summary()
	{
		$this->_app['menu']->setActive('user', 'user_profile');
		return $this->module->render('profile');
	}

	public function settings()
	{
		$this->_app['menu']->setActive('user', 'user_settings');
		return $this->module->render('settings');
	}
}