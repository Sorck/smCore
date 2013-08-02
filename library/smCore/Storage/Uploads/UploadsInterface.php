<?php

/**
 * smCore Storage - UploadsInterface
 * This file provides the interface that is required to implement
 * a file uploader.
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

use smCore\Model\Upload;

interface UploadsInterface
{
    /**
     * @method get Returns a series of information about a file from the database.
     * @param $uid The UID of the file to get info about.
     * @returns Upload object.
     */
    public function get($uid);
    
    /**
     * @method delete Deletes a file from the database and from storage.
     * @param $uid The UID of the file to delete.
     * The reason we use the UID is to reduce database queries - we can send delete requests for non existent items. 
     * @return null
     */
    public function delete($uid);
    
    /**
     * @method save Saves a file into the database and onto the file server.
     * This method does two things:
     * * It will save the file onto a storage system with a unique identifier.
     * * It will then put records into the database describing the file, location, owner etc.
     * @param $file The Upload object that is to be saved.
     * @return null
     */
    public function save(Upload $file);
}