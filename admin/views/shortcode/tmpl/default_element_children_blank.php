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

$e_name     = ($this -> jscontent_ename)?'_'.$this -> jscontent_ename:'';
$filter_link    = '';

if(isset($this -> item) && $this -> item && isset($this -> item -> id)){
    if(preg_match_all('/.*?(\[loop\].*?\[\/type\].*?\[\/loop\]).*?/msi',$this -> item -> html)){
        $filter_link    = '&element_id='.$this -> item -> id;
    }elseif(preg_match_all('/.*?(\[\/type\]).*?/msi',$this -> item -> html)){
        $filter_link    = '&tzaddnew=true';
    }
}else{
    $filter_link    = '';
}

$link           = 'index.php?option=com_jvisualcontent&view=elements&layout=modal'.$filter_link.'&tmpl=component&e_name='
    .$e_name.'&'.JSession::getFormToken().'=1';
?>
<div class="tz_column_container tz_container_for_children tz_empty-container fancybox<?php echo (!preg_match_all('/.*?(\[loop\].*?\[\/type\].*?\[\/loop\]).*?/msi',
    $this -> item -> html) && preg_match_all('/.*?(\[\/type\]).*?/msi',$this -> item -> html))?' jscontent_element_container':'';?>"
     data-fancybox-href="<?php echo $link;?>">
    <i class="jvc_fa jvc_fa-plus"></i>
</div>