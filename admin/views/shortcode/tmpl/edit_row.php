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
?>
<!--<form action="index.php?option=com_jvisualcontent&view=type&task=type.listfields&tmpl=component" method="post">-->
<!--    <div class="tz_add-element-dialog-heading">-->
<!--        <h5 class="modal-title">--><?php //echo JText::_('COM_JVISUALCONTENT_HEADING_ROW_SETTING');?><!--</h5>-->
<!--    </div>-->
<!---->
<!--    <div class="jvc_container-fluid">-->
<!--        <div class="jscontent_edit_form_elements">-->
<!--            <div class="jscontent_shortcode-toolbar jvc_form-group jvc_clearfix">-->
<!--                <div class="jvc_pull-right">-->
<!--                    <button data-save="true" class="jvc_btn tz_panel-btn-save jvc_btn-danger">--><?php //echo JText::_('COM_JVISUALCONTENT_SAVE_CHANGE');?><!--</button>-->
<!--                    <button data-dismiss="jvc_modal" class="jvc_btn tz_panel-btn-close jvc_btn-default" type="button">--><?php //echo JText::_('JTOOLBAR_CLOSE');?><!--</button>-->
<!--                </div>-->
<!--            </div>-->

            <div id="tz_edit-form-row-tabs">
                <!-- Nav tabs -->
                <ul class="jvc_nav jvc_nav-tabs tz_nav-tabs" role="tablist">
                    <li class="jvc_active"><a href="#tz_edit-form-row-tab-0" role="tab" data-toggle="jvc_tab"><?php echo JText::_('COM_JVISUALCONTENT_GENERAL');?></a></li>
                    <li><a href="#tz_edit-form-row-tab-1" role="tab" data-toggle="jvc_tab"><?php echo JText::_('COM_JVISUALCONTENT_DESIGN_OPTIONS');?></a></li>
                </ul>

                <!-- Tab panes -->
                <div class="jvc_tab-content">
                    <div class="jvc_row jvc_tab-pane jvc_active jvc_form-horizontal" id="tz_edit-form-row-tab-0">
                        <div class="jvc_col-sm-12 tz_column tz_sortable el_type_colorpicker tz_shortcode-param">
                            <div class="jvc_form-group">
                                <label class="jvc_col-sm-3 element_label"><?php echo JText::_('COM_JVISUALCONTENT_ROW_WIDTH');?></label>
                                <div class="jvc_col-sm-9">
                                    <select data-name="width" name="jform[params][width]">
                                        <option value=""><?php echo JText::_('JNONE');?></option>
                                        <option value="jvc_container"<?php echo (isset($data['width']) && $data['width'] == 'container')?' selected="true"':'';?>><?php echo JText::_('COM_JVISUALCONTENT_FIXED_WIDTH');?></option>
                                        <option value="jvc_container-fluid"<?php echo (isset($data['width']) && $data['width'] == 'container-fluid')?' selected="true"':'';?>><?php echo JText::_('COM_JVISUALCONTENT_FULL_WIDTH');?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="jvc_form-group">
                                <label class="jvc_col-sm-3 element_label"><?php echo JText::_('COM_JVISUALCONTENT_TEXT_ALIGN');?></label>
                                <div class="jvc_col-sm-9">
                                    <select data-name="text_align" name="jform[params][text_align]">
                                        <option value="left"><?php echo JText::_('JGLOBAL_LEFT');?></option>
                                        <option value="center"<?php echo (isset($data['text_align']) && $data['text_align'] == 'center')?' selected="true"':'';?>><?php echo JText::_('JGLOBAL_CENTER');?></option>
                                        <option value="right"<?php echo (isset($data['text_align']) && $data['text_align'] == 'right')?' selected="true"':'';?>><?php echo JText::_('JGLOBAL_RIGHT');?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="jvc_col-sm-12 tz_column tz_sortable el_type_textfield tz_shortcode-param">
                            <label class="element_label"><?php echo JText::_('COM_JVISUALCONTENT_EXTRA_CLASS_NAME');?></label>
                            <div class=" edit_form_line">
                                <input type="text" value="<?php echo isset($data['el_class'])?$data['el_class']:'';?>"
                                       name="jform[params][el_class]"
                                       class="tz_param_value el_class textinput form-control" data-name="el_class">
                                <small class="description jvc_text-muted jvc_clearfix">
                                    <em><?php echo JText::_('COM_JVISUALCONTENT_EXTRA_CLASS_NAME_DESCRIPTION');?></em>
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="jvc_row jvc_tab-pane" id="tz_edit-form-row-tab-1">
                        <?php echo $this -> loadTemplate('design_tab');?>
                    </div>

                </div>
            </div>
<!--        </div>-->
<!--    </div>-->
<!--</form>-->