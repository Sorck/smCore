<?php

/**
 * smCore Storage - AbstractUploads
 * This file provides the database functions required for file management.
 *
 * @package smCore
 * @author smCore Dev Team
 * @version 1.0 Alpha
 * @license MPL 1.1
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

namespace smCore\Storage\Uploads;

use smCore\Storage\AbstractStorage, smCore\Storage\Uploads\UploadsInterface, smCore\Models\Upload;

abstract class AbstractUploads extends AbstractStorage implements UploadsInterface
{
    /**
     * @todo Add database functions.
     */
    public function get($uid)
    {
        $result = $this->_app['db']->query("
    		SELECT *
			FROM {db_prefix}uploads
			WHERE LOWER(uid) = {string:uid}",
			array(
				'uid' => $uid,
			)
		);

		if ($result->rowCount() < 1)
		{
			return false;
		}

		$row = $result->fetch();
        
        return new Upload($row);
    }
}