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


class JVisualContentHelper
{
    /**
     * Configure the Linkbar.
     *
     * @param   string    The name of the active view.
     */
    public static function addSubmenu($vName)
    {
        JHtmlSidebar::addEntry(
            JText::_('COM_JVISUALCONTENT_SUBMENU_ELEMENTS'),
            'index.php?option=com_jvisualcontent&view=elements',
            $vName == 'elements'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_JVISUALCONTENT_SUBMENU_EXTRAFIELDS'),
            'index.php?option=com_jvisualcontent&view=extrafields',
            $vName == 'extrafields'
        );
    }
}