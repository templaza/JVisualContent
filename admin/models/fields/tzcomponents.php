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

jimport('joomla.filesystem.folder');
JFormHelper::loadFieldClass('list');

JModelList::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jvisualcontent/models');

class JFormFieldTZComponents extends JFormFieldList
{
    protected $type = 'TZComponents';

    protected function getOptions(){
        if(!empty($this -> value) && is_string($this -> value)){
            if(strpos($this -> value,'[') == 0 && strpos($this -> value,']') == (strlen($this -> value) - 1)){
                $this -> value    = preg_replace('/^\[|\]$/i','',$this -> value);
                $this -> value    = preg_replace('/^\'|\'$/i','',$this -> value);
                $this -> value    = explode('\',\'',$this -> value);
            }
        }
        $options    = array();

        if($components = ConfigHelperConfig::getAllComponents()){
            ConfigHelperConfig::loadLanguageForComponents($components);
            $lang   = JFactory::getLanguage();
            foreach ($components as $component)
            {
                if($component != 'com_jvisualcontent') {
                    $path               = JPATH_ADMINISTRATOR . '/components/' . $component . '/views';
//                    $value              = new stdClass();
                    $optgroup_open              = JHtml::_('select.optgroup',JText::_($component));
                    $optgroup_open -> groups    = true;
//                    $value -> value     = '<OPTGROUP>';
//                    $value -> text      = JText::_($component);

                    $options[]                  = $optgroup_open;

                    $items          = array();
                    if (JFolder::exists($path)) {
                        if ($views = JFolder::folders($path)) {
                            foreach ($views as $view) {
                                $v_value = new stdClass();
                                $v_value->value = $component . '.' . $view;
                                $v_value->text  = $view.' ['.JText::_($component).']';
                                $options[]      = $v_value;
                                $items[]        = $v_value;
                            }
                        }
                    }

                    $optgroup_close              = JHtml::_('select.optgroup',JText::_($component));
                    $optgroup_close -> groups    = true;
                    $options[]                  = $optgroup_close;
                }
            }
        }

        return array_merge(parent::getOptions(),$options);
    }
}