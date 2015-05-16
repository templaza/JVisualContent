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

class JVisualContentTableExtraFields extends JTable
{
    // @var int Primary key
    var $id 				= null;
    // @var string
    var $name               = null;
    // @var title
    var $title              = null;
    // @var int
    var $published          = null;
    // @var string
    var $type               = null;
    // @var string
    var $option_value       = null;
    // @var int
    var $protected          = 0;
    // @var string
    var $css_code           = null;
    // @var int
    var $ordering           = 0;
    // @var string
    var $description        = null;

    function __construct(&$db) {
        parent::__construct('#__tz_jvisualcontent_extrafields','id',$db);
    }

    public function check()
    {
        if (trim($this -> title) == '')
        {
            $this -> setError(JText::_('COM_CONTENT_WARNING_PROVIDE_VALID_NAME'));

            return false;
        }

        if (trim($this -> name) == '')
        {
            $this->name = $this->title;
        }

        $this -> name = JApplication::stringURLSafe($this -> name);

        if (trim(str_replace('-', '', $this -> name)) == '')
        {
            $this -> name = JFactory::getDate()->format('Y_m_d_H_i_s');
        }else{
            $this -> name   = str_replace('-','_',$this -> name);
        }

        if($this ->protected){
            $this -> published  = 1;
        }

        return true;
    }

    public function store($updateNulls = false)
    {
        // Verify that the alias is unique
        $table = JTable::getInstance('ExtraFields', 'JVisualContentTable');

        if ($table->load(array('name' => $this->name)) && ($table->id != $this->id || $this->id == 0))
        {
            $this->setError(JText::_('COM_JVISUALCONTENT_ERROR_EXTRAFIELDS_UNIQUE_NAME'));

            return false;
        }

        return parent::store($updateNulls);
    }
}