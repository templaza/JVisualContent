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

if($this -> item && $this -> item -> fields):
    $extrafields    = $this -> item -> fields;
?>

<!--<form method="post"-->
<!--      action="index.php?option=com_jvisualcontent&view=type&task=type.listfields&tmpl=component">-->
<!--    <div class="tz_add-element-dialog-heading">-->
<!--        <h5 class="modal-title">--><?php //echo JText::sprintf('COM_JVISUALCONTENT_HEADING_SETTING',$this -> item -> title);?><!--</h5>-->
<!--    </div>-->
<!---->
<!--    <div class="jvc_container-fluid">-->
<!--        <div class="jscontent_edit_form_elements jvc_form-horizontal">-->
<!---->
<!--            <div class="jscontent_shortcode-toolbar jvc_form-group jvc_clearfix">-->
<!--                <div class="jvc_pull-right">-->
<!--                    <button data-save="true" class="jvc_btn tz_panel-btn-save jvc_btn-danger">--><?php //echo JText::_('COM_JVISUALCONTENT_SAVE_CHANGE');?><!--</button>-->
<!--                    <button data-dismiss="jvc_modal" class="jvc_btn tz_panel-btn-close jvc_btn-default" type="button">--><?php //echo JText::_('JTOOLBAR_CLOSE');?><!--</button>-->
<!--                </div>-->
<!--            </div>-->
            <?php
            foreach($extrafields as $item):
            ?>
            <div class="jvc_form-group">
                <div class="jvc_col-sm-3">
                    <label class="element_label"<?php echo $item -> description?' data-toggle="jvc_tooltip"':'';?>
                           title="<?php echo $item -> description;?>"><?php echo $item -> title;?></label>
                </div>
                <div class="jvc_col-sm-9 edit_form_line">
                    <?php
                    $optValues  = json_decode($item -> option_value);
                    switch($item -> type):
                        default:
                            ?>
                                <input type="text" name="jform[params][<?php echo $item -> name;?>]"
                                       class="textinput" value="<?php echo $item -> value;?>">
                            <?php
                            break;
                        case 'textfield':
                            ?>
                            <input type="<?php echo ($optValues && isset($optValues -> type))?$optValues -> type:'text';?>"
                                   class="textinput" name="jform[params][<?php echo $item -> name;?>]" value="<?php echo (isset($item -> value))?$item -> value:'';?>">
                            <?php
                            break;
                        case 'editor':
                            $editor = JFactory::getEditor();
                            echo $editor -> display('jform[params]['.$item -> name.']',
                                htmlspecialchars_decode(trim($item -> value)),'100%','200px','60',
                                '20',array('readmore'),'jform_'.$item -> name);
                            break;
                        case 'textarea':
                            ?>
                            <textarea name="jform[params][<?php echo $item -> name;?>]"><?php echo $item -> value;?></textarea>
                            <?php
                            break;
                        case 'checkbox':
                        case 'radio':
                            if(isset($optValues -> title)){
                                $titles = $optValues -> title;
                                $values = null;
                                if(isset($optValues -> value)){
                                    $values = $optValues -> value;
                                }
                                if($item -> type == 'checkbox'){
                                    if(!is_array($item -> value)){
                                        $item -> value  = explode(' ',$item -> value);
                                    }
                                }
                                foreach($titles as $i => $title){
                                    ?>
                                    <label><input type="<?php echo $item -> type;?>" name="jform[params][<?php echo $item -> name;?>]<?php echo ($item -> type == 'checkbox')?'[]':'';?>"
                                                  value="<?php echo $values[$i];?>"<?php echo (isset($item -> value) && ((is_array($item -> value) && in_array($values[$i],$item -> value))
                                                || (is_string($item -> value) && $item -> value == $values[$i])))?' checked="checked"':'';?>> <?php echo $title;?></label>
                                <?php
                                }
                            }
                            break;
                        case 'multiselect':
                            ?>
                            <select name="jform[params][<?php echo $item -> name;?>][]" multiple="true">
                                <?php
                                if(isset($optValues -> title)){
                                    $titles = $optValues -> title;
                                    $values = $optValues -> value;

                                    if(!is_array($item -> value)){
                                        $item -> value  = explode(' ',$item -> value);
                                    }
                                    foreach($titles as $i => $title){
                                        ?>
                                        <option<?php echo (isset($item -> value) && ((is_string($item -> value) && $item -> value == $values[$i]) ||(is_array($item -> value) && in_array($values[$i],$item -> value))))?' selected="true"':'';?> value="<?php echo $values[$i];?>"><?php echo $title;?></option>
                                    <?php
                                    }

                                }
                                ?>
                            </select>
                            <?php
                            break;
                        case 'select':
                            ?>
                            <select name="jform[params][<?php echo $item -> name;?>]">
                                <?php
                                if(isset($optValues -> title)){
                                    $titles = $optValues -> title;
                                    $values = $optValues -> value;
                                    foreach($titles as $i => $title){
                                        ?>
                                        <option<?php echo ($item -> value == $values[$i])?' selected="true"':'';?> value="<?php echo $values[$i];?>"><?php echo $title;?></option>
                                    <?php
                                    }

                                }
                                ?>
                            </select>
                            <?php
                            break;
                        case 'calendar':
                            echo JHtml::_('calendar',(isset($item -> value))?$item -> value:'',$item -> name.']',$item -> name,'%Y-%m-%d',array('class' => 'textinput'));
                            break;
                        case 'image':
                            echo JHtml::_('tz_image.media',(isset($item -> value))?$item -> value:'','jform[params]['.$item -> name.']',$item -> name,null,null,array('class' => 'tz_media-preview'));
                            ?>

                            <?php
                            break;
                    endswitch;
                    if($item -> description):
                    ?>
                    <small class="description jvc_text-muted jvc_clearfix"><em><?php echo $item -> description;?></em></small>
                    <?php endif;?>
                </div>
            </div>
            <?php endforeach;?>
<!--        </div>-->
<!--    </div>-->
<!--</form>-->
<?php endif;?>