<?php

/**
 * smCore Parsers Storage
 *
 * Creates a parser (BBCode, markdown etc) to make wbesite content easier to develop.
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
 * 
 * @todo Allow parsing through Twig (both calling {{content:Parse()}} and allowing TWIG content to be parsed.
 */

namespace smCore\Storage;

use smCore\Autoloader, smCore\Exception, smCore\Module, smCore\FileIO\Factory as IOFactory;

class Parsers extends AbstractStorage
{
    public function getParser($default = true)
    {
        // @todo return a parse model
    }
}