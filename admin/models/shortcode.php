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

jimport('joomla.application.component.modellist');
require_once(JPATH_ADMINISTRATOR.'/components/com_jvisualcontent/models/element.php');


class JVisualContentModelShortCode extends JVisualContentModelElement{

    protected function populateState()
    {
        parent::populateState();
        $app    = JFactory::getApplication();
        $input  = $app -> input;

        $this -> setState($this -> getName().'.element_type',$input -> get('element_type'));
        $this -> setState($this -> getName().'.element_source',$input -> get('element_source'));
        $this -> setState($this -> getName().'.id',$input -> getInt('id'));

        $this -> setState($this -> getName().'.tzshortcode',$input -> get('tzshortcode'));
        $this -> setState($this -> getName().'.tzaddnew',$input -> get('tzaddnew'));
        $this -> setState($this -> getName().'.action',$input -> get('action',''));

        $data   = $input -> get('data',array(),'array');

        if(isset($data['element_id'])) {
            $this->setState($this->getName() . '.id',(int) $data['element_id']);
        }
        if(isset($data['name'])){
            $this -> setState($this -> getName().'.element_type',$data['name']);
        }
        if(isset($data['params'])){
            $this -> setState($this -> getName().'.params',$data['params']);
        }
        $this -> setState($this -> getName().'.data',$data);
    }

    public function getForm($data = array(), $loadData = true){
        $form = $this->loadForm('com_jvisualcontent.shortcode', 'shortcode', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        return $form;
    }

    protected function loadFormData()
    {
        $data = parent::loadFormData();
        if($_data  = $this -> getState($this -> getName().'.data')){
            if(isset($_data['params']) && isset($_data['params']['background_image'])){
                $data -> params['background_image'] = $_data['params']['background_image'];
            }
        }
        return $data;

    }

//    public function getItem($pk = null){
//        if($item = parent::getItem($pk)){
//            $this -> getSystemShortCode($item);
//            return $item;
//        }
//        return false;
//    }
//
//    public function getSystemShortCode($item=null){
////        if($item = $this -> getItem()){
//            var_dump('gdfg'); die();
////        }
//    }
}