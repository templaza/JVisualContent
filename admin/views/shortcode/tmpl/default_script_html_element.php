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
//if($this -> item && $this -> item -> html):
?>

<?php
if($elementItems = $this-> elementItem):
    if(is_array($elementItems)):
        foreach($elementItems as $i => $elementItem):
            $_elementItem    = trim($elementItem);
            if(count($elementItems) > 1 && $i != (count($elementItems) - 1)){
                if(!preg_match('/aria-control\=["|\'].*?"|\'/msi',$_elementItem)){
                    $_elementItem = preg_replace('/^(<[a-z]+[1-6]?.*?)(>)/msi','$1 aria-control="[modelid][/modelid]" $2',$_elementItem);
                }else{
                    $_elementItem = preg_replace('/^(<[a-z]+[1-6]?.*?aria-control\=["|\'])(.*?)/msi','$1 [modelid][/modelid] $2',$_elementItem);
                }
            }else{
                if(!preg_match('/^<[a-z]+[1-6]?.*?data-model-id\=["|\'].*?["|\'].*?>/i',$_elementItem)){
                    $_elementItem = preg_replace('/^(<[a-z]+[1-6]?)(.*?)/i','$1 data-model-id="'.JHtml::_('JVisualContent.guidV4').'" $2',$_elementItem);
                }else{
                    $_elementItem = preg_replace('/^(<[a-z]+[1-6]?.*?data-model-id\=["|\'])(.*?)/msi','$1 '.JHtml::_('JVisualContent.guidV4').' $2',$_elementItem);
                }
            }

?>
<script id="template-jscontent-new-element-<?php echo $this -> item -> id;?>-item-<?php echo $i;?>" type="text/html">
    <?php echo $_elementItem;?>
</script>
<?php endforeach;
    else:
?>
<script id="template-jscontent-new-element-<?php echo $this -> item -> id;?>-item" type="text/html">
    <?php echo $this -> elementItem;?>
</script>
<?php endif;
endif;
//endif;?>

