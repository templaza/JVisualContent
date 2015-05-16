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

JFormHelper::loadFieldClass('hidden');

class JFormFieldTZHidden extends JFormFieldHidden{

    protected $type = 'TZHidden';

    protected function getInput()
    {

        $required     = $this->required ? ' required aria-required="true"' : '';
        // Initialize some field attributes.
        $class = !empty($this->class) ? ' class="' . $this->class . '"' : '';
        $disabled = $this->disabled ? ' disabled' : '';

        // Initialize JavaScript field attributes.
        $onchange = $this->onchange ? ' onchange="' . $this->onchange . '"' : '';

        return '<input type="hidden" name="' . $this->name . '" id="' . $this->id . '" value="'
        . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $disabled. $required. $onchange . ' />';
    }

}