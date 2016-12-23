<?php
/*
 * CKFinder
 * ========
 * http://ckfinder.com
 * Copyright (C) 2007-2011, CKSource - Frederico Knabben. All rights reserved.
 *
 * The software, this file and its contents are subject to the CKFinder
 * License. Please read the license.txt file before using, installing, copying,
 * modifying or distribute this file or part of its contents. The contents of
 * this file is part of the Source Code of CKFinder.
 */
if (!defined('IN_CKFINDER')) exit;

/**
 * @package CKFinder
 * @subpackage Utils
 * @copyright CKSource - Frederico Knabben
 */

/**
 * Simple class which provides some basic API for creating XML nodes and adding attributes
 *
 * @package CKFinder
 * @subpackage Utils
 * @copyright CKSource - Frederico Knabben
 */
class Ckfinder_Connector_Utils_XmlNode
{
    /**
     * Array that stores XML attributes
     *
     * @access private
     * @var array
     */
    var $_attributes = array();
    /**
     * Array that stores child nodes
     *
     * @access private
     * @var array
     */
    var $_childNodes = array();
    /**
     * Node name
     *
     * @access private
     * @var string
     */
    var $_name;
    /**
     * Node value
     *
     * @access private
     * @var string
     */
    var $_value;

    /**
     * Create new node
     *
     * @param string $nodeName node name
     * @param string $nodeValue node value
     * @return C