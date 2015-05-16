<?php
/*------------------------------------------------------------------------

# TZ Shortcode Extension

# ------------------------------------------------------------------------

# author    DuongTVTemPlaza

# copyright Copyright (C) 2012 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die;

$modelId    = $this -> item -> data_model_id;

$e_name     = ($this -> jscontent_ename)?'_'.$this -> jscontent_ename:'';
$link       = 'index.php?option=com_jvisualcontent&view=elements&layout=modal'.(isset($this -> item)
    && $this -> item && isset($this -> item -> element_id)?'&element_id='.$this -> item -> element_id:'')
    .'&tmpl=component&e_name='
    .$e_name.'&'.JSession::getFormToken().'=1';

$el_link    = 'index.php?option=com_jvisualcontent&view=shortcode&layout=edit&action=edit_element&tmpl=component';
?>
<div class="tz_element-column" data-model-id="<?php echo $modelId;?>"
     data-aria-control="<?php echo (isset($this -> item -> aria_control_id) && $this -> item -> aria_control_id)?$this -> item -> aria_control_id:'[modelid][/modelid]';?>"
     data-element-item-count="<?php echo ($this -> elementItem)?count($this -> elementItem):1;?>">
    <div class="tz_controls">
        <div class="jvc_btn-group jvc_btn-group-xs tz_controls-tc tz_control-container">
            <a class="jvc_btn jvc_btn-warning tz_element-name">
                <span class="tz_btn-content"><span class="jvc_fa jvc_fa-arrows"></span> <?php echo JText::_('COM_JVISUALCONTENT_SECTION');?></span>
            </a>
            <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_PREPEND_ITEM',JText::_('COM_JVISUALCONTENT_SECTION'));?>" href="javascript:" data-toggle="jvc_tooltip"
               data-fancybox-href="<?php echo $link;?>"
               class="jvc_btn jvc_btn-warning tz_control-btn tz_control-btn-prepend tz_edit fancybox">
                <span class="jvc_fa jvc_fa-plus"></span>
            </a>
            <?php
            if(preg_match_all('/\[loop\].*?\{.*?\}.*?\[\/loop\]/msi',$this -> item -> html)){
            ?>
            <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_EDIT_ITEM',JText::_('COM_JVISUALCONTENT_SECTION'));?>" href="javascript:"
               data-toggle="jvc_tooltip"
               data-fancybox-href="<?php echo $el_link;?>"
               class="jvc_btn jvc_btn-warning tz_control-btn tz_control-btn-edit fancybox fancybox-medium">
                <span class="tz_btn-content"><span class="jvc_fa jvc_fa-pencil"></span></span>
            </a>
            <?php }?>
            <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_CLONE_ITEM',JText::_('COM_JVISUALCONTENT_SECTION'));?>" href="javascript:"
               data-toggle="jvc_tooltip"
               class="jvc_btn jvc_btn-warning tz_control-btn tz_control-btn-clone"><span class="tz_btn-content"><span class="jvc_fa jvc_fa-files-o"></span></span></a>
            <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_REMOVE_ITEM',JText::_('COM_JVISUALCONTENT_SECTION'));?>" href="javascript:"
               data-toggle="jvc_tooltip"
               class="jvc_btn jvc_btn-warning tz_control-btn tz_control-btn-delete"><span class="tz_btn-content"><span class="jvc_fa jvc_fa-times"></span></span></a>
        </div>
    </div>
    <div class="element_wrapper">
        <?php echo $this -> loadTemplate('element_children_blank');?>
    </div>
</div>