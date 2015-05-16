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

$app        = JFactory::getApplication();
//$doc        = JFactory::getDocument();
$input      = $app -> input;


//$doc -> addStyleSheet(JVisualContentUri::root(true,null,true).'/bootstrap/'.JVISUALCONTENT_BOOTSTRAP_NAME.'/css/bootstrap.min.css');

$e_name     = $app -> input -> getCmd('e_name');
$tzRowId = $app -> input -> getInt('row_id');

?>
<script type="text/javascript">
    jQuery(document).ready(function(){
       jQuery('#tz-content-types').find('.tz-item').bind('click',function(){

           jQuery('input[type=hidden][name=id]').val(jQuery(this).data('id'));
           jQuery('input[type=hidden][name=element_type]').val(jQuery(this).data('element-type'));
           jQuery('input[type=hidden][name=element_source]').val(jQuery(this).data('element-source'));
           jQuery('#adminForm').submit();

//               jQuery.ajax({
//                   url:'index.php?option=com_jvisualcontent&task=types.extrafieldForm&layout=form&id='
//                       +jQuery(this).data('id')+'&tmpl=component',
//                   data:{
//
//                   }
//               }).success(function(data){
//                   if (data.length) {
//                       jQuery(data).insertAfter(jQuery('#adminForm'));
//                       jQuery('select').chosen();
//                       jQuery('#adminForm').remove();
//                   }
//               });
       });
    });
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jvisualcontent&view=shortcode'
    .(($tzshortcode = $input -> get('tzshortcode'))?'&tzshortcode='.$tzshortcode:'')
    .(($tzmodal = $input -> get('tzmodal'))?'&tzmodal='.$tzmodal:'')
    .(($tzaddnew = $input -> get('tzaddnew'))?'&tzaddnew='.$tzaddnew:'')
    .(($tzTypeId = $input -> getInt('type_id'))?'&type_id='.$tzTypeId:'')
    .(is_numeric($tzRowId)?'&row_id='.$tzRowId:'')
    .'&tmpl=component&e_name='.$e_name.'&'.JSession::getFormToken().'=1');?>"
      method="post" name="adminForm" id="adminForm" class="form-inline form-modal">

    <fieldset class="adminform jvc_bootstrap3">
        <div class="tz_add-element-dialog-heading">
            <h5 class="modal-title"><?php echo JText::_('COM_JVISUALCONTENT_ADD_ELEMENT');?></h5>
        </div>

        <div class="jvc_container-fluid" id="tz-content-types">
            <?php if($items = $this -> items):?>
            <div class="jvc_row tz_row">
<!--                <div class="tz-item col-xs-6 col-sm-4 col-md-3 col-lg-2 text-center" data-id="0"-->
<!--                     data-element-type="row"-->
<!--                     data-element-source="main">-->
<!--                    <a href="javascript:" class="title">-->
<!--                            <span class="text-primary fa fa-table tz-icon-medium text-primary"></span>-->
<!--                        --><?php //echo JText::_('COM_JVISUALCONTENT_ROW');?><!--<small class="btn-block muted">--><?php //echo JText::_('COM_JVISUALCONTENT_ROW_DESCRIPTION');?><!--</small>-->
<!--                    </a>-->
<!--                </div>-->
<!--                --><?php //// Readmore item ?>
<!--                <div class="tz-item col-xs-6 col-sm-4 col-md-3 col-lg-2 text-center" data-id="0"-->
<!--                     data-element-type="row"-->
<!--                     data-element-source="main">-->
<!--                    <a href="javascript:" class="title">-->
<!--                            <span class="text-primary fa fa-table tz-icon-medium text-primary"></span>-->
<!--                        --><?php //echo JText::_('COM_JVISUALCONTENT_READMORE');?><!--<small class="btn-block muted">--><?php //echo JText::_('COM_JVISUALCONTENT_ROW_DESCRIPTION');?><!--</small>-->
<!--                    </a>-->
<!--                </div>-->
                <?php
                    $keys   = array_keys($items);
                foreach($items as $i => $item):
                    if($item -> published == 1):
                        if(!is_numeric($item -> id)):
                            echo $this -> loadTemplate($item -> id);
                        else:?>
                <div class="tz-item jvc_col-xs-6 jvc_col-sm-4 jvc_col-md-3 jvc_col-lg-2 jvc_text-center" data-id="<?php echo $item -> id;?>">
                    <a href="javascript:" class="title">
                        <?php
                        if($classIcon = $item -> class_icon):
                        ?>
                        <span class="<?php echo $classIcon;?> tz-icon-medium jvc_text-primary"></span>
                        <?php elseif($imgIcon = $item -> image_icon): ?>
                        <img src="<?php echo JUri::root().$imgIcon?>" alt="<?php echo $item -> title;?>"/>
                        <?php else:?>
                        <span class="jvc_fa jvc_fa-ban tz-icon-medium jvc_text-primary"></span>
                        <?php endif;?>
                        <?php echo $item -> title;?><small class="jvc_btn-block jvc_text-muted"><?php echo $item -> introtext;?></small>
                    </a>
                </div>
                <?php
                        endif;
                    endif;
                endforeach;
                ?>
<!--                <div class="tz-item col-sm-3 text-center" data-id="0"-->
<!--                     data-element-type="row"-->
<!--                     data-element-source="plugin">-->
<!--                    <div class="title">-->
<!--                        <span class="text-primary fa fa-table tz-icon-medium text-primary"></span>-->
<!--                        Row<small class="btn-block muted">Place content elements inside the row</small>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
            <?php endif;?>
        </div>
    </fieldset>

    <input type="hidden" name="task" value="" />
    <input type="hidden" name="id" value="" />
    <input type="hidden" name="element_type" value="" />
    <input type="hidden" name="element_source" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>