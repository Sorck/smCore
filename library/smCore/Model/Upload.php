<?php

/**
 * smCore Upload Model
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

namespace smCore\Model;

use smCore\Application, smCore\Storage\Factory as StorageFactory, smCore\Exception, smCore\Model\User;

class Upload extends AbstractModel
{
    /**
     * @var smCore\Storage\AbstractUploads $_store A copy of the storage variable required for saving model data.
     */
    protected $_store;
    
    /**
     * @var string $uid The unique identifier for the file.
     */
    public $uid = '_FILES';
    
    /**
     * @var string $location The location of the file.
     * Valid locations are
     * * S3://bucket/path/to/file
     * * Filesystem://path/to/file (path/to/file is relative to your uploads storage directory)
     */
    public $location = null;
    
    /**
     * @public string $uri URI at which the file is available.
     * If the URI begins cdn:// then the cdn uri setting will replace cdn://
     */
    public $uri = null;
    
    /**
     * @var string $mime The mime type of the file.
     */
    public $mime = null;
    
    /**
     * @var int $size The file size.
     **/
    public $size = 0;
    
    /**
     * @var smCore\Model\User $owner The owner of the file.
     * This can be used for verifying quotas or tracking who uploaded bad files etc.
     */
    public $owner;
    
    /**
     * @method __construct
     * @param Application $app
     * @param $data The data to set on the file.
     */
    public function __construct(Application $app, $data = null)
    {
		parent::__construct($app);
        // Make sure our storage system is ready for us
        $this->_store = $app['storage_factory']->factory('Uploads\\' . $app['settings']['uploads']['class']);
        $this->owner = $this->_app['user'];
        
        if(is_array($data))
        {
            $this->setData($data);
        }
	}
    
    /**
     * @method get Returns an instance of Upload correlating to the supplied UID
     * @param string $uid The UID to look for.
     * @returns Upload
     * @throws Exception
     */
    public function get($uid)
    {
        return $this->_store->get($uid);
    }
    
    /**
     * @method save Saves the upload.
     **/
    public function save()
    {
        // If we're on location _FILES then we need to update our data
        if($this->uid === '_FILES') 
        {
            // Have they specified a filename for this upload?
            $post_key = $this->location ?: 'upload';
            // Make sure it exists
            if(!isset($_FILES[$post_key]))
            {
                throw new Exception('No file upload key exists.');
            }
            if(!($_FILES[$post_key]['size'] > 0))
            {
                throw new Exception('Files must have a size.');
            }
            // Create a unique identifier
            $this->uid = $this->_makeUID($_FILES[$post_key]['name']);
            // Now make sure the location is absaloute
            $this->location = $_FILES[$post_key]['tmp_name'];
            // Make sure we have the right mime type
            /**
             * @todo Make sure it's an allowable mime type
             * @todo Verify the file is the type it says it is
             */
            $this->mime = $_FILES[$post_key]['type'];
            // And make sure we've set the size.
            $this->size = $_FILES[$post_key]['size'];
        }
        $this->_store->save($this);
    }
    
    /**
     * @method setData Sets file data and verifies it.
     * @param array $data The array of data which we will set the internal data based upon.
     * @throws smCore\Exception
     */
    public function setData(array $data)
    {
        foreach($data as $key => $value)
        {
            switch($key)
            {
                case 'location':
                    // wait until the end
                    break;
                case 'mime:
                    // @todo make sure it's a valid mime
                    $this->mime = $value;
                    break;
                case 'uid':
                    // @todo make sure it's a string
                    $this->uid = $value;
                    break;
                case 'size':
                    // Make sure it's allowable
                    if(!((int) $value > 0))
                    {
                       // This is definitely not right!
                       throw new Exception('File size must be a positive integer.');
                    }
                    $this->size = (int) $size;
                    break;
                case 'owner':
                    if(!($value instanceof User))
                    {
                        throw new Exception('Invalid user.');
                    }
                    $this->owner = $value;
                    break;
            }
        }
        if(isset($data['location']))
        {
            if($this->uid === '_FILES')
            {
                // @todo verify that it's a valid key
            }
            else
            {
                /**
                 * @todo verify that it's a valid location
                 */
                 
            }
            throw new Exception('Feature not implemented ' . __CLASS__ . '::' . __METHOD__ . ' (key = location)');
        }
    }
    
    /**
     * @method _makeUID Create a pseudo random unique identifier.
     * @param string $real_filename The filename for which we are creating the UID.
     * @return string The UID for the file in question.
     */
    protected function _makeUID($real_filename)
    {
        return substr(sha1(rand(0,10000)) . $filename . time() . $this->['app']['settings']['site_key'],0,20) .
            '_' . substr(md5($filename . $this->['app']['settings']['site_key'] . microtime()), 10, 12);
    }
}