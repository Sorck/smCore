<?php

/**
 * smCore Authentication Module - Log In/Out Controller
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

namespace smCore\Modules\Auth\Controllers;

use smCore\Application, smCore\Module\Controller, smCore\Security\Crypt\Bcrypt, smCore\Security\Session, smCore\Settings, smCore\Storage;

class LogInOut extends Controller
{
	public function preDispatch()
	{
		$this->_getParentModule()->loadLangPackage();
	}

	public function login()
	{
		$module = $this->_getParentModule();
		$input = Application::get('input');

		// I'd actually like to use the router to route to a different method depending on whether this was a GET or a POST
		if ($input->post->keyExists('submit'))
		{
			$username = $input->post->getRaw('login_user');
			$password = $input->post->getRaw('login_pass');

			$storage = Storage\Factory::factory('Users');

			if (false === $user = $storage->getUserByName($username))
			{
				if (false !== strpos($username, '@', 1))
				{
					$user = $storage->getUserByEmail($username);
				}

				if (false === $user)
				{
					return $module->render('login', array(
						'failed' => true,
						'username' => $username,
					));
				}
			}

			$bcrypt = new Bcrypt();

			if ($bcrypt->match($password, $user['password']))
			{
				if ($input->post->keyExists('login_forever'))
				{
					// Six years of seconds!
					Session::setLifetime(189216000);
				}
				else
				{
					$minutes = $input->post->getInt('login_length');

					// Minimum login time is 15 minutes
					if ($minutes < 15)
					{
						$minutes = 60;
					}

					Session::setLifetime($minutes * 60);
				}

				Session::start();
				$_SESSION['id_user'] = $user['id'];

				// @todo: $module->fire('post_successful_login');

				if (!empty($_SESSION['redirect_url']))
				{
					$url = $_SESSION['redirect_url'];
				}
				else
				{
					$url = Settings::URL;
				}

				Application::get('response')->redirect($url);
			}

			setcookie(Settings::COOKIE_NAME, '', 0, Settings::COOKIE_PATH, Settings::COOKIE_DOMAIN);

			return $module->render('login', array(
				'failed' => true,
				'username' => $username,
			));
		}

		return $module->render('login', array(
			'username' => '',
		));
	}

	public function logout()
	{
		Session::end();

		Application::get('response')->redirect(Settings::URL);
	}
}