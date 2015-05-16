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


$modelId    = JHtml::_('JVisualContent.guidV4');

if($this -> item && $this -> item -> html):
    $html       = $this -> item -> html;
    $boolLooop  = false;
//    $fields = $this -> item -> fields;
//    $this -> model_ids -> elementTypeIdRnd   = null;

    $el_link    = 'index.php?option=com_jvisualcontent&view=shortcode&layout=edit&action=edit_element&tmpl=component';
?>

<script id="tmpl-jscontent-new-element-<?php echo $this -> item -> id;?>" type="text/html">
    <?php if(preg_match_all('/.*?(\[loop\].*?\[\/loop\]).*?/msi',$html,$loop)):
        $loop   = $loop[count($loop) - 1];
        $boolLooop  = true;

        if(count($loop)){
            foreach($loop as $j => $lp){
                $lp2    = trim($lp);
                if(preg_match_all('/\[loop\].*?(<[a-z]+[1-6]?.*?>).*?\[\/loop\]/msi',$lp2,$matches2)){
                    $lp2    = str_replace(array('[loop]','[/loop]'),'',($lp2));
                    $lp2    = trim($lp2);

                    $matches2   = $matches2[count($matches2) -1];
                    if(!count($matches2) || (count($matches2) && $j != (count($loop) - 1))){
                        if(!preg_match('/aria-control\=["|\'].*?"|\'/msi',$lp2)){
                            $str = preg_replace('/^(<[a-z]+[1-6]?)(.*?>)/msi','$1 aria-control="[modelid][/modelid]" $2',$lp2);
                            $html   = str_replace($lp2,$str,$html);
                        }else{
                            $str = preg_replace('/^(<[a-z]+[1-6]?.*?aria-control\=["|\'])(.*?)/msi','$1 [modelid][/modelid] ',$lp2);
                            $html   = str_replace($lp2,$str,$html);
                        }
                    }else{
                        if(!preg_match('/^<[a-z]+[1-6]?.*?data-model-id\=["|\'].*?["|\'].*?>/i',$lp2)){
                            $str = preg_replace('/^(<[a-z]+[1-6]?)(.*?)/i','$1 data-model-id="'.JHtml::_('JVisualContent.guidV4').'" $2',$lp2);
                        }else{
                            $str = preg_replace('/^(<[a-z]+[1-6]?.*?data-model-id\=["|\'])(.*?)/msi','$1 '.JHtml::_('JVisualContent.guidV4').' $2',$lp2);
                        }
                            $html   = str_replace($lp2,$str,$html);
                    }
                }
            }
        }
    ?>
    <div class="content_element tz_element-item tz_sortable"
         data-element_type="<?php echo $this -> item -> name;?>"
         data-element_id="<?php echo $this -> item -> id;?>"
         data-element-item-count="<?php echo ($this -> elementItem)?count($this -> elementItem):1;?>"
         data-model-id="<?php echo $modelId;?>">
        <div class="tz_controls">
            <div class="jvc_btn-group jvc_btn-group-xs tz_controls-out-tc tz_controls-content-widget">
                <a class="tz_control-btn tz_element-name element-move jvc_btn jvc_btn-inverse">
                <span title="<?php echo JText::sprintf('COM_JVISUALCONTENT_DRAG_MOVE_ITEM',$this -> item -> title);?>"
                      data-toggle="jvc_tooltip"
                      class="tz_btn-content">
                    <?php
                    if($classIcon = $this -> item -> class_icon):?>
                        <span class="<?php echo $classIcon;?> tz_icon"></span>
                    <?php elseif($imgIcon = $this -> item -> image_icon): ?>
                        <img src="<?php echo JUri::root().$imgIcon?>" alt="<?php echo $this -> item -> title;?>"/>
                    <?php else:?>
                        <span class="jvc_fa jvc_fa-ban tz_icon"></span>
                    <?php endif;?>
                    <span class="jvc_fa jvc_fa-arrows"></span> <?php echo $this -> item -> title;?>
                </span>
                </a>
                <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_ADD_ITEM',$this -> item -> title);?>" href="javascript:"
                   data-toggle="jvc_tooltip"
                   class="tz_control-btn tz_control-btn-add jvc_btn jvc_btn-inverse"><span class="tz_btn-content"><span
                    class="jvc_fa jvc_fa-plus"></span></span></a>
                <?php
                $_html   = preg_replace('/(\[loop].*?\[\/loop\])/msi','',$this -> item -> html);
                if(preg_match('/\{.*?\}/msi',$_html)){
                ?>
                <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_EDIT_ITEM',$this -> item -> title);?>" href="javascript:"
                   data-fancybox-href="<?php echo $el_link;?>"
                   class="tz_control-btn tz_control-btn-edit jvc_btn jvc_btn-inverse hasTooltip fancybox fancybox-medium"><span class="tz_btn-content"><span
                    class="jvc_fa jvc_fa-pencil"></span></span></a>
                <?php }?>
                <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_CLONE_ITEM',$this -> item -> title);?>" href="javascript:"
                   data-toggle="jvc_tooltip"
                   class="tz_control-btn tz_control-btn-clone jvc_btn jvc_btn-inverse"><span class="tz_vc_btn-content"><span
                    class="jvc_fa jvc_fa-files-o"></span></span></a>
                <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_REMOVE_ITEM',$this -> item -> title);?>" href="javascript:"
                   data-toggle="jvc_tooltip"
                   class="tz_control-btn tz_control-btn-delete jvc_btn jvc_btn-inverse"><span class="tz_btn-content"><span class="jvc_fa jvc_fa-times"></span></span></a>
            </div>
        </div>
    <?php else:?>
    <div class="content_element tz_sortable" data-model-id="<?php echo $modelId;?>"
         data-element_type="<?php echo $this -> item -> name;?>"
         data-element_id="<?php echo $this -> item -> id;?>">
            <div class="tz_controls">
                <div class="tz_controls-cc jvc_btn-group jvc_btn-group-xs">
                    <a class="tz_control-btn tz_element-name element-move jvc_btn jvc_btn-success">
                        <span data-toggle="jvc_tooltip"
                            title="<?php echo JText::sprintf('COM_JVISUALCONTENT_DRAG_MOVE_ITEM',$this -> item -> title);?>"
                              class="tz_btn-content">
                        <?php
                        if($classIcon = $this -> item -> class_icon):?>
                            <span class="<?php echo $classIcon;?> tz_icon"></span>
                        <?php elseif($imgIcon = $this -> item -> image_icon): ?>
                            <img src="<?php echo JUri::root().$imgIcon?>" alt="<?php echo $this -> item -> title;?>"/>
                        <?php else:?>
                            <span class="jvc_fa jvc_fa-ban tz_icon"></span>
                        <?php endif;?>
                        <i class="jvc_fa jvc_fa-arrows"></i> <?php echo $this -> item -> title;?></span>
                    </a>
                    <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_EDIT_ITEM',$this -> item -> title);?>" href="javascript:"
                       data-fancybox-href="<?php echo $el_link;?>" data-toggle="jvc_tooltip"
                       class="tz_control-btn tz_control-btn-edit jvc_btn jvc_btn-success hasTooltip fancybox fancybox-medium">
                        <span class="tz_btn-content"><span class="jvc_fa jvc_fa-pencil"></span></span>
                    </a>
                    <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_CLONE_ITEM',$this -> item -> title);?>" href="javascript:"
                       data-toggle="jvc_tooltip"
                       class="tz_control-btn tz_control-btn-clone jvc_btn jvc_btn-success"><span class="tz_btn-content"><span class="jvc_fa jvc_fa-files-o"></span></span></a>
                    <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_REMOVE_ITEM',$this -> item -> title);?>" href="javascript:"
                       data-aria-control="<?php echo $modelId;?>" data-toggle="jvc_tooltip"
                       class="tz_control-btn tz_control-btn-delete jvc_btn jvc_btn-success"><span class="tz_btn-content"><span class="jvc_fa jvc_fa-times"></span></span></a>
                </div>
            </div>
    <?php endif; ?>
        <div class="element_wrapper">
        <?php
            if(preg_match_all('/(\[\/type\])/msi',$html,$typeids)){

                $html   = str_replace(array('[loop]','[/loop]'),'',$html);
            }

            if(preg_match_all('/(\[\/type\])/msi',$html,$typeids)){
                $html   = preg_replace('/(\[\/type\])/msi',$this -> loadTemplate('element_children'),$html);
            }
            echo $html;
         ?>
        </div>
    </div>
</script>
<?php endif;?>