<?php

/**
 * smCore Storage - S3
 * This file provides the Amazon S3 file uploader implementation.
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

use smCore\Model\Upload, smCore\Storage\Uploads\AbstractUploads, smCore\Storage\AbstractStorage, smCore\Application,
Aws\S3\S3Client;

class S3 extends AbstractUploads
{
    /**
     * @var s3client The Amazon S3 client object that we're going to use.
     */
    protected $_s3client;
    
    /**
     * @method __construct This creates the s3client object that we will be needing.
     * @todo This object might be better being lazy loaded.
     * @todo If AWS is used elsewhere it would be better for us to use the service builder.
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->_s3client = S3Client::factory(array(
            'key'    => $app['settings']['aws']['key_id'],
            'secret' => $app['settings']['aws']['secret_key'],
        ));
    }
    
    /**
     * @todo Do we delete using the UID or the actual filename?
     */
    public function delete($uid)
    {
        throw new Exception('Not implemented.');
        $this->_s3client->deleteObject(array(
            'bucket' => $this->_app['settings']['aws']['bucket'],
            'key' => $uid
        ));
        $this->_dbDelete($uid);
    }
    
    public function save(Upload $file)
    {
        throw new Exception('Not implemented.');
        $this->_s3client->putObject(array(
            'bucket' => $this->_app['settings']['aws']['bucket'],
            'key' => $file->uid,
            'ACL' => 'public-read',
            'Body' => fopen($file['location'])
        ));
        $this->_dbSave($file);
    }
}