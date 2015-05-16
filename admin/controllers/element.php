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
jimport('joomla.application.component.controllerform');
JHtml::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jvisualcontent/helpers/html');
require_once (JPATH_ADMINISTRATOR.'/components/com_jvisualcontent/libraries/tree_functions.php');

class JVisualContentControllerElement extends JControllerForm{

    private $tree           = '';
    private $child_count    = array();

    public function listfields(){
        $app        = JFactory::getApplication();
        $doc        = JFactory::getDocument();
        $input      = $app -> input;

        $data       = $input -> get('jform',null,'array');
        if(count($data)){
            foreach($data as $name => &$_data){
                if(is_array($_data) && count($_data)) {
                    foreach($_data as $n => &$d){
                        if($n != 'editor') {
                            if(is_array($d) && count($d)) {
                                $d  = array_map(function($val){
                                    $_val   = addslashes($val);
                                    $_val   = htmlspecialchars($_val);
                                    return $_val;
                                },$d);
                                $d  = implode(' ',$d);
                            }else{
                                $d  = addslashes($d);
                                $d  = htmlspecialchars($d);
                            }
                        }
                    }
                }else{
                    if($name != 'editor') {
                        $_data = addslashes($_data);
                        $_data = htmlspecialchars($_data);
                    }
                }
            }
        }

        $doc -> addScriptDeclaration('
            window.parent.jQuery.editExtrafield('.str_replace('&nbsp;','',json_encode($data)).');
            window.parent.jQuery.fancybox.close();
        ');
    }

    public function generate(){

        $app        = JFactory::getApplication();
        $doc        = JFactory::getDocument();
        $input      = $app -> input;

        if($data_params = $input -> get('params',null,'array')){
            if(count($data_params)){
                foreach($data_params as $d_param){

                }
            }
        }
        die();
    }

    public function insertEditorText(){
        $input              = $this -> input;
        $data               = $input -> get('jform',null,'array');
        $shortcode          = '';
        $shortcode_open     = '';
        $shortcode_close    = '';
        if($data){
            $data   = $data['attrib'];
            if(count($data)){

                $new_array  = $this -> shortcode_prepare_data($data);

                $model      = JModelLegacy::getInstance('Elements','JVisualContentModel',array('ignore_request' => true));

                TZ_ShortCodeTree::init_tree($new_array);
                $el_types   = TZ_ShortCodeTree::get_element_types();
                $model -> setState('filter.name',$el_types);

                TZ_ShortCodeTree::general_tree($new_array,$model -> getItems());
                $doc    = JFactory::getDocument();
                $doc -> addScriptDeclaration('
                    window.parent.jInsertEditorText("'.TZ_ShortCodeFunctions::jsaddslashes(implode('',TZ_ShortCodeTree::$shortcode)).'","'.$input -> get('e_name').'");
                    window.parent.jQuery.fancybox.close();
                ');
            }
        }
    }

    function prepare_attrib($tag_name,$attr){
        $attrib = array();
        if($tag_name == 'tz_row' || $tag_name == 'tz_column'){
            $attrib['css_class']    = '';
            $attrib['offset']    = '';
            $css_style  = array('background_color','background_image','background_style','margin_top'
            ,'margin_right','margin_left',' padding_top','padding_right','padding_bottom','padding_left'
            ,'border_color','border_style','border_top_width','border_right_width','border_bottom_width'
            ,'border_left_width','font_color');

            $offset = array('width','tz_col_lg_size','tz_col_md_size','tz_col_xs_size','tz_lg_offset_size'
            ,'tz_md_offset_size','tz_sm_offset_size','tz_xs_offset_size','tz_hidden_lg','tz_hidden_md'
            ,'tz_hidden_sm','tz_hidden_xs');

            if($attr){
                foreach($attr as $key => $val){
                    if($val){
                        if(in_array($key,$css_style) || in_array($key,$offset)){
                            if(in_array($key,$css_style)){
                                if($key == 'font-color'){
                                    $attrib['css_class']    .= ' color:"'.$val.'"';
                                }else{
                                    $attrib['css_class']    .= ' '.str_replace('_','-',$key).': '.$val;
                                }
                            }
                            if(in_array($key,$offset)){
                                if($key == 'width'){
                                    if(preg_match('/\d{1,2}\/?/',$val)){
                                        $attrib['offset']    .= ' tz_col-sm-'.($val * 12);
                                    }else{
                                        $attrib['offset']    .= ' '.$val;
                                    }
                                }else{
                                    $attrib['offset']    .= ' '.$val;
                                }
                            }
                        }else{
                            $attrib[$key]   = $val;
                        }
                    }
                }
                if($attrib['css_class']){
                    $attrib['css_class']    = '.tz_custom_css_'.str_replace('.','',microtime(true))
                        .'{'.trim($attrib['css_class']).'}';
                }
                if($attrib['offset']){
                    $attrib['offset']   = trim($attrib['offset']);
                }
            }
        }else{
            $attrib = $attr;
        }
        return $attrib;
    }

    public $tzparent=array();
    function get_element_types($arr,&$element_types  = array()){
        while($value = current($arr)){
            $key    = key($arr);
            $element_types[$key]  = '';
            $this -> tzparent[$key] = $value['parent'];
            if(isset($value['element_type'])){
                $element_types[$key]  = $value['element_type'];
            }
            if(is_array($value) && isset($value['children'])){
                $this -> get_element_types($value['children'],$element_types);
            }
            next($arr);
        }
        return $element_types;
    }

    private $element_types  = array();

    function get_shortcode($arr,&$shortcode='',&$element_types){

        if($arr){
            foreach($arr as $value){
                $shortcode  .= '['.$value['element_type'];
                $attr   = '';
                $shortcode  .= $attr.']';

                if(is_array($value) && ((isset($value['children']) && !$value['children'])
                        || !isset($value['children']))){
                    $shortcode  .= '[/'.$value['element_type'].']';
                    $this -> child_count[$value['parent']] = $this -> child_count[$value['parent']]-1;
                }
//                $next   = next($arr);
                if(isset($value['parent'])){
                    if(isset($this -> child_count[$value['parent']])){
                        if(($this -> child_count[$value['parent']] - 1) <= 0){
                            if(isset($element_types[$value['parent']])){
                                $shortcode  .= '[/'.$element_types[$value['parent']].']';
                            }
                        }
                    }
                }
            }
        }
    }
    function shortcode_prepare_data($arr,&$new_arr = array()){
        while($value = current($arr)){
            $key    = key($arr);
            $child  = $value;
//            unset($child['children']);
            if(isset($value['element_type'])){
                $new_arr[$key]   = $value;
                $this -> element_types[$key]  = $value['element_type'];
            }
            if(is_array($value) && isset($value['children']) && count($value['children'])){
                $this -> shortcode_prepare_data($value['children'],$new_arr);
            }
            next($arr);
        }
        return $new_arr;
    }

    public function insertDataEditor(){
        // Check for request forgeries.
        JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));

        $app    = JFactory::getApplication();
        $doc    = JFactory::getDocument();

        $post   = JRequest::get();
        $model  = $this -> getModel();
        $model -> setState($this->context.'.id',$this -> input -> getInt('id'));
        $item   = $model -> getItem();
        $html   = $item -> html;

        $view   = $this -> getView($app -> input -> get('view'),$doc -> getType(),'JVisualContentView');
        $view -> assign('item',$item);
        $view -> setLayout($app -> input -> get('layout'));

        if(preg_match_all('/\{(.*?)\}/msi',$item -> html,$matches)){
            if(count($matches) > 1){
                $matches    = $matches[1];
                foreach($matches as $name){
                    $html   = str_replace('{'.$name.'}',$post[$name],$html);
                }
            }
        }
        $e_name     = $app->input->getCmd('e_name');
        $formUrl    = 'index.php?option=com_jvisualcontent&view=elements&layout=modal&tmpl=component&e_name='
            .$e_name.(($tzmodal = $this -> input -> getCmd('tzmodal'))?'&tzmodal='.$tzmodal:'')
            .(($typeId = $this -> input -> getInt('type_id'))?'&type_id='.$typeId:'').'&'.JSession::getFormToken().'=1';

        if(JRequest::getCmd('tzmodal',null)){
            $doc -> addScriptDeclaration('
                jQuery(document).ready(function(){
                    window.parent.jQuery.tzAddHtmlToRow("'
                .addslashes(preg_replace('/(\r\n|\n|\r)/','',trim($view -> loadTemplate('element_button').$html))).'");
                });
            ');
        }else{
            $doc -> addScriptDeclaration('
            jQuery(document).ready(function(){
                window.parent.jInsertEditorText("'.addslashes(preg_replace('/(\r\n|\n|\r)/','',trim($html))).'","'.JRequest::getCmd('e_name').'");
                window.parent.SqueezeBox.close();
            });
            ');
        }
    }
}