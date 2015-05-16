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

// Register helper class
JLoader::register('JVisualContentHelper', dirname(__FILE__) . '/helpers/jvisualcontent.php');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_jvisualcontent')) {
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JLoader::import('framework',JPATH_ADMINISTRATOR.'/components/com_jvisualcontent/includes');

// Variable application input object
$input  = JFactory::getApplication() -> input;

// Register helper class
JLoader::register('TZ_ShortCodeFunctions', dirname(__FILE__) . '/libraries/functions.php');

// Execute the task.
$controller	= JControllerLegacy::getInstance('JVisualContent');

$controller->execute($input->get('task'));


$controller->redirect();