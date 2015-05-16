<?php
/*------------------------------------------------------------------------

# JVisualContent Extension

# ------------------------------------------------------------------------

# author    DuongTVTemPlaza

# copyright Copyright (C) 2012 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die;

JLoader::import('defines',JPATH_ADMINISTRATOR.'/components/com_jvisualcontent/includes');

if(!class_exists('JVisualContentUri')){
    JLoader::import('uri',JVISUALCONTENT_LIBRARIES);
}

if(!class_exists('JVisualContentShortCode')){
    JLoader::import('shortcode',JVISUALCONTENT_LIBRARIES);
}

if(!class_exists('JVisualContentFunctions')){
    JLoader::import('functions',JVISUALCONTENT_LIBRARIES);
}

function jvisualcontentimport($path){
    JLoader::import($path,JVISUALCONTENT_LIBRARIES);
}