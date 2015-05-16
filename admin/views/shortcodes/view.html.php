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

JHtml::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jvisualcontent/helpers/html');

class JVisualContentViewShortCodes extends JViewLegacy{
    protected $items                = null;
    protected $item                 = null;
    protected $children             = null;
    protected $jscontent_ename      = null;
    protected $html                 = null;
    protected $model_ids            = null;
    protected $elementItem          = null;

    public function display($tpl=null){
        $this -> items  = $this -> get('Items');
        parent::display($tpl);
    }
}