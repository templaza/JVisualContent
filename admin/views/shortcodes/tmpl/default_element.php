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

if($this -> item && $this -> item -> html):
    $boolLooop  = false;
//    $modelIds   = $this -> model_ids;
    $html       = $this -> item -> html;
    $el_link    = 'index.php?option=com_jvisualcontent&view=shortcode&layout=edit&action=edit_element&tmpl=component';
    $e_name         = ($this -> jscontent_ename)?'_'.$this -> jscontent_ename:'';

    $filter_link    = '';

    if(isset($this -> item) && $this -> item && isset($this -> item -> id)){
        if(preg_match_all('/.*?(\[loop\].*?\[\/type\].*?\[\/loop\]).*?/msi',$html)){
            $filter_link    = '&element_id='.$this -> item -> id;
        }elseif(preg_match_all('/.*?(\[\/type\]).*?/msi',$html)){
            $filter_link    = '&tzaddnew=true';
        }
    }else{
        $filter_link    = '';
    }

    $link           = 'index.php?option=com_jvisualcontent&view=elements&layout=modal'.$filter_link.'&tmpl=component&e_name='
        .$e_name.'&'.JSession::getFormToken().'=1';

    $this -> elementItem    = null;
    if(preg_match_all('/.*?(\[loop\].*?\[\/loop\]).*?/msi',$html,$loop)){
        $boolLooop  = true;
        foreach($loop[count($loop) - 1] as $j => $lp){
            $this -> elementItem[$j]    = null;
        }
    }
    ob_start();
    ?>
<div class="content_element tz_element-item tz_sortable"
     data-element_type="<?php echo $this -> item -> name;?>"
     data-element-item-count="<?php echo ($this -> elementItem)?count($this -> elementItem):1;?>"
     data-element_id="<?php echo $this -> item -> id;?>"
     data-model-id="<?php echo $this -> item -> data_model_id;?>">
    <!--- Element toolbar -->
    <div class="tz_controls">
        <div class="jvc_btn-group jvc_btn-group-xs tz_controls-out-tc tz_controls-content-widget">
            <a class="tz_control-btn tz_element-name element-move jvc_btn jvc_btn-inverse">
                <span title="<?php echo JText::sprintf('COM_JVISUALCONTENT_DRAG_MOVE_ITEM',$this -> item -> title);?>" data-toggle="jvc_tooltip"
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
               data-elemet-item-count="<?php echo ($this -> elementItem)?count($this -> elementItem):1;?>"
               class="tz_control-btn tz_control-btn-add jvc_btn jvc_btn-inverse hasTooltip"><span class="tz_btn-content"><span
                        class="jvc_fa jvc_fa-plus"></span></span></a>
            <?php
            $_html   = preg_replace('/(\[loop].*?\[\/loop\])/msi','',$html);
            if(preg_match('/\{.*?\}/msi',$_html)){
                ?>
                <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_EDIT_ITEM',$this -> item -> title);?>" href="javascript:"
                   data-fancybox-href="<?php echo $el_link;?>"
                   class="tz_control-btn tz_control-btn-edit jvc_btn jvc_btn-inverse hasTooltip fancybox fancybox-medium">
                    <span class="tz_btn-content"><span
                            class="jvc_fa jvc_fa-pencil"></span></span>
                </a>
            <?php }?>
            <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_CLONE_ITEM',$this -> item -> title);?>" href="javascript:"
               class="tz_control-btn tz_control-btn-clone jvc_btn jvc_btn-inverse hasTooltip">
                <span class="tz_vc_btn-content"><span
                        class="jvc_fa jvc_fa-files-o"></span></span></a>
            <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_REMOVE_ITEM',$this -> item -> title);?>" href="javascript:"
               class="tz_control-btn tz_control-btn-delete jvc_btn jvc_btn-inverse hasTooltip">
                <span class="tz_btn-content"><span class="jvc_fa jvc_fa-times"></span></span>
            </a>
        </div>
    </div>
    <!-- End element toolbar -->
    <?php
    $loop_html  = ob_get_contents();

    ob_end_clean();
    ob_start();
    ?>
    <div class="content_element <?php echo preg_match_all('/.*?(\[\/type\]).*?/msi',$html)?'tz_element-item ':'';?>tz_sortable"
         data-model-id="<?php echo $this -> item -> data_model_id;?>"
         data-element_type="<?php echo $this -> item -> name;?>"
         data-element_id="<?php echo $this -> item -> id;?>">
        <div class="tz_controls">
            <div class="tz_controls-cc jvc_btn-group jvc_btn-group-xs<?php echo preg_match_all('/.*?(\[\/type\]).*?/msi',$html)?' tz_controls-out-tc tz_controls-content-widget':'';?>">
                <a class="tz_control-btn tz_element-name element-move jvc_btn jvc_btn-success">
                    <span title="<?php echo JText::sprintf('COM_JVISUALCONTENT_DRAG_MOVE_ITEM',$this -> item -> title);?>" data-toggle="jvc_tooltip"
                          class="tz_btn-content">
                    <?php
                    if($classIcon = $this -> item -> class_icon):?>
                        <span class="<?php echo $classIcon;?> tz_icon"></span>
                    <?php elseif($imgIcon = $this -> item -> image_icon): ?>
                        <img src="<?php echo JUri::root().$imgIcon?>" alt="<?php echo $this -> item -> title;?>"/>
                    <?php else:?>
                        <span class="jvc_fa jvc_fa-ban tz_icon"></span>
                    <?php endif;?>
                        <!-- End shortcode type's icon -->
                    <i class="jvc_fa jvc_fa-arrows"></i> <?php echo $this -> item -> title;?></span>
                </a>

                <?php if(preg_match_all('/.*?(\[type\].*?\[\/type\]).*?/msi',$html)):?>
                    <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_PREPEND_ITEM',JText::_('COM_JVISUALCONTENT_SECTION'));?>" href="javascript:" data-toggle="jvc_tooltip"
                       data-fancybox-href="<?php echo $link;?>"
                       class="jvc_btn jvc_btn-success tz_control-btn tz_control-btn-prepend tz_control-btn-add fancybox">
                        <span class="jvc_fa jvc_fa-plus"></span>
                    </a>
                <?php endif;?>

                <?php if(preg_match_all('/.*?(\{.*?\}).*?/msi',$html)):?>
                <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_EDIT_ITEM',$this -> item -> title);?>" href="javascript:"
                   data-fancybox-href="<?php echo $el_link;?>"
                   class="tz_control-btn tz_control-btn-edit jvc_btn jvc_btn-success hasTooltip fancybox fancybox-medium">
                    <span class="tz_btn-content"><span class="jvc_fa jvc_fa-pencil"></span></span>
                </a>
                <?php endif;?>
                <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_CLONE_ITEM',$this -> item -> title);?>" href="javascript:"
                   class="tz_control-btn tz_control-btn-clone jvc_btn jvc_btn-success hasTooltip">
                    <span class="tz_btn-content"><span class="jvc_fa jvc_fa-files-o"></span></span>
                </a>
                <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_REMOVE_ITEM',$this -> item -> title);?>" href="javascript:"
                   class="tz_control-btn tz_control-btn-delete jvc_btn jvc_btn-success hasTooltip">
                    <span class="tz_btn-content"><span class="jvc_fa jvc_fa-times"></span></span>
                </a>
            </div>
        </div>

        <?php
        $original_html  = ob_get_contents();
        ob_end_clean();

        if(preg_match_all('/.*?(\[loop\].*?\[\/type\].*?\[\/loop\]).*?/msi',$this -> item -> html)):
            echo $loop_html;
        else:
            echo $original_html;
        endif;

//        if($boolLooop):
//            echo $loop_html;
//        else:
//            echo $original_html;
//        endif;
        ?>

        <div class="element_wrapper">
<?php

            $elementItem    = null;

            if(preg_match_all('/.*?(\[loop\].*?\[\/loop\]).*?/msi',$html,$loop)){
                $loop           = $loop[count($loop) - 1];

                $aria_model_id  =  JHtml::_('JVisualContent.guidV4');

                if(count($loop)){
                    foreach($loop as $i => $lp){
                        if(preg_match_all('/\[loop\].*?(<[a-z]+[1-6]?.*?\>).*?\[\/loop\]/msi',$lp,$matches)){
                            $matches    = $matches[count($matches) -1];
                            $match      = $matches[count($matches) -1];
                            $lp         = str_replace(array('[loop]','[/loop]'),'',$lp);
                            $lp         = trim($lp);
                            $match      = str_replace(array('[loop]','[/loop]'),'',$match);
                            if($i == (count($loop) - 1)){
                                if(count($matches) && $match){

                                    if(!preg_match('/^<[a-z]+[1-6]?.*?data-model-id\=["|\'].*?"|\'.*?\>/msi',$match)){
                                        $str = preg_replace('/^(<[a-z]+[1-6]?)(.*?\>)/msi','$1 data-model-id="'.JHtml::_('JVisualContent.guidV4').'" $2',$match);
                                    }else{
                                        $str = preg_replace('/^(<[a-z]+[1-6]?.*?data-model-id\=["|\'])(.*?)/msi','$1 '.JHtml::_('JVisualContent.guidV4').' $2',$match);
                                    }

                                    $html   = str_replace($match,$str,$html);

                                }
                            }else{
                                if(!preg_match('/aria-control\=["|\'].*?["|\']/msi',$lp)){
                                    $str = preg_replace('/^(<[a-z]+[1-6]?)(.*?\>)/msi','$1 aria-control="[modelid][/modelid]" $2',$lp);
                                }else{
                                    $str = preg_replace('/^(<[a-z]+[1-6]?.*?aria-control\=["|\'])(.*?)/msi','$1 [modelid][/modelid] $2',$lp);
                                }

                                $html   = str_replace($lp,$str,$html);
                            }
                        }
                    }
                }

//                if(preg_match_all('/(\[type\].*?\[\/type\])/msi',$html,$typeids)){
//                    if(count($loop) > 1){
//                        foreach($loop as $j => $lp){
//                            $test   = preg_replace('/(\[type\].*?\[\/type\])/msi',$this -> loadTemplate('element_children'),$lp);
//                            $test   = str_replace(array('[loop]','[/loop]'),'',$test);
//                            $this -> elementItem[$j]    = $test;
//                        }
//                    }else{
//                        $test   = preg_replace('/(\[type\].*?\[\/type\])/msi',$this -> loadTemplate('element_children'),$loop);
//                        $test   = str_replace(array('[loop]','[/loop]'),'',$test);
//                        $this -> elementItem    = $test;
//                    }
//                }

//                $html   = str_replace(array('[loop]','[/loop]'),'',$html);
            }


            if(!preg_match_all('/.*?(\[loop\].*?\[\/type\].*?\[\/loop\]).*?/msi',$this -> item -> html)){
                if(preg_match_all('/.*?(\[\/type\]).*?/msi',$this -> item -> html)) {
                    $html = preg_replace('/(\[\/type\])/msi', $this->loadTemplate('element_children_blank'), $html);
                }
            }

//            if(preg_match_all('/(\[typeid[0-9+]?\].*?\[\/typeid[0-9+]?\])/msi',$html,$typeids)){
//                $typeids    = $typeids[count($typeids) - 1];
//                $typeids    = array_filter($typeids);
//                $typeids    = array_unique($typeids);
//                if(count($typeids)){
//                    foreach($typeids as $typeid){
//                        $html   = preg_replace('/('.str_replace(array('[',']','/'),array('\\[','\\]','\\/'),$typeid).')/msi',$modelIds -> elementTypeIdRnd,$html);
//                    }
//                }
//            }

//            if(preg_match_all('/(\[type\].*?\[\/type\])/msi',$html)){
//                $html   = preg_replace('/(\[type\].*?\[\/type\])/msi',str_replace('[modelid2][/modelid2]', $aria_model_id,$this -> loadTemplate('element_children')),$html);
//                $html   = str_replace('[modelid2][/modelid2]', $this -> item -> data_model_id,$html);
//            }

            echo $html;
//            ?>

        </div>
    </div>
    <!-- End Element accordion -->
<?php endif;?>