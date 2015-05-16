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

// Call chosen js function
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.formvalidation');

$doc    = JFactory::getDocument();
$doc -> addCustomTag('<script type="text/javascript">

    (function($){
        $(document).ready(function(){
            $(\'#tz-extra-field .tz-relative\').bind(\'click\',function(e){
                var $html   = $(\'#jform_html\');
                if($html.length) {
                    var $html_value = $html.val(),
                            $extra_field = \'\';
                    if($(this).find(\'.extrafield\').length) {
                        $extra_field    = $(this).find(\'.extrafield\').text().trim();
                    }else{
                        if($(this).attr(\'data-extrafield\').trim().length){
                            $extra_field    = $(this).attr(\'data-extrafield\').trim();
                        }
                    }
                    if($html_value.length){
                        var $cursor_start_pos = $html[0].selectionStart,
                            $cursor_end_pos = $html[0].selectionEnd,
                            $first_value,
                            $last_value,
                            $selected_value;
                        if($cursor_start_pos >= 0 ){
                            $first_value    = $html_value.substr(0,$cursor_start_pos);
                            $last_value    = $html_value.substr($cursor_end_pos,$html_value.length);
                                $selected_value = $html_value.substr($cursor_start_pos,$cursor_end_pos - $cursor_start_pos);
                        }
                        if($extra_field.length){
                            if($(this).attr("data-type") && jQuery(this).attr("data-type") == "loop"){
                                if($cursor_start_pos == $cursor_end_pos){
                                    alert("Please select some html");
                                }else{
                                    var $loop   = $extra_field.split(/\]\[/);
                                    var $_extra_field   = $loop[0]+ "]" + $selected_value + "[" + $loop[1];
                                    $html.focus();
                                    $html.val($first_value + $_extra_field + $last_value);
                                    if($cursor_start_pos != $cursor_end_pos){
                                        $html[0].setSelectionRange($cursor_start_pos,$cursor_start_pos + $_extra_field.length);
                                    }
                                }

                            }else{
                                if($(this).attr("data-type") && $(this).attr("data-type") == "autoid"){
                                    if(!$(this).find("input").is(":focus")){
                                        $html.focus();
                                        var $_extra_field   = $extra_field.replace(/\]$/,$(this).find("input").val()+"]");
                                        $html.val($first_value + $_extra_field + $last_value);
                                        if($cursor_start_pos != $cursor_end_pos){
                                            $html[0].setSelectionRange($cursor_start_pos,$cursor_start_pos + $_extra_field.length);
                                        }else {
                                            $html[0].setSelectionRange($cursor_start_pos + $_extra_field.length,
                                                $cursor_end_pos + $extra_field.length);
                                        }
                                    }
                                }else{
                                    $html.focus();
                                    $html.val($first_value + $extra_field + $last_value);
                                    if($cursor_start_pos != $cursor_end_pos){
                                        $html[0].setSelectionRange($cursor_start_pos,$cursor_start_pos + $extra_field.length);
                                    }else {
                                        $html[0].setSelectionRange($cursor_start_pos + $extra_field.length,
                                            $cursor_end_pos + $extra_field.length);
                                    }
                                }
                            }
                        }
                    }else{
                        $html.val($extra_field);
                    }
                }
            });
            $("#tz-extra-field .extrafield input").bind("change",function(){
                $(this).focus();
            });
        });
    })(jQuery);
</script>');
?>
<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'element.cancel' || document.formvalidator.isValid(document.id('type-form')))
        {
            Joomla.submitform(task, document.getElementById('type-form'));
        }
        else{
            if($('jform_extrafields').hasClass('invalid')){
                $$('#tz-shortcode-type, #tz-extra-field').addClass('tz-invalid');
            }
            else{
                $$('#tz-shortcode-type, #tz-extra-field').removeClass('tz-invalid');
            }
        }
    }
</script>
<form name="adminForm" id="type-form" method="post"
      action="index.php?option=com_jvisualcontent&view=type&layout=edit&id=<?php echo $this -> item -> id?>">
    <fieldset class="adminForm">
        <legend><?php echo JText::_('COM_JVISUALCONTENT_FIELDSET_DETAILS');?></legend>
        <div class="form-horizontal">
            <div class="row-fluid">
                <div class="span6">
                    <div class="control-group">
                        <div class="control-label"><?php echo $this -> form -> getLabel('title');?></div>
                        <div class="controls"><?php echo $this -> form -> getInput('title');?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this -> form -> getLabel('name');?></div>
                        <div class="controls"><?php echo $this -> form -> getInput('name');?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this -> form -> getLabel('class_icon');?></div>
                        <div class="controls"><?php echo $this -> form -> getInput('class_icon');?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this -> form -> getLabel('image_icon');?></div>
                        <div class="controls"><?php echo $this -> form -> getInput('image_icon');?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this -> form -> getLabel('introtext');?></div>
                        <div class="controls"><?php echo $this -> form -> getInput('introtext');?></div>
                    </div>
                </div>
                <div class="span6">
                    <div class="control-group">
                        <div class="control-label"><?php echo $this -> form -> getLabel('published');?></div>
                        <div class="controls"><?php echo $this -> form -> getInput('published');?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this -> form -> getLabel('css_code');?></div>
                        <div class="controls"><?php echo $this -> form -> getInput('css_code');?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this -> form -> getLabel('id');?></div>
                        <div class="controls"><?php echo $this -> form -> getInput('id');?></div>
                    </div>
    <!--                    <div class="control-group">-->
    <!--                        <div class="control-label">--><?php //echo $this -> form -> getLabel('js_code');?><!--</div>-->
    <!--                        <div class="controls">--><?php //echo $this -> form -> getInput('js_code');?><!--</div>-->
    <!--                    </div>-->

                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <div class="control-group">
                        <div class="control-label"><?php echo $this -> form -> getLabel('html');?></div>
                        <div class="controls"><?php echo $this -> form -> getInput('html');?></div>
                    </div>
                </div>
                <div class="span6">
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this -> form -> getLabel('extrafields');?>
                            <small class="muted" style="display: block; max-width: 160px;">
                                <em><?php echo JText::_('COM_JVISUALCONTENT_CHOOSE_EXTRAFIELD_DESC');?></em>
                            </small>
                        </div>
                        <div class="controls jvc_bootstrap3">
                            <div id="tz-extra-field" class="tz-well">
                                <div class="row-fluid">
                                    <div class="tz-relative span3">
                                        <div class="tz-drag">
                                            <span class="jvc_label-warning jvc_btn-block tz-title">
                                                <?php echo JText::_('COM_JVISUALCONTENT_SHORTCODE_TYPE');?><small class="extrafield jvc_btn-block">[/type]</small>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="tz-relative span3" data-type="loop">
                                        <div class="tz-drag">
                                            <span class="jvc_label-warning jvc_btn-block tz-title">
                                                <?php echo JText::_('COM_JVISUALCONTENT_LOOP');?><small class="extrafield jvc_btn-block">[loop][/loop]</small>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="tz-relative span3" data-toggle="jvc_modal" data-target="#myModal" data-type="autoid">
                                        <div class="tz-drag">
                                            <span class="jvc_label-warning jvc_btn-block tz-title">
                                                <?php echo JText::_('COM_JVISUALCONTENT_AUTO_ID');?><small class="extrafield jvc_btn-block">[/parentid<input type="number" min="0" name="parentid" style="width: 35px; padding: 0; height: auto;">]</small>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="tz-relative span3" data-type="autoid">
                                        <div class="tz-drag">
                                            <span class="jvc_label-warning jvc_btn-block tz-title">
                                                <?php echo JText::_('COM_JVISUALCONTENT_AUTO_ID_IN_LOOP');?><small class="extrafield jvc_btn-block">[/typeid<input type="number" min="0" name="typeid" style="width: 35px; padding: 0; height: auto;">]</small>
                                            </span>
                                        </div>
                                    </div>
                                </div>
<!--                                <div class="jvc_modal jvc_fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">-->
<!--                                    <div class="jvc_modal-dialog jvc_modal-sm">-->
<!--                                        <div class="jvc_modal-content">-->
<!--                                            <div class="jvc_modal-header">-->
<!--                                                <button type="button" class="jvc_close" data-dismiss="jvc_modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
<!--                                                <h4 class="modal-title" id="myModalLabel">Auto ID Setting</h4>-->
<!--                                            </div>-->
<!--                                            <div class="jvc_modal-body">-->
<!--                                                <input type="number" min="0" name="parentid" style="height: auto;">-->
<!--                                            </div>-->
<!--                                            <div class="jvc_modal-footer">-->
<!--                                                <button type="button" class="jvc_btn jvc_btn-default" data-dismiss="jvc_modal">--><?php //echo JText::_('JTOOLBAR_CLOSE');?><!--</button>-->
<!--                                                <button id="jvc_btn-autoid-add" type="button" class="jvc_btn jvc_btn-primary">--><?php //echo JText::_('COM_JVISUALCONTENT_ADD');?><!--</button>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
                                <?php
                                if($fields = $this -> listFields):
                                    $keys   = array_keys($fields);
                                    $exFields   = array();
                                    if($this -> item -> fields_id){
                                        $exFields   = explode(',',$this -> item -> fields_id);
                                    }
                                    foreach($fields as $i => $field):
                                        if($i % 3 == 0){
                                            ?>
                                            <div class="row-fluid">
                                        <?php }?>
                                        <div class="tz-relative span4">
                                            <!--                                            --><?php //echo (in_array($field -> id,$exFields))?' jvc_disabled':'';?>
                                            <div data-id="tz-extrafield-drag-<?php echo $field -> id;?>" class="tz-drag">
                                            <span class="jvc_label-primary jvc_btn-block tz-title">
                                                <?php echo ($field -> published)?$field -> title:'[ '.$field -> title.' ]';?><small class="extrafield jvc_btn-block">{<?php echo $field -> name;?>}</small>
                                            </span>
<!--                                                <div class="tz-inside">-->
<!--                                                    <div class="control-group">-->
<!--                                                        <div class="control-label">-->
<!--                                                            --><?php //echo ($field -> published)?$field -> title:'[ '.$field -> title.' ]';?>
<!--                                                            <small class="muted btn-block">{--><?php //echo $field -> name;?><!--}</small>-->
<!--                                                        </div>-->
<!--                                                        <div class="controls">-->
<!--                                                            --><?php //echo JHtml::_('ExtraFields.formRender',$field,false,array('editor'),3);?>
<!--                                                            <i class="icon-remove tz-delete"></i>-->
<!--                                                        </div>-->
<!--                                                    </div>-->
<!--                                                </div>-->
                                            </div>
                                        </div>
                                        <?php if(($i %3 == 2) ||($i == end($keys))){?>
                                        </div>
                                    <?php
                                    }
                                    endforeach;
                                else:
                                    echo JText::_('COM_JVISUALCONTENT_EXTRAFIELDS_EMPTY');
                                endif;
                                echo $this -> form -> getInput('extrafields');
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<!--            <div class="control-group">-->
<!--                <div class="control-label">-->
<!--                    --><?php //echo $this -> form -> getLabel('extrafields');?>
<!--                </div>-->
<!--                <div class="row-fluid controls">-->
<!--                    <div class="span4">-->
<!--                        <div id="tz-extra-field" class="tz-well">-->
<!---->
<!--                        --><?php
//                        if($fields = $this -> listFields):
//                            $keys   = array_keys($fields);
//                            $exFields   = array();
//                            if($this -> item -> fields_id){
//                                $exFields   = explode(',',$this -> item -> fields_id);
//                            }
//                            foreach($fields as $i => $field):
//                                if($i % 3 == 0){
//                        ?>
<!--                        <div class="row-fluid">-->
<!--                        --><?php //}?>
<!--                            <div class="tz-relative span4">-->
<!---->
<!--                                <div data-id="tz-extrafield-drag---><?php //echo $field -> id;?><!--" class="tz-drag--><?php //echo (in_array($field -> id,$exFields))?' jvc_disabled':'';?><!--">-->
<!--                                    <span class="jvc_label-primary jvc_btn-block tz-title">-->
<!--                                        --><?php //echo ($field -> published)?$field -> title:'[ '.$field -> title.' ]';?><!--<small class="jvc_btn-block">{--><?php //echo $field -> name;?><!--}</small>-->
<!--                                    </span>-->
<!--                                    <div class="tz-inside">-->
<!--                                        <div class="control-group">-->
<!--                                            <div class="control-label">-->
<!--                                                --><?php //echo ($field -> published)?$field -> title:'[ '.$field -> title.' ]';?>
<!--                                                <small class="muted btn-block">{--><?php //echo $field -> name;?><!--}</small>-->
<!--                                            </div>-->
<!--                                            <div class="controls">-->
<!--                                                --><?php //echo JHtml::_('ExtraFields.formRender',$field,false,array('editor'),3);?>
<!--                                                <i class="icon-remove tz-delete"></i>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!---->
<!--                            </div>-->
<!--                        --><?php //if(($i %3 == 2) ||($i == end($keys))){?>
<!--                        </div>-->
<!--                        --><?php
//                            }
//                            endforeach;
//                        endif;
//                        echo $this -> form -> getInput('extrafields');
//                        ?>
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div id="tz-shortcode-type" class="span5 well">-->
<!--                        --><?php
//                        if($extraFiedls = $this -> item -> fields):
//                            foreach($extraFiedls as $item):
//                        ?>
<!--                            <div class="tz-drag ui-draggable" data-id="tz-extrafield-drag---><?php //echo $item -> id;?><!--" style="display: block;">-->
<!--                                <span class="label-info btn-block tz-title ui-draggable-handle" style="display: none;">-->
<!--                                    --><?php //echo $item -> title;?><!--<small class="btn-block">{--><?php //echo $item -> name;?><!--}</small>-->
<!--                                </span>-->
<!--                                <div class="tz-inside" style="display: block;">-->
<!--                                    <div class="control-group">-->
<!--                                        <div class="control-label">-->
<!--                                            --><?php //echo ($item -> published == 0)?'[ '.$item -> title.' ]':$item -> title;?><!--<small class="muted btn-block">{--><?php //echo $item -> name;?><!--}</small>-->
<!--                                        </div>-->
<!--                                        <div class="controls">-->
<!---->
<!--                                            --><?php //echo JHtml::_('ExtraFields.formRender',$item,false,array('editor'),3);?>
<!--                                            -->
<!--                                            <i class="icon-remove tz-delete"></i>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <input type="hidden" aria-required="true" required="" value="--><?php //echo $item -> id;?><!--" name="jform[extrafields][]">-->
<!--                            </div>-->
<!--                        --><?php
//                            endforeach;
//                        endif;
//                        ?>
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->

        </div>
    </fieldset>

    <input type="hidden" value="com_jvisualcontent" name="option">
    <input type="hidden" value="" name="task">
    <?php echo JHTML::_('form.token');?>
</form>