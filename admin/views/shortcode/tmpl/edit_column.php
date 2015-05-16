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
<!---->
<!--<form action="index.php?option=com_jvisualcontent&view=type&task=type.listfields&tmpl=component" method="post">-->
<!--    <div class="tz_add-element-dialog-heading">-->
<!--        <h5 class="modal-title">--><?php //echo JText::_('COM_JVISUALCONTENT_HEADING_COLUMN_SETTING');?><!--</h5>-->
<!--    </div>-->
<!---->
<!--    <div class="jvc_container-fluid">-->
<!--        <div class="jscontent_edit_form_elements">-->
<!---->
<!--        <div class="jscontent_shortcode-toolbar form-group clearfix">-->
<!--            <div class="pull-right">-->
<!--                <button data-save="true" class="jvc_btn tz_panel-btn-save jvc_btn-danger">--><?php //echo JText::_('COM_JVISUALCONTENT_SAVE_CHANGE');?><!--</button>-->
<!--                <button data-dismiss="modal" class="jvc_btn tz_panel-btn-close jvc_btn-default" type="button">--><?php //echo JText::_('JTOOLBAR_CLOSE');?><!--</button>-->
<!--            </div>-->
<!--        </div>-->

        <div id="tz_edit-form-column-tabs">
            <!-- Nav tabs -->
            <ul class="jvc_nav jvc_nav-tabs tz_nav-tabs" role="tablist">
                <li class="jvc_active"><a href="#tz_edit-form-column-tab-0" role="tab" data-toggle="jvc_tab"><?php echo JText::_('COM_JVISUALCONTENT_GENERAL');?></a></li>
                <li><a href="#tz_edit-form-column-tab-1" role="tab" data-toggle="jvc_tab"><?php echo JText::_('COM_JVISUALCONTENT_DESIGN_OPTIONS');?></a></li>
                <li><a href="#tz_edit-form-column-tab-2" role="tab" data-toggle="jvc_tab"><?php echo JText::_('COM_JVISUALCONTENT_WIDTH_RESPONSIVE');?></a></li>
            </ul>

        <!-- Tab panes -->
        <div class="jvc_tab-content">
            <div class="jvc_row jvc_tab-pane jvc_active" id="tz_edit-form-column-tab-0">
                <div class="jvc_col-sm-6 column tz_sortable el_type_colorpicker tz_shortcode-param jvc_form-group">
                    <label class="element_label jvc_btn-block"><?php echo JText::_('COM_JVISUALCONTENT_FONT_COLOR');?></label>
                    <input type="text" class="rowbackgroundcolor" id="" data-extrafield-name="font_color"
                           data-extrafield-type="color">
                    <input type="hidden" class="rowbackgroundcolorinput"
                           data-name="font_color" name="jform[params][font_color]"
                           value="<?php echo isset($data['font_color'])?$data['font_color']:'';?>">
                    <small class="description text-muted clearfix"><em><?php echo JText::_('COM_JVISUALCONTENT_SELECT_FONT_COLOR');?></em></small>
                </div>
                <div class="jvc_col-sm-12 tz_column tz_sortable el_type_textfield tz_shortcode-param form-group">
                    <label class="element_label"><?php echo JText::_('COM_JVISUALCONTENT_EXTRA_CLASS_NAME');?></label>
                    <div class="edit_form_line">
                        <input type="text" class="tz_param_value el_class textinput jvc_form-control"
                               name="jform[params][el_class]"
                               data-name="el_class"
                               value="<?php echo isset($data['el_class'])?$data['el_class']:'';?>">
                        <small class="description jvc_text-muted jvc_clearfix">
                            <em><?php echo JText::_('COM_JVISUALCONTENT_EXTRA_CLASS_NAME_DESCRIPTION');?></em>
                        </small>
                    </div>
                </div>
            </div>

            <div class="jvc_row jvc_tab-pane" id="tz_edit-form-column-tab-1">
                <?php echo $this->loadTemplate('design_tab'); ?>
            </div>

            <!-- Tab width & responsiveness -->
            <div class="jvc_row jvc_tab-pane" id="tz_edit-form-column-tab-2">
            <!-- Width -->
            <div class="jvc_col-sm-12 tz_column tz_sortable el_type_dropdown tz_shortcode-param jvc_form-group">
                <label class="element_label"><?php echo JText::_('COM_JVISUALCONTENT_WIDTH');?></label>
                <div class="edit_form_line">
                    <select data-option="1/2" name="jform[params][width]"
                            class="tz_param_value input"
                            data-name="width">
                        <option value="1/12" class="1/12"<?php echo (isset($data['width']) && $data['width'] == '1/12')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMN_NUMBER',1,'1/12')?></option>
                        <option value="1/6" class="1/6"<?php echo (isset($data['width']) && $data['width'] == '1/6')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',2,'1/6')?></option>
                        <option value="1/4" class="1/4"<?php echo (isset($data['width']) && $data['width'] == '1/4')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',3,'1/4')?></option>
                        <option value="1/3" class="1/3"<?php echo (isset($data['width']) && $data['width'] == '1/3')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',4,'1/3')?></option>
                        <option value="5/12" class="5/12"<?php echo (isset($data['width']) && $data['width'] == '5/12')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',5,'5/12')?></option>
                        <option value="1/2" class="1/2"<?php echo (isset($data['width']) && $data['width'] == '1/2')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',6,'1/2')?></option>
                        <option value="7/12" class="7/12"<?php echo (isset($data['width']) && $data['width'] == '7/12')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',7,'7/12')?></option>
                        <option value="2/3" class="2/3"<?php echo (isset($data['width']) && $data['width'] == '2/3')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',8,'2/3')?></option>
                        <option value="3/4" class="3/4"<?php echo (isset($data['width']) && $data['width'] == '3/4')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',9,'3/4')?></option>
                        <option value="5/6" class="5/6"<?php echo (isset($data['width']) && $data['width'] == '5/6')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',10,'5/6')?></option>
                        <option value="11/12" class="11/12"<?php echo (isset($data['width']) && $data['width'] == '11/12')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',11,'11/12')?></option>
                        <option value="1/1" class="1/1"<?php echo (isset($data['width']) && $data['width'] == '1/1')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',12,'1/1')?></option>
                    </select>
                    <small class="description jvc_clearfix"><?php echo JText::_('COM_JVISUALCONTENT_SELECT_COLUMN_WIDTH');?></small>
                </div>
            </div>
            <!-- End width -->

            <!-- Responsiveness -->
            <div class="jvc_col-sm-12 tz_column tz_sortable el_type_column_offset tz_shortcode-param">
            <label class="element_label"><?php echo JText::_('COM_JVISUALCONTENT_RESPONSIVE');?></label>
            <div class="edit_form_line">
            <div data-column-offset="true" class="vc_column-offset">
            <input type="hidden" value=""
                   class="tz_param_value offset	column_offset '_field"
                   name="offset">
            <table class="table column-offset-table">
            <tbody>
            <tr>
                <th><?php echo JText::_('COM_JVISUALCONTENT_DEVICE');?></th>
                <th><?php echo JText::_('COM_JVISUALCONTENT_OFFSET');?></th>
                <th><?php echo JText::_('COM_JVISUALCONTENT_WIDTH');?></th>
                <th><?php echo JText::_('COM_JVISUALCONTENT_HIDE_ON_DEVICE');?></th>
            </tr>
            <tr class="tz_size-lg">
                <td class="tz_screen-size tz_screen-size-lg">
                    <span title="Large" data-toggle="jvc_tooltip" class="jvc_fa jvc_fa-desktop"></span>
                </td>
                <td>
                    <select data-type="offset-lg" class="tz_column_offset_field"
                            name="jform[params][col_lg_offset]"
                            data-name="lg_offset_size">
                        <option style="color: #ccc;" value=""><?php echo JText::_('COM_JVISUALCONTENT_INHERIT_SMALLER');?></option>
                        <option style="color: #ccc;" value="jvc_col-lg-offset-0"<?php echo (isset($data['col_lg_offset']) && $data['col_lg_offset'] == 'jvc_col-lg-offset-0')?' selected="true"':'';?>>No
                            offset
                        </option>
                        <option value="jvc_col-lg-offset-1"<?php echo (isset($data['col_lg_offset']) && $data['col_lg_offset'] == 'jvc_col-lg-offset-1')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMN_NUMBER',1,'1/12')?></option>
                        <option value="jvc_col-lg-offset-2"<?php echo (isset($data['col_lg_offset']) && $data['col_lg_offset'] == 'jvc_col-lg-offset-2')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',2,'1/6')?></option>
                        <option value="jvc_col-lg-offset-3"<?php echo (isset($data['col_lg_offset']) && $data['col_lg_offset'] == 'jvc_col-lg-offset-3')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',3,'1/4')?></option>
                        <option value="jvc_col-lg-offset-4"<?php echo (isset($data['col_lg_offset']) && $data['col_lg_offset'] == 'jvc_col-lg-offset-4')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',4,'1/3')?></option>
                        <option value="jvc_col-lg-offset-5"<?php echo (isset($data['col_lg_offset']) && $data['col_lg_offset'] == 'jvc_col-lg-offset-5')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',5,'5/12')?></option>
                        <option value="jvc_col-lg-offset-6"<?php echo (isset($data['col_lg_offset']) && $data['col_lg_offset'] == 'jvc_col-lg-offset-6')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',6,'1/2')?></option>
                        <option value="jvc_col-lg-offset-7"<?php echo (isset($data['col_lg_offset']) && $data['col_lg_offset'] == 'jvc_col-lg-offset-7')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',7,'7/12')?></option>
                        <option value="jvc_col-lg-offset-8"<?php echo (isset($data['col_lg_offset']) && $data['col_lg_offset'] == 'jvc_col-lg-offset-8')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',8,'2/3')?></option>
                        <option value="jvc_col-lg-offset-9"<?php echo (isset($data['col_lg_offset']) && $data['col_lg_offset'] == 'jvc_col-lg-offset-9')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',9,'3/4')?></option>
                        <option value="jvc_col-lg-offset-10"<?php echo (isset($data['col_lg_offset']) && $data['col_lg_offset'] == 'jvc_col-lg-offset-10')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',10,'5/6')?>
                        </option>
                        <option value="jvc_col-lg-offset-11"<?php echo (isset($data['col_lg_offset']) && $data['col_lg_offset'] == 'jvc_col-lg-offset-11')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',11,'11/12')?>
                        </option>
                        <option value="jvc_col-lg-offset-12"<?php echo (isset($data['col_lg_offset']) && $data['col_lg_offset'] == 'jvc_col-lg-offset-12')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',12,'1/1')?>
                        </option>
                    </select></td>
                <td>
                    <select data-type="size-lg" class="tz_column_offset_field"
                            name="jform[params][col_lg_size]"
                            data-name="tz_col_lg_size">
                        <option style="color: #ccc;" value=""><?php echo JText::_('COM_JVISUALCONTENT_INHERIT_SMALLER');?></option>
                        <option value="jvc_col-lg-1"<?php echo (isset($data['col_lg_size']) && $data['col_lg_size'] == 'jvc_col-lg-1')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMN_NUMBER',1,'1/12')?></option>
                        <option value="jvc_col-lg-2"<?php echo (isset($data['col_lg_size']) && $data['col_lg_size'] == 'jvc_col-lg-2')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',2,'1/6')?></option>
                        <option value="jvc_col-lg-3"<?php echo (isset($data['col_lg_size']) && $data['col_lg_size'] == 'jvc_col-lg-3')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',3,'1/4')?></option>
                        <option value="jvc_col-lg-4"<?php echo (isset($data['col_lg_size']) && $data['col_lg_size'] == 'jvc_col-lg-4')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',4,'1/3')?></option>
                        <option value="jvc_col-lg-5"<?php echo (isset($data['col_lg_size']) && $data['col_lg_size'] == 'jvc_col-lg-5')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',5,'5/12')?></option>
                        <option value="jvc_col-lg-6"<?php echo (isset($data['col_lg_size']) && $data['col_lg_size'] == 'jvc_col-lg-6')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',6,'1/2')?></option>
                        <option value="jvc_col-lg-7"<?php echo (isset($data['col_lg_size']) && $data['col_lg_size'] == 'jvc_col-lg-7')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',7,'7/12')?></option>
                        <option value="jvc_col-lg-8"<?php echo (isset($data['col_lg_size']) && $data['col_lg_size'] == 'jvc_col-lg-8')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',8,'2/3')?></option>
                        <option value="jvc_col-lg-9"<?php echo (isset($data['col_lg_size']) && $data['col_lg_size'] == 'jvc_col-lg-9')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',9,'3/4')?></option>
                        <option value="jvc_col-lg-10"<?php echo (isset($data['col_lg_size']) && $data['col_lg_size'] == 'jvc_col-lg-10')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',10,'5/6')?></option>
                        <option value="jvc_col-lg-11"<?php echo (isset($data['col_lg_size']) && $data['col_lg_size'] == 'jvc_col-lg-11')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',11,'11/12')?>
                        <option value="jvc_col-lg-12"<?php echo (isset($data['col_lg_size']) && $data['col_lg_size'] == 'jvc_col-lg-12')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',12,'1/1')?></option>
                    </select></td>
                <td>
                    <label>
                        <input type="checkbox" class="tz_column_offset_field"
                               name="jform[params][hidden_lg]"<?php echo (isset($data['hidden_lg']) && $data['hidden_lg'] == 'hidden-lg')?' checked="true"':'';?>
                               value="hidden-lg" data-name="tz_hidden_lg">
                        Hide </label>
                </td>
            </tr>
            <tr class="tz_size-md">
                <td class="tz_screen-size tz_screen-size-md">
                    <span title="Medium" data-toggle="jvc_tooltip" class="jvc_fa jvc_fa-laptop"></span>
                </td>
                <td>
                    <select data-type="offset-md" class="tz_column_offset_field"
                            name="jform[params][col_md_offset]"
                            data-name="tz_md_offset_size">
                        <option style="color: #ccc;" value=""><?php echo JText::_('COM_JVISUALCONTENT_INHERIT_SMALLER');?></option>
                        <option style="color: #ccc;" value="jvc_col-md-offset-0"<?php echo (isset($data['col_md_offset']) && $data['col_md_offset'] == 'jvc_col-md-offset-0')?' selected="true"':'';?>><?php echo JText::_('COM_JVISUALCONTENT_NO_OFFSET');?>
                        </option>
                        <option value="jvc_col-md-offset-1"<?php echo (isset($data['col_md_offset']) && $data['col_md_offset'] == 'jvc_col-md-offset-1')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMN_NUMBER',1,'1/12')?></option>
                        <option value="jvc_col-md-offset-2"<?php echo (isset($data['col_md_offset']) && $data['col_md_offset'] == 'jvc_col-md-offset-2')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',2,'1/6')?></option>
                        <option value="jvc_col-md-offset-3"<?php echo (isset($data['col_md_offset']) && $data['col_md_offset'] == 'jvc_col-md-offset-3')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',3,'1/4')?></option>
                        <option value="jvc_col-md-offset-4"<?php echo (isset($data['col_md_offset']) && $data['col_md_offset'] == 'jvc_col-md-offset-4')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',4,'1/3')?></option>
                        <option value="jvc_col-md-offset-5"<?php echo (isset($data['col_md_offset']) && $data['col_md_offset'] == 'jvc_col-md-offset-5')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',5,'5/12')?></option>
                        <option value="jvc_col-md-offset-6"<?php echo (isset($data['col_md_offset']) && $data['col_md_offset'] == 'jvc_col-md-offset-6')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',6,'1/2')?></option>
                        <option value="jvc_col-md-offset-7"<?php echo (isset($data['col_md_offset']) && $data['col_md_offset'] == 'jvc_col-md-offset-7')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',7,'7/12')?></option>
                        <option value="jvc_col-md-offset-8"<?php echo (isset($data['col_md_offset']) && $data['col_md_offset'] == 'jvc_col-md-offset-8')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',8,'2/3')?></option>
                        <option value="jvc_col-md-offset-9"<?php echo (isset($data['col_md_offset']) && $data['col_md_offset'] == 'jvc_col-md-offset-9')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',9,'3/4')?></option>
                        <option value="jvc_col-md-offset-10"<?php echo (isset($data['col_md_offset']) && $data['col_md_offset'] == 'jvc_col-md-offset-10')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',10,'5/6')?>
                        </option>
                        <option value="jvc_col-md-offset-11"<?php echo (isset($data['col_md_offset']) && $data['col_md_offset'] == 'jvc_col-md-offset-11')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',11,'11/12')?>
                        </option>
                        <option value="jvc_col-md-offset-12"<?php echo (isset($data['col_md_offset']) && $data['col_md_offset'] == 'jvc_col-md-offset-12')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',12,'1/1')?>
                        </option>
                    </select></td>
                <td>
                    <select data-type="size-md" class="tz_column_offset_field"
                            name="jform[params][col_md_size]"
                            data-name="tz_col_md_size">
                        <option style="color: #ccc;" value=""><?php echo JText::_('COM_JVISUALCONTENT_INHERIT_SMALLER');?></option>
                        <option value="jvc_col-md-1"<?php echo (isset($data['col_md_size']) && $data['col_md_size'] == 'jvc_col-md-1')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMN_NUMBER',1,'1/12')?></option>
                        <option value="jvc_col-md-2"<?php echo (isset($data['col_md_size']) && $data['col_md_size'] == 'jvc_col-md-2')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',2,'1/6')?></option>
                        <option value="jvc_col-md-3"<?php echo (isset($data['col_md_size']) && $data['col_md_size'] == 'jvc_col-md-3')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',3,'1/4')?></option>
                        <option value="jvc_col-md-4"<?php echo (isset($data['col_md_size']) && $data['col_md_size'] == 'jvc_col-md-4')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',4,'1/3')?></option>
                        <option value="jvc_col-md-5"<?php echo (isset($data['col_md_size']) && $data['col_md_size'] == 'jvc_col-md-5')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',5,'5/12')?></option>
                        <option value="jvc_col-md-6"<?php echo (isset($data['col_md_size']) && $data['col_md_size'] == 'jvc_col-md-6')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',6,'1/2')?></option>
                        <option value="jvc_col-md-7"<?php echo (isset($data['col_md_size']) && $data['col_md_size'] == 'jvc_col-md-7')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',7,'7/12')?></option>
                        <option value="jvc_col-md-8"<?php echo (isset($data['col_md_size']) && $data['col_md_size'] == 'jvc_col-md-8')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',8,'2/3')?></option>
                        <option value="jvc_col-md-9"<?php echo (isset($data['col_md_size']) && $data['col_md_size'] == 'jvc_col-md-9')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',9,'3/4')?></option>
                        <option value="jvc_col-md-10"<?php echo (isset($data['col_md_size']) && $data['col_md_size'] == 'jvc_col-md-10')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',10,'5/6')?></option>
                        <option value="jvc_col-md-11"<?php echo (isset($data['col_md_size']) && $data['col_md_size'] == 'jvc_col-md-11')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',11,'11/12')?></option>
                        <option value="jvc_col-md-12"<?php echo (isset($data['col_md_size']) && $data['col_md_size'] == 'jvc_col-md-12')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',12,'1/1')?></option>
                    </select></td>
                <td>
                    <label>
                        <input type="checkbox" class="tz_column_offset_field"
                               name="jform[params][hidden_md]"<?php echo (isset($data['hidden_md']) && $data['hidden_md'] == 'hidden-md')?' checked="true"':'';?>
                               value="hidden-md" data-name="tz_hidden_md">
                        <?php echo JText::_('JHIDE');?> </label>
                </td>
            </tr>
            <tr class="tz_size-sm">
                <td class="tz_screen-size tz_screen-size-sm">
                    <span title="Small" data-toggle="jvc_tooltip" class="jvc_fa jvc_fa-tablet"></span>
                </td>
                <td>
                    <select data-type="offset-sm" class="tz_column_offset_field"
                            name="jform[params][col_sm_offset]"
                            data-name="tz_sm_offset_size">
                        <option style="color: #ccc;" value=""><?php echo JText::_('COM_JVISUALCONTENT_INHERIT_SMALLER');?></option>
                        <option style="color: #ccc;" value="jvc_col-sm-offset-0"<?php echo (isset($data['col_sm_offset']) && $data['col_sm_offset'] == 'jvc_col-sm-offset-0')?' selected="true"':'';?>><?php echo JText::_('COM_JVISUALCONTENT_NO_OFFSET');?>
                        </option>
                        <option value="jvc_col-sm-offset-1"<?php echo (isset($data['col_sm_offset']) && $data['col_sm_offset'] == 'jvc_col-sm-offset-1')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMN_NUMBER',1,'1/12')?></option>
                        <option value="jvc_col-sm-offset-2"<?php echo (isset($data['col_sm_offset']) && $data['col_sm_offset'] == 'jvc_col-sm-offset-2')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',2,'1/6')?></option>
                        <option value="jvc_col-sm-offset-3"<?php echo (isset($data['col_sm_offset']) && $data['col_sm_offset'] == 'jvc_col-sm-offset-3')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',3,'1/4')?></option>
                        <option value="jvc_col-sm-offset-4"<?php echo (isset($data['col_sm_offset']) && $data['col_sm_offset'] == 'jvc_col-sm-offset-4')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',4,'1/3')?></option>
                        <option value="jvc_col-sm-offset-5"<?php echo (isset($data['col_sm_offset']) && $data['col_sm_offset'] == 'jvc_col-sm-offset-5')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',5,'5/12')?></option>
                        <option value="jvc_col-sm-offset-6"<?php echo (isset($data['col_sm_offset']) && $data['col_sm_offset'] == 'jvc_col-sm-offset-6')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',6,'1/2')?></option>
                        <option value="jvc_col-sm-offset-7"<?php echo (isset($data['col_sm_offset']) && $data['col_sm_offset'] == 'jvc_col-sm-offset-7')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',7,'7/12')?></option>
                        <option value="jvc_col-sm-offset-8"<?php echo (isset($data['col_sm_offset']) && $data['col_sm_offset'] == 'jvc_col-sm-offset-8')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',8,'2/3')?></option>
                        <option value="jvc_col-sm-offset-9"<?php echo (isset($data['col_sm_offset']) && $data['col_sm_offset'] == 'jvc_col-sm-offset-9')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',9,'3/4')?></option>
                        <option value="jvc_col-sm-offset-10"<?php echo (isset($data['col_sm_offset']) && $data['col_sm_offset'] == 'jvc_col-sm-offset-10')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',10,'5/6')?>
                        </option>
                        <option value="jvc_col-sm-offset-11"<?php echo (isset($data['col_sm_offset']) && $data['col_sm_offset'] == 'jvc_col-sm-offset-11')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',11,'11/12')?>
                        </option>
                        <option value="jvc_col-sm-offset-12"<?php echo (isset($data['col_sm_offset']) && $data['col_sm_offset'] == 'jvc_col-sm-offset-12')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',12,'1/1')?>
                        </option>
                    </select></td>
                <td>
                    <small class="description"><em><?php echo JText::_('COM_JVISUALCONTENT_OFFSET_DESCRIPTION');?></em></small>
                </td>
                <td>
                    <label>
                        <input type="checkbox" class="tz_column_offset_field"
                               name="jform[params][hidden_sm]"<?php echo (isset($data['hidden_sm']) && $data['hidden_sm'] == 'hidden-sm')?' checked="true"':'';?>
                               value="hidden-sm" data-name="tz_hidden_sm">
                        Hide </label>
                </td>
            </tr>
            <tr class="tz_size-xs">
                <td class="tz_screen-size tz_screen-size-xs">
                    <span title="Extra small" data-toggle="jvc_tooltip" class="jvc_fa jvc_fa-mobile"></span>
                </td>
                <td>
                    <select data-type="offset-xs"
                            name="jform[params][col_xs_offset]"
                            class="tz_column_offset_field"
                            data-name="tz_xs_offset_size">
                        <option style="color: #ccc;" value=""><?php echo JText::_('COM_JVISUALCONTENT_NO_OFFSET');?></option>
                        <option value="jvc_col-xs-offset-1"<?php echo (isset($data['col_xs_offset']) && $data['col_xs_offset'] == 'jvc_col-xs-offset-1')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMN_NUMBER',1,'1/12')?></option>
                        <option value="jvc_col-xs-offset-2"<?php echo (isset($data['col_xs_offset']) && $data['col_xs_offset'] == 'jvc_col-xs-offset-2')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',2,'1/6')?></option>
                        <option value="jvc_col-xs-offset-3"<?php echo (isset($data['col_xs_offset']) && $data['col_xs_offset'] == 'jvc_col-xs-offset-3')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',3,'1/4')?></option>
                        <option value="jvc_col-xs-offset-4"<?php echo (isset($data['col_xs_offset']) && $data['col_xs_offset'] == 'jvc_col-xs-offset-4')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',4,'1/3')?></option>
                        <option value="jvc_col-xs-offset-5"<?php echo (isset($data['col_xs_offset']) && $data['col_xs_offset'] == 'jvc_col-xs-offset-5')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',5,'5/12')?></option>
                        <option value="jvc_col-xs-offset-6"<?php echo (isset($data['col_xs_offset']) && $data['col_xs_offset'] == 'jvc_col-xs-offset-6')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',6,'1/2')?></option>
                        <option value="jvc_col-xs-offset-7"<?php echo (isset($data['col_xs_offset']) && $data['col_xs_offset'] == 'jvc_col-xs-offset-7')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',7,'7/12')?></option>
                        <option value="jvc_col-xs-offset-8"<?php echo (isset($data['col_xs_offset']) && $data['col_xs_offset'] == 'jvc_col-xs-offset-8')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',8,'2/3')?></option>
                        <option value="jvc_col-xs-offset-9"<?php echo (isset($data['col_xs_offset']) && $data['col_xs_offset'] == 'jvc_col-xs-offset-9')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',9,'3/4')?></option>
                        <option value="jvc_col-xs-offset-10"<?php echo (isset($data['col_xs_offset']) && $data['col_xs_offset'] == 'jvc_col-xs-offset-10')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',10,'5/6')?>
                        </option>
                        <option value="jvc_col-xs-offset-11"<?php echo (isset($data['col_xs_offset']) && $data['col_xs_offset'] == 'jvc_col-xs-offset-11')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',11,'11/12')?>
                        </option>
                        <option value="jvc_col-xs-offset-12"<?php echo (isset($data['col_xs_offset']) && $data['col_xs_offset'] == 'jvc_col-xs-offset-12')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',12,'1/1')?>
                        </option>
                    </select></td>
                <td>
                    <select data-type="size-xs" class="tz_column_offset_field"
                            name="jform[params][col_xs_size]"
                            data-name="tz_col_xs_size">
                        <option style="color: #ccc;" value=""></option>
                        <option value="jvc_col-xs-1"<?php echo (isset($data['col_xs_size']) && $data['col_xs_size'] == 'jvc_col-xs-1')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMN_NUMBER',1,'1/12')?></option>
                        <option value="jvc_col-xs-2"<?php echo (isset($data['col_xs_size']) && $data['col_xs_size'] == 'jvc_col-xs-2')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',2,'1/6')?></option>
                        <option value="jvc_col-xs-3"<?php echo (isset($data['col_xs_size']) && $data['col_xs_size'] == 'jvc_col-xs-3')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',3,'1/4')?></option>
                        <option value="jvc_col-xs-4"<?php echo (isset($data['col_xs_size']) && $data['col_xs_size'] == 'jvc_col-xs-4')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',4,'1/3')?></option>
                        <option value="jvc_col-xs-5"<?php echo (isset($data['col_xs_size']) && $data['col_xs_size'] == 'jvc_col-xs-5')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',5,'5/12')?></option>
                        <option value="jvc_col-xs-6"<?php echo (isset($data['col_xs_size']) && $data['col_xs_size'] == 'jvc_col-xs-6')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',6,'1/2')?></option>
                        <option value="jvc_col-xs-7"<?php echo (isset($data['col_xs_size']) && $data['col_xs_size'] == 'jvc_col-xs-7')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',7,'7/12')?></option>
                        <option value="jvc_col-xs-8"<?php echo (isset($data['col_xs_size']) && $data['col_xs_size'] == 'jvc_col-xs-8')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',8,'2/3')?></option>
                        <option value="jvc_col-xs-9"<?php echo (isset($data['col_xs_size']) && $data['col_xs_size'] == 'jvc_col-xs-9')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',9,'3/4')?></option>
                        <option value="jvc_col-xs-10"<?php echo (isset($data['col_xs_size']) && $data['col_xs_size'] == 'jvc_col-xs-10')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',10,'5/6')?></option>
                        <option value="jvc_col-xs-11"<?php echo (isset($data['col_xs_size']) && $data['col_xs_size'] == 'jvc_col-xs-11')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',11,'11/12')?></option>
                        <option value="jvc_col-xs-12"<?php echo (isset($data['col_xs_size']) && $data['col_xs_size'] == 'jvc_col-xs-12')?' selected="true"':'';?>><?php echo JText::sprintf('COM_JVISUALCONTENT_COLUMNS_NUMBER',12,'1/1')?></option>
                    </select></td>
                <td>
                    <label>
                        <input type="checkbox" class="tz_column_offset_field"
                               name="jform[params][hidden_xs]"<?php echo (isset($data['hidden_xs']) && $data['hidden_xs'] == 'hidden-xs')?' checked="true"':'';?>
                               value="hidden-xs" data-name="tz_hidden_xs">
                        Hide </label>
                </td>
            </tr>
            </tbody>
            </table>
            </div>
            <small class="description clearfix"><em><?php echo JText::_('COM_JVISUALCONTENT_RESPONSIVE_DESCRIPTION');?></em></small>
            </div>
            </div>
            <!-- End responsiveness -->

            </div>
            <!-- End tab width & responsiveness -->
        </div>
        </div>
<!--        </div>-->
<!--    </div>-->
<!--</form>-->