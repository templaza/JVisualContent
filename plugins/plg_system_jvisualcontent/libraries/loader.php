<?php
/*------------------------------------------------------------------------

# JVisual Content Extension

# ------------------------------------------------------------------------

# author    DuongTVTemPlaza

# copyright Copyright (C) 2012 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

if (!class_exists('JVisualContentLoader'))
{

    class JVisualContentLoader{

        protected static $imported  = array();
        protected static $classes  = array();
        public static $body      = null;

        public static function load($body){
            self::$body = $body;
        }
    }
}

function plgsys_jvisualcontentimport($path)
{
    return JLoader::import($path,JVISUALCONTENT_SYSTEM_PLUGIN.'/libraries');
}

if(!class_exists('JVisualContentDocument')){
    plgsys_jvisualcontentimport('jvisualcontent.document.document');
}