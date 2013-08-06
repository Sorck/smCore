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

use smCore\Storage\AbstractStorage, smCore\Storage\Uploads\UploadsInterface, smCore\Model\Upload, smCore\Exception;

abstract class AbstractUploads extends AbstractStorage implements UploadsInterface
{
    /**
     * @method _dbDelete Deletes the record of an upload from the database.
     * @param string $uid The UID of the upload to delete.
     * @return null
     */
    protected function _dbDelete($uid)
    {
        // @todo
    }
    
    /**
     * @method _dbSave Adds an upload file record to the database.
     * @param Upload $file The upload object to store.
     * @return null
     */
    protected function _dbSave(Upload $file)
    {
        // @todo
        $this->_app['db']->query("
            INSERT INTO {db_prefix}uploads
            (uid, size, mime, id_owner)
            VALUES ({string:uid}, {int:size}, {string:mime}, {string:id_owner})",
            array(
                'uid' => $file->uid,
                'size' => $file->size,
                'mime' => $file->mime,
                'id_owner' => $file->id_owner,
            )
        );
        throw new Exception();
    }
    
    public function get($uid)
    {
        $result = $this->_app['db']->query("
    		SELECT *
			FROM {db_prefix}uploads
			WHERE uid = {string:uid}",
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