<?php

/**
 * smCore Storage - Filesytem
 * This file provides the Filesystem file uploader implementation.
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

use smCore\Model\Upload, smCore\Storage\Uploads\AbstractUploads, smCore\Storage\AbstractStorage, smCore\Exception;

class Filesystem extends AbstractUploads
{
    public function delete($uid)
    {
        unlink($this->_app['settings']['uploads']['directory'] . '/' . $uid);
        $this->_dbDelete($uid);
    }
    
    public function save(Upload $file)
    {
        $new_location = $this->_app['settings']['uploads']['directory'] . '/' . $file->uid;
        move_uploaded_file($file->location, $new_location);
        // Set the new location
        $file->location = 'Filesystem://' . $file->uid;
        // Put it into the database
        $this->_dbSave($file);
    }
}