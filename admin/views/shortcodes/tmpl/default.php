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

if($this -> item):
    if(in_array($this -> item -> id,array('row','column','readmore'))){
        echo $this -> loadTemplate($this -> item -> id);
    }elseif($this -> item -> id == 'element') {
        echo $this -> loadTemplate('element_children');
    }else{
        echo $this -> loadTemplate('element');
    }
endif;
