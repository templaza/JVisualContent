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

// Require model class of joomla
jimport('joomla.application.component.modellist');

jvisualcontentimport('tree_functions');

class JVisualContentModelShortcodes extends JModelList{

    protected function populateState($ordering = null, $direction = null)
    {
        parent::populateState($ordering,$direction);

        $app            = JFactory::getApplication();
        $doc            = JFactory::getDocument();
        $input          = $app -> input;
        $data_params    = $input -> get('params',null,'array');
        $ids            = array();

        $this -> setState('data_params',$data_params);

        $this -> setState('shortcodes.shortcode',null);

        if(count($data_params)){
            foreach($data_params as $d_param){
                if(isset($d_param['element_id']) && $d_param['element_id'] != 'row' && $d_param['element_id'] != 'column'){
                    $ids[]    = $d_param['element_id'];
                }
            }
        }

        if(count($ids)){
            $this -> setState('list.limit',count($ids));
        }

        $this -> setState($this->context . '.id',$ids);

    }

    // Method to get a JDatabaseQuery object for retrieving the data set from a database.
    protected function getListQuery(){
        $db     = $this -> getDbo();
        $query  = $db -> getQuery(true);

        $query -> select($this -> getState('list.select','a.*'));
        $query -> from($db -> quoteName('#__tz_jvisualcontent_elements').' AS a');

        // Filter by published state
        $published  = $this -> getState('filter.published');
        if (is_numeric($published)){
            $query->where('a.published = ' . (int) $published);
        }elseif ($published === ''){
            $query->where('(a.published = 0 OR a.published = 1)');
        }

        // Filter by search in name.
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            if (stripos($search, 'id:') === 0)
            {
                $query->where('a.id = ' . (int) substr($search, 3));
            }else
            {
                $search = $db->quote('%' . $db->escape($search, true) . '%');
                $query->where('(a.title LIKE ' . $search . ' OR a.name LIKE ' . $search . ')');
            }
        }

        // Filter by type
        if($type   = $this -> getState('filter.type')){
            $query -> where('a.type = '.$db -> quote($type));
        }

        // Filter by name
        if($name   = $this -> getState('filter.name')){
            if(is_string($name)){
                $query -> where('a.name = '.$db -> quote($name));
            }elseif(is_array($name)){
                $query -> where('a.name IN( \''.join('\',\'',$name).'\')');
            }
        }

        // Add the list ordering clause.
        $orderCol = $this->getState('list.ordering', 'a.title');
        $orderDirn = $this->getState('list.direction', 'asc');
        if ($orderCol == 'a.ordering')
        {
            $orderCol = 'a.title ' . $orderDirn . ', a.ordering';
        }

        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }



    public function getItems(){

        if($types  = parent::getItems()){
            $_types = array();
            foreach($types as $type){
                $_types[$type -> id]    = $type;
            }
            $types  = $_types;
            $types['row']   = new stdClass();
            $types['row'] -> id = 'row';
            $types['row'] -> name = 'tz_row';
            $types['row'] -> html = '<div class="jvc_row {css_class} {width} {el_class}" style="text-align: {text_align};">[/type]</div>';
            $types['column']   = new stdClass();
            $types['column'] -> id = 'column';
            $types['column'] -> name = 'tz_column';
            $types['column'] -> html = '<div class="{css_class} {width} {el_class}">[/type]</div>';

            return $types;
        }
        return false;

//        if($data_params = $this -> getState('data_params')){
//            $data_params    = $this -> prepare_attr($data_params);
//
//            if(count($data_params)){
//                foreach($data_params as &$d_param){
//                    $d_param['html']    = $types[$d_param['element_id']] -> html;
//                }
//            }
//
//
//            $shortcode  = JVisualContentTree::generate_shortcode($data_params);
//            $html       = JVisualContentShortCode::do_shortcode($shortcode);
//
//            if($css = JVisualContentShortCode::get_custom_css()) {
//                $html .= '<style type="text/css">'.$css.'</style>';
//            }
//            $array  = array('shortcode' => $shortcode,'html' => $html);
//            echo (json_encode($array));
//            die();
//        }
    }

//    public function prepare_attr($params){
//        if($params && count($params)){
//            $_params            = $params;
//            $date               = JFactory::getDate();
//            $default_css_class  = array('background_image','background_color','background_style',
//                'border_top_width','border_bottom_width','border_right_width','border_left_width','border_color'
//            ,'border_style','margin_top','margin_bottom','margin_right','margin_left','padding_top','padding_bottom'
//            ,'padding_right','padding_left');
//            $default_el_class  = array('el_class','col_lg_offset','col_md_offset','col_sm_offset','col_xs_offset'
//            ,'col_lg_size','col_md_size','width','col_xs_size','hidden_lg','hidden_md','hidden_sm','hidden_xs');
//
//            foreach($_params as &$param){
//                $param['prepare_params']    = array();
//                $param['docs']['css']       = array();
//
//                if($param['element_id'] == 'row' || $param['element_id'] == 'column'){
//                    $css_class                  = '';
//                    $el_class                   = '';
//
//                    if($param['params'] && count($param['params'])){
//                        foreach($param['params'] as $name => $value){
//                            if(in_array($name,$default_css_class) || in_array($name,$default_el_class)){
//                                if(in_array($name,$default_css_class)){
//                                    if($name == 'background_image'){
//                                        $url        = $value;
//                                        if(!preg_match('/http/m',$url)){
//                                            $url    = JUri::root().$value;
//                                        }
//                                        $css_class  .= str_replace('_', '-', $name) . ': url(' . $url . '); ';
//                                    }elseif($name == 'background_style') {
//                                        if($value == 'cover' || $value == 'contain'){
//                                            $css_class .= 'background-size: ' . $value . '; ';
//                                        }elseif($value == 'repeat' || $value == 'no-repeat') {
//                                            $css_class .= 'background-repeat: ' . $value . '; ';
//                                        }
//                                    }else{
//                                        $css_class  .= str_replace('_', '-', $name) . ': ' . $value . '; ';
//                                    }
//
//                                }
//                                if(in_array($name,$default_el_class)){
//                                    if($param['element_id'] == 'column' && $name == 'width') {
//                                        $width  = 1;
//                                        if(strpos($value,'/')){
//                                            $width  = explode('/',$value);
//                                            if(count($width)){
//                                                $_width = null;
//                                                foreach($width as $w){
//                                                    if($_width == null){
//                                                        $_width = $w;
//                                                    }else{
//                                                        $_width /= $w;
//                                                    }
//                                                }
//                                                if($_width != null){
//                                                    $width  = $_width;
//                                                }
//                                            }
//                                        }
//                                        $el_class  .= 'col-sm-'.($width * 12).' ';
//                                    }else{
//                                        $el_class .= $value.' ';
//                                    }
//                                }
//                            }
//                            else{
//                                $param['prepare_params'][$name] = $value;
//                            }
//                        }
//                    }
//                    $unix   = str_replace('.','',microtime(true));
//                    $param['prepare_params']['css_class']   = 'tz_custom_css_'.$unix;
//                    $param['docs']['css']                   = '.tz_custom_css_'.$unix.'{'.trim($css_class).'}';
//
//                    $param['prepare_params']['el_class']   = trim($el_class);
//
//                    if(preg_match('/(\[\w+)(.*?)(\].*?\[\/\w+\])/m',$param['shortcode'],$match)){
//                        $param['shortcode'] = $match[1].' css_class="'.$param['docs']['css']
//                            .'" el_class="'.$param['prepare_params']['el_class'].'"'.$match[3];
//                    }
//                }else{
//                    $param['prepare_params']    = $param['params'];
//
//                    if(is_array($param['params']) && count($param['params'])) {
//                        $attr   = null;
//                        foreach($param['params'] as $name => $value){
//                            if($value && is_array($value)){
//                                $attr   .= ' '.$name.'="'.implode(' ',$value).'"';
//                            }else{
//                                $attr   .= ' '.$name.'="'.$value.'"';
//                            }
//                        }
//                        if(preg_match('/([\[|\{]\w+)(.*?)([\]|\}].*?[\[|\{]\/\w+[\]|\}])/m',$param['shortcode'],$match)){
//                            $param['shortcode'] = preg_replace('/([\[|\{]\w+)(.*?)([\]|\}].*?[\[|\{]\/\w+[\]|\}])/m','$1'.$attr.'$3',$param['shortcode']);
//                        }
//                    }
//                }
//            }
//            return $_params;
//        }
//        return $params;
//    }
}