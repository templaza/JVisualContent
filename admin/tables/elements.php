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

class JVisualContentTableElements extends JTable
{
    // @var int Primary key
    var $id 				= null;
    // @var string
    var $name               = null;
    // @var string
    var $title              = null;
    // @var int
    var $published          = null;
    // @var string
    var $class_icon         = null;
    // @var string
    var $image_icon         = null;
    // @var int
    var $protected          = 0;
    // @var string
    var $fields_id          = null;
    // @var string
    var $html               = null;
    // @var int
    var $ordering           = 0;
    // @var string
    var $introtext          = null;

    function __construct(&$db) {
        parent::__construct('#__tz_jvisualcontent_elements','id',$db);
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

    public function bind($array, $ignore = '')
    {
        if(isset($array['extrafields'])
            && !empty($array['extrafields'])){
            if(is_array($array['extrafields'])){
                $array['fields_id']  = implode(',',$array['extrafields']);
            }
        }

        return parent::bind($array, $ignore);
    }


    public function store($updateNulls = false)
    {
        // Verify that the alias is unique
        $table = JTable::getInstance('Elements', 'JVisualContentTable');

        if ($table->load(array('name' => $this->name)) && ($table->id != $this->id || $this->id == 0))
        {
            $this->setError(JText::_('COM_JVISUALCONTENT_ERROR_TYPES_UNIQUE_NAME'));

            return false;
        }

        return parent::store($updateNulls);
    }
}