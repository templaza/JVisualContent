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

$state  = $this -> state;
$data   = $state -> get('shortcode.params');

$doc    = JFactory::getDocument();
$script = '';
if(isset($data['font_color']) && $data['font_color']){
    $script .= 'jQuery(".jscontent_edit_form_elements input[data-extrafield-type=color][data-extrafield-name=font_color]").spectrum("set","'.$data['font_color'].'");';
}
if(isset($data['background_color']) && $data['background_color']){
    $script .= 'jQuery(".jscontent_edit_form_elements input[data-extrafield-type=color][data-extrafield-name=background_color]").spectrum("set","'.$data['background_color'].'");';
}
if(isset($data['border_color']) && $data['border_color']){
    $script .= "\n".'jQuery(".jscontent_edit_form_elements input[data-extrafield-type=color][data-extrafield-name=border_color]").spectrum("set","'.$data['border_color'].'");';
}
if($script){
    $doc -> addCustomTag('<script type="text/javascript">
    jQuery(document).ready(function(){'.$script.'});</script>');
}
?>
    <div class="jvc_col-sm-12">
        <label class="element_label">Css</label>
        <div class="edit_form_line">

            <div class="tz_css-editor tz_row">
                <!-- Left css -->
                <div class="tz_layout-onion jvc_col-xs-6">
                    <div class="tz_margin">
                        <label>margin</label>
                        <input type="text" data-attribute="margin"
                               value="<?php echo isset($data['margin_top'])?$data['margin_top']:'';?>"
                               placeholder="-" class="tz_top"
                               data-name="margin-top" name="jform[params][margin_top]">
                        <input type="text" data-attribute="margin"
                               value="<?php echo isset($data['margin_right'])?$data['margin_right']:'';?>"
                               placeholder="-" class="tz_right"
                               data-name="margin-right" name="jform[params][margin_right]">
                        <input type="text" data-attribute="margin"
                               value="<?php echo isset($data['margin_bottom'])?$data['margin_bottom']:'';?>"
                               placeholder="-" class="tz_bottom"
                               data-name="margin-bottom" name="jform[params][margin_bottom]">
                        <input type="text" data-attribute="margin"
                               value="<?php echo isset($data['margin_left'])?$data['margin_left']:'';?>"
                               placeholder="-" class="tz_left"
                               data-name="margin-left" name="jform[params][margin_left]">

                        <div class="tz_border">
                            <label>border</label>
                            <input type="text" data-attribute="border"
                                   value="<?php echo isset($data['border_top_width'])?$data['border_top_width']:'';?>"
                                   placeholder="-" class="tz_top"
                                   data-name="border-top-width"
                                   name="jform[params][border_top_width]">
                            <input type="text" data-attribute="border"
                                   value="<?php echo isset($data['border_right_width'])?$data['border_right_width']:'';?>"
                                   placeholder="-" class="tz_right"
                                   data-name="border-right-width"
                                   name="jform[params][border_right_width]">
                            <input type="text" data-attribute="border"
                                   value="<?php echo isset($data['border_bottom_width'])?$data['border_bottom_width']:'';?>"
                                   placeholder="-" class="tz_bottom"
                                   data-name="border-bottom-width"
                                   name="jform[params][border_bottom_width]">
                            <input type="text" data-attribute="border"
                                   value="<?php echo isset($data['border_left_width'])?$data['border_left_width']:'';?>"
                                   placeholder="-" class="tz_left"
                                   data-name="border-left-width"
                                   name="jform[params][border_left_width]">

                            <div class="tz_padding">
                                <label>padding</label>
                                <input type="text" data-attribute="padding"
                                       value="<?php echo isset($data['padding_top'])?$data['padding_top']:'';?>"
                                       placeholder="-" class="tz_top"
                                       data-name="padding-top" name="jform[params][padding_top]">
                                <input type="text" data-attribute="padding"
                                       value="<?php echo isset($data['padding_right'])?$data['padding_right']:'';?>"
                                       placeholder="-" class="tz_right"
                                       data-name="padding-right" name="jform[params][padding_right]">
                                <input type="text" data-attribute="padding"
                                       value="<?php echo isset($data['padding_bottom'])?$data['padding_bottom']:'';?>"
                                       placeholder="-" class="tz_bottom"
                                       data-name="padding-bottom" name="jform[params][padding_bottom]">
                                <input type="text" data-attribute="padding"
                                       value="<?php echo isset($data['padding_left'])?$data['padding_left']:'';?>"
                                       placeholder="-" class="tz_left"
                                       data-name="padding-left" name="jform[params][padding_left]">

                                <div class="tz_content"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End left css -->
                <!-- Right css -->
                <div class="jvc_col-xs-6 tz_settings">
                    <label>Border</label>

                    <div class="color-group">
                        <input type="text" class="rowbackgroundcolor" data-extrafield-name="border_color"
                               name="jform[params][border_color]" data-extrafield-type="color">
                        <input type="hidden" class="rowbackgroundcolorinput" data-name="border_color"
                               name="jform[params][border_color]"
                               value="<?php echo isset($data['border_color'])?$data['border_color']:'';?>">
                    </div>
                    <div class="tz_border-style">
                        <select class="tz_border-style" name="jform[params][border_style]"
                                data-name="border_style">
                            <option value=""><?php echo JText::_('COM_JVISUALCONTENT_THEME_DEFAULT');?></option>
                            <option value="solid"<?php echo (isset($data['border_style']) && $data['border_style'] == 'solid')?' selected="true"':'';?>><?php echo JText::_('COM_JVISUALCONTENT_SOLID');?></option>
                            <option value="dotted"<?php echo (isset($data['border_style']) && $data['border_style'] == 'dotted')?' selected="true"':'';?>><?php echo JText::_('COM_JVISUALCONTENT_DOTTED');?></option>
                            <option value="dashed"<?php echo (isset($data['border_style']) && $data['border_style'] == 'dashed')?' selected="true"':'';?>><?php echo JText::_('COM_JVISUALCONTENT_DASHED');?></option>
                            <option value="none"<?php echo (isset($data['border_style']) && $data['border_style'] == 'none')?' selected="true"':'';?>><?php echo JText::_('JNONE');?></option>
                            <option value="hidden"<?php echo (isset($data['border_style']) && $data['border_style'] == 'hidden')?' selected="true"':'';?>><?php echo JText::_('COM_JVISUALCONTENT_HIDDEN');?></option>
                            <option value="double"<?php echo (isset($data['border_style']) && $data['border_style'] == 'double')?' selected="true"':'';?>><?php echo JText::_('COM_JVISUALCONTENT_DOUBLE');?></option>
                            <option value="groove"<?php echo (isset($data['border_style']) && $data['border_style'] == 'groove')?' selected="true"':'';?>><?php echo JText::_('COM_JVISUALCONTENT_GROOVE');?></option>
                            <option value="ridge"<?php echo (isset($data['border_style']) && $data['border_style'] == 'ridge')?' selected="true"':'';?>><?php echo JText::_('COM_JVISUALCONTENT_RIDGE');?></option>
                            <option value="inset"<?php echo (isset($data['border_style']) && $data['border_style'] == 'inset')?' selected="true"':'';?>><?php echo JText::_('COM_JVISUALCONTENT_INSET');?></option>
                            <option value="outset"<?php echo (isset($data['border_style']) && $data['border_style'] == 'outset')?' selected="true"':'';?>><?php echo JText::_('COM_JVISUALCONTENT_OUTSET');?></option>
                            <option value="initial"<?php echo (isset($data['border_style']) && $data['border_style'] == 'initial')?' selected="true"':'';?>><?php echo JText::_('COM_JVISUALCONTENT_INITIAL');?></option>
                            <option value="inherit"<?php echo (isset($data['border_style']) && $data['border_style'] == 'inherit')?' selected="true"':'';?>><?php echo JText::_('JGLOBAL_INHERIT');?></option>
                        </select>
                    </div>
                    <label>Background</label>

                    <div class="color-group">
                        <input type="text" class="rowbackgroundcolor" data-extrafield-type="color"
                               data-extrafield-name="background_color">
                        <input type="hidden" class="rowbackgroundcolorinput" data-name="background_color"
                               name="jform[params][background_color]" value="<?php echo isset($data['background_color'])?$data['background_color']:'';?>">
                    </div>
                    <div class="tz_background-image">
                        <?php
                        $fieldSets = $this->form->getFieldsets('params');
                        if($fieldSet = $this->form->getFieldset('shortcode_details')):
                            foreach($fieldSet as $field):
                                echo $field -> input;
                            endforeach;
                        endif;
                        ?>
                        <div class="clearfix"></div>
                    </div>
                    <div class="tz_background-style">
                        <select class="tz_background-style" name="jform[params][background_style]">
                            <option value=""><?php echo JText::_('COM_JVISUALCONTENT_THEME_DEFAULT');?></option>
                            <option value="cover"<?php echo (isset($data['background_style']) && $data['background_style'] == 'cover')?' selected="true"':'';?>><?php echo JText::_('COM_JVISUALCONTENT_COVER');?></option>
                            <option value="contain"<?php echo (isset($data['background_style']) && $data['background_style'] == 'contain')?' selected="true"':'';?>><?php echo JText::_('COM_JVISUALCONTENT_CONTAIN');?></option>
                            <option value="no-repeat"<?php echo (isset($data['background_style']) && $data['background_style'] == 'no-repeat')?' selected="true"':'';?>><?php echo JText::_('COM_JVISUALCONTENT_NO_REPEAT');?></option>
                            <option value="repeat"<?php echo (isset($data['background_style']) && $data['background_style'] == 'repeat')?' selected="true"':'';?>><?php echo JText::_('COM_JVISUALCONTENT_REPEAT');?></option>
                        </select>
                    </div>
                    <label><?php echo JText::_('COM_JVISUALCONTENT_BOX_CONTROLS');?></label>
                    <label>
                        <input type="checkbox" value="" class="tz_simplify" name="simply"> <?php echo JText::_('COM_JVISUALCONTENT_SIMPLIFY_CONTROLS');?></label>

                </div>
                <!-- End right css -->

            </div>

        </div>
    </div>