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

$modelId    = $this -> item -> data_model_id;
$e_name     = ($this -> jscontent_ename)?$this -> jscontent_ename:'';
$link       = 'index.php?option=com_jvisualcontent&view=elements&layout=modal&element_id=column&tmpl=component&e_name='
    .$e_name.'&'.JSession::getFormToken().'=1';

$edit_link  = 'index.php?option=com_jvisualcontent&view=shortcode&layout=edit&action=edit_column&tmpl=component';

?>

<!-- Column 0 of row 0 level 0 -->
<div class="tz_column tz_sortable {width} content_holder<?php echo (!$this -> children?' tz_empty-column':'')?>"
     data-tz-column-width="11" data-element_type="tz_column" data-model-id="<?php echo $modelId;?>"
     data-element_id="column" data-width="{data_width}">
    <?php
    $bool   = !$this -> children;
    ?>
    <!-- Column 0 toolbar top of row 0 level 0 -->
    <div class="tz_controls tz_controls-visible controls">
        <?php
        if($attributes = $this -> item -> attributes):
            if(isset($attributes['background_image']) && $bg_image = $attributes['background_image']):
        ?>
        <span class="tz_column_image" style="background-image: url(<?php echo JUri::root().$bg_image;?>);" title="<?php echo JText::_('COM_JVISUALCONTENT_COLUMN_BACKGROUND_IMAGE');?>" data-toggle="jvc_tooltip"></span>
        <?php
            endif;
            if(isset($attributes['background_color']) && $bg_color = $attributes['background_color']):
        ?>
        <span class="tz_column_color" style="background-color: <?php echo $bg_color;?>" title="<?php echo JText::_('COM_JVISUALCONTENT_COLUMN_BACKGROUND_COLOR');?>" data-toggle="jvc_tooltip"></span>
        <?php
            endif;
        endif;?>
        <a data-toggle="jvc_tooltip" title="<?php echo JText::sprintf('COM_JVISUALCONTENT_PREPEND_ITEM',JText::_('COM_JVISUALCONTENT_THIS_COLUMN'));?>" href="javascript:"
           class="tz_control column_add fancybox" data-fancybox-href="<?php echo $link;?>">
            <i class="jvc_fa jvc_fa-plus"></i>
        </a>
        <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_EDIT_ITEM',JText::_('COM_JVISUALCONTENT_THIS_COLUMN'));?>" href="javascript:"
           data-fancybox-href="<?php echo $edit_link;?>"
           class="tz_control column_edit hasTooltip fancybox fancybox-medium">
            <i class="jvc_fa jvc_fa-pencil"></i>
        </a>
        <a data-toggle="jvc_tooltip" title="<?php echo JText::sprintf('COM_JVISUALCONTENT_REMOVE_ITEM',JText::_('COM_JVISUALCONTENT_THIS_COLUMN'));?>" href="javascript:" class="tz_control column_delete">
            <i class="jvc_fa jvc_fa-times"></i>
        </a>
    </div>
    <!-- End column 0 toolbar top of row 0 level 0 -->
    <!-- Column 0 content of row 0 level 0 -->
    <div class="element_wrapper jvc_panel-collapse jvc_collapse jvc_in"
         aria-expanded="true">
        <div class="tz_column_container tz_container_for_children<?php echo ($bool)?' fancybox tz_empty-container':'';?>"
            <?php echo ($bool)?' data-toggle="modal"':'';?>
             data-fancybox-href="<?php echo $link;?>">
            <?php
            // If first click button shortcode
            if($bool):
            ?>
                <i class="jvc_fa jvc_fa-plus"></i>
            <?php
            endif;
//            else:
                echo '[/type]';
            ?>
<!--            --><?php //endif;?>
        </div>
    </div>
    <!-- End column 0 content of row 0 level 0 -->
    <!-- Column toolbar bottom -->
    <div class="tz_controls tz_controls-visible controls bottom-controls jvc_panel-collapse jvc_collapse jvc_in"
         aria-expanded="true">
        <a data-toggle="jvc_tooltip" title="<?php echo JText::sprintf('COM_JVISUALCONTENT_APPEND_ITEM',JText::_('COM_JVISUALCONTENT_THIS_COLUMN'));?>" href="javascript:"
           class="tz_control column_add fancybox" data-fancybox-href="<?php echo $link;?>">
            <i class="jvc_fa jvc_fa-plus"></i>
        </a>
        <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_EDIT_ITEM',JText::_('COM_JVISUALCONTENT_THIS_COLUMN'));?>" href="javascript:"
           data-fancybox-href="<?php echo $edit_link;?>"
           class="tz_control column_edit hasTooltip fancybox fancybox-medium">
            <i class="jvc_fa jvc_fa-pencil"></i>
        </a>
        <a data-toggle="jvc_tooltip" title="<?php echo JText::sprintf('COM_JVISUALCONTENT_REMOVE_ITEM',JText::_('COM_JVISUALCONTENT_THIS_COLUMN'));?>" href="javascript:" class="tz_control column_delete">
            <i class="jvc_fa jvc_fa-times"></i>
        </a>
    </div>
</div>
<!-- End column 0 of row 0  level 0 -->