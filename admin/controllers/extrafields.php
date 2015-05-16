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

jimport('joomla.application.component.controlleradmin');

class JVisualContentControllerExtraFields extends JControllerAdmin
{
    protected $text_prefix  = 'COM_JVISUALCONTENT_EXTRAFIELDS';

    public function getModel($name = 'ExtraField', $prefix = 'JVisualContentModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

}