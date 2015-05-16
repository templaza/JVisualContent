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

$state              = $this -> state;
$html               = null;
$item               = $this -> item;
$model_ids          = $this -> model_ids;
$shortcode          = array();
$head               = array('css' => array(),'js' => array());
$htmlNewElement     = null;
$htmlNewElement2    = null;

if(($state -> get('shortcode.tzshortcode') && $state -> get('shortcode.tzaddnew'))
    || ($state -> get('shortcode.element_source') == 'main' && ($state -> get('shortcode.element_type') == 'row'
        || $state -> get('shortcode.element_type') == 'readmore'))){ // Element is readmore or row or element have row
    if($state -> get('shortcode.element_type') == 'readmore') {
        $html   = JHtml::_('JVisualContent.jsaddslashes',$this ->loadTemplate('readmore'));

        $shortcode[$model_ids -> readmore]['id']          = $model_ids -> readmore;
        $shortcode[$model_ids -> readmore]['element_id']  = 'readmore';
        $shortcode[$model_ids -> readmore]['name']        = 'readmore';
        $shortcode[$model_ids -> readmore]['parent_id']   = '';
        $shortcode[$model_ids -> readmore]['params']      = '';
//        $shortcode[$model_ids -> readmore]['shortcode'] = '[readmore][/readmore]';
        $shortcode[$model_ids -> readmore]['shortcode'] = '<hr id="system-readmore" />';
    }else{
        $html = JHtml::_('JVisualContent.jsaddslashes', $this->loadTemplate('row'));

        $shortcode[$model_ids->tz_row] = array();
        $shortcode[$model_ids->tz_row]['id'] = $model_ids->tz_row;
        $shortcode[$model_ids->tz_row]['element_id'] = 'row';
        $shortcode[$model_ids->tz_row]['name'] = 'tz_row';
        $shortcode[$model_ids->tz_row]['parent_id'] = '';
        $shortcode[$model_ids->tz_row]['params'] = array(
            "background_color" => "",
            "background_image" => "",
            "background_style" => "",
            "border_color" => "",
            "border_style" => "",
            "el_class" => "",
            "margin_top" => "",
            "margin_right" => "",
            "margin_bottom" => "",
            "margin_left" => "",
            "padding_top" => "",
            "padding_right" => "",
            "padding_bottom" => "",
            "padding_left" => "",
            "border_top_width" => "",
            "border_right_width" => "",
            "border_bottom_width" => "",
            "border_left_width" => "",
            "width" => "",
            "text_align" => "");
        $shortcode[$model_ids->tz_row]['shortcode'] = '[tz_row][/tz_row]';

        $shortcode[$model_ids->tz_column] = array();
        $shortcode[$model_ids->tz_column]['id'] = $model_ids->tz_column;
        $shortcode[$model_ids->tz_column]['element_id'] = 'column';
        $shortcode[$model_ids->tz_column]['name'] = 'tz_column';
        $shortcode[$model_ids->tz_column]['parent_id'] = $model_ids->tz_row;
        $shortcode[$model_ids->tz_column]['params'] = array("background_color" => "",
            "background_image" => "",
            "background_style" => "",
            "border_color" => "",
            "border_style" => "",
            "font_color" => "",
            "el_class" => "",
            "margin_top" => "",
            "margin_right" => "",
            "margin_bottom" => "",
            "margin_left" => "",
            "padding_top" => "",
            "padding_right" => "",
            "padding_bottom" => "",
            "padding_left" => "",
            "border_top_width" => "",
            "border_right_width" => "",
            "border_bottom_width" => "",
            "border_left_width" => "",
            "width" => "1/1",
            "col_lg_size" => "",
            "col_md_size" => "",
            "col_xs_size" => "",
            "col_lg_offset" => "",
            "col_md_offset" => "",
            "col_sm_offset" => "",
            "col_xs_offset" => "",
            "hidden_xs" => "",
            "hidden_sm" => "",
            "hidden_md" => "",
            "hidden_lg" => "");
        $shortcode[$model_ids->tz_column]['shortcode'] = '[tz_column][/tz_column]';
    }
//    $html   = $this ->loadTemplate('row');
}elseif($this -> item){ // Element don't have row
    $html   = JHtml::_('JVisualContent.jsaddslashes',$this ->loadTemplate('element'));
}

if($this -> item && isset($this -> item -> id)){
    $shortcode[$model_ids -> tz_element]                = array();
    $shortcode[$model_ids -> tz_element]['id']          = $model_ids -> tz_element;
    $shortcode[$model_ids -> tz_element]['element_id']  = $this -> item -> id;
    $shortcode[$model_ids -> tz_element]['name']        = $item -> name;
    $shortcode[$model_ids -> tz_element]['parent_id']   = $model_ids -> tz_column;
    $shortcode[$model_ids -> tz_element]['params']      = '';

    $head['css']['shortcodes'][$this -> item -> name]    = $this -> item -> css_code;
    $head['js']['shortcodes'][$this -> item -> name]     = $this -> item -> js_code;

    if($fields = $this -> item -> fields){
        if(count($fields)){
            foreach($fields as $field){
                if(isset($field -> css_code) && !empty($field -> css_code)) {
                    $head['css']['fields'][$field->name] = $field->css_code;
                }
            }
        }
    }

    $ex_fields      = null;
    $_ex_fields     = null;
    $attributes     = null;
    $_attributes    = null;
    $_html          = $this -> item -> html;
    $_html_loop     = null;
    if(preg_match_all('/(.*?)(\[loop\].*?\[\/loop\])(.*?)/msi',$_html,$matches)){
        $_html_loop = $matches[2];
        $_html      = (isset($matches[1])?join("",$matches[1]):'').(isset($matches[3])?join('',$matches[3]):'');
        if(is_array($_html_loop)){
            $_html_loop   = join('',$_html_loop);
        }
    }

    if(preg_match_all('/(\{\w+\})/msi',$_html,$ex_matches)){
        $_ex_fields     = $ex_matches[count($ex_matches) - 1];
        $_ex_fields     = array_filter($_ex_fields);
        $_ex_fields     = array_unique($_ex_fields);
        $_ex_fields2    = array();
        foreach($_ex_fields as $key => $value){
            $_ex_fields2[str_replace(array('{','}'),'',$value)] = '';
            $attributes .= ' '.str_replace(array('{','}'),'',$value).'=""';
        }
        $_ex_fields = $_ex_fields2;
//        if(count($_ex_fields2)) {
//            $_ex_fields = json_encode($_ex_fields2);
//            $_ex_fields = str_replace('":"','"="',$_ex_fields);
//        }
    }
    $shortcode[$model_ids -> tz_element]['params']      = $_ex_fields;
    $shortcode[$model_ids -> tz_element]['shortcode']   = '['.$item -> name.$attributes.'][/'.$item -> name.']';


    if($_html_loop){
        if(preg_match_all('/(\{\w+\})/msi',$_html_loop,$ex_matches)){
            $ex_fields  = $ex_matches[count($ex_matches) - 1];
            $ex_fields  = array_filter($ex_fields);
            $ex_fields  = array_unique($ex_fields);
            $ex_fields2 = array();
            foreach($ex_fields as $key => $value){
                $ex_fields2[str_replace(array('{','}'),'',$value)] = '';
                $_attributes .= ' '.str_replace(array('{','}'),'',$value).'=""';
            }

            $ex_fields = $ex_fields2;
        }
        $shortcode[$model_ids -> tz_element_item]                   = array();
        $shortcode[$model_ids -> tz_element_item]['id']             = $model_ids -> tz_element_item;
        $shortcode[$model_ids -> tz_element_item]['name']           = 'tz_element';
        $shortcode[$model_ids -> tz_element_item]['element_id']     = $this -> item -> id;
        $shortcode[$model_ids -> tz_element_item]['parent_id']      = $model_ids -> tz_element;
        $shortcode[$model_ids -> tz_element_item]['params']         = $ex_fields;
        $shortcode[$model_ids -> tz_element_item]['shortcode']      = '{tz_element'.$_attributes.'}{/tz_element}';
    }
}

$parentid           = uniqid();
$htmlNewElement     = $this ->loadTemplate('script_html_element');
//if(preg_match_all('/(\[parentid[0-9+]?\].*?\[\/parentid[0-9+]?\])/ms',$htmlNewElement,$pmatches)){
//    $pmatches    = $pmatches[count($pmatches) - 1];
//}elseif(preg_match_all('/(\[\/parentid[0-9+]?\])/ms',$htmlNewElement,$pmatches)){
//    $pmatches    = $pmatches[count($pmatches) - 1];
//}else{
//    $pmatches   = array();
//}
//if($pmatches && count($pmatches)){
//    $pmatches    = array_filter($pmatches);
//    $pmatches    = array_unique($pmatches);
//
//    foreach($pmatches as $pmatch){
//        $htmlNewElement   = preg_replace('/('.str_replace(array('[',']','/'),array('\\[','\\]','\\/'),$pmatch).')/msi',uniqid(),$htmlNewElement);
//    }
//}

//if(preg_match_all('/(\[\/parentid\])|(\[parentid\]\[\/parentid\])/',$htmlNewElement,$pmatches)){
//    $htmlNewElement     = preg_replace('/(\[\/parentid\])|(\[parentid\]\[\/parentid\])/',$parentid,$htmlNewElement);
//}
$htmlNewElement     = JHtml::_('JVisualContent.jsaddslashes',$htmlNewElement);
$htmlNewElement2    = $this ->loadTemplate('script_html_element_original');
//if(preg_match_all('/(\[parentid[0-9+]?\].*?\[\/parentid[0-9+]?\])/',$htmlNewElement2,$pmatches2)){
//    $pmatches2    = $pmatches2[count($pmatches2) - 1];
//}elseif(preg_match_all('/(\[\/parentid[0-9+]?\])/',$htmlNewElement2,$pmatches2)){
//    $pmatches2    = $pmatches2[count($pmatches2) - 1];
//}else{
//    $pmatches2   = array();
//}
//if($pmatches2 && count($pmatches2)){
//    $pmatches2    = array_filter($pmatches2);
//    $pmatches2    = array_unique($pmatches2);
//
//    foreach($pmatches2 as $pmatch2){
//        $htmlNewElement2   = preg_replace('/('.str_replace(array('[',']','/'),array('\\[','\\]','\\/'),$pmatch2).')/msi',uniqid(),$htmlNewElement2);
//    }
//}

//if(preg_match_all('/(\[\/parentid\])|(\[parentid\]\[\/parentid\])/',$htmlNewElement2,$pmatch2)){
//    $htmlNewElement2    = preg_replace('/(\[\/parentid\])|(\[parentid\]\[\/parentid\])/',$parentid,$htmlNewElement2);
//}
$htmlNewElement2    = JHtml::_('JVisualContent.jsaddslashes',$htmlNewElement2);

$parentids  = array();
if(preg_match_all('/(\[parentid[0-9+]?\].*?\[\/parentid[0-9+]?\])/ms',$html,$pmatches3)){
    $pmatches3    = $pmatches3[count($pmatches3) - 1];
}elseif(preg_match_all('/(\[\/parentid[0-9+]?\])/ms',$html,$pmatches3)){
    $pmatches3    = $pmatches3[count($pmatches3) - 1];
}else{
    $pmatches3   = array();
}
if($pmatches3 && count($pmatches3)){
    $pmatches3    = array_filter($pmatches3);
    $pmatches3    = array_unique($pmatches3);

    foreach($pmatches3 as $pmatch3){
        $p_id                   = uniqid();
        $parentids[$pmatch3]    = $p_id;
        $html                   = preg_replace('/('.str_replace(array('[',']','/'),array('\\[','\\]','\\/'),$pmatch3).')/msi',
            $p_id,$html);
    }
}
if($this -> item && isset($this -> item -> id)){
    $shortcode[$model_ids -> tz_element]['parentids']   = $parentids;
}

if($shortcode && count($shortcode)){
    $shortcode  = json_encode($shortcode);
}

$app        = JFactory::getApplication();
$input      = $app -> input;
$post       = $input -> get('jform','','array');
$options    = '{}';
if(isset($post['options']) && $post['options']){
    $options    = $post['options'];
    $options    = json_encode($options);
}

//if(preg_match_all('/(\[\/parentid\])|(\[parentid\]\[\/parentid\])/',$html)){
//    $html   = preg_replace('/(\[\/parentid\])|(\[parentid\]\[\/parentid\])/',$parentid,$html);
//}
if($head && count($head)) {
    $head = json_encode($head);
}
?>
<script type="text/javascript">
    jQuery(document).ready(function(){
        window.parent.jQuery.addShortCodeElement('','<?php echo $html;?>',<?php echo $shortcode;?>,<?php echo $options;?>,<?php echo $head?$head:'""'?>);
        <?php if($htmlNewElement) { ?>
            window.parent.jQuery.addShortCodeElementTemp('<?php echo $htmlNewElement;?>');
        <?php } ?>
        <?php if($htmlNewElement2) { ?>
            window.parent.jQuery.addShortCodeElementTemp('<?php echo $htmlNewElement2;?>');
        <?php } ?>
        window.parent.jQuery.fancybox.close();
    });
</script>