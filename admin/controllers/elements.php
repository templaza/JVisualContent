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

jimport('joomla.application.component.controlleradmin');

class JVisualContentControllerElements extends JControllerAdmin{

    protected $text_prefix  = 'COM_JVISUALCONTENT_ELEMENTS';

    public function getModel($name = 'Element', $prefix = 'JVisualContentModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function extrafieldForm(){
        $model  = $this -> getModel();
        $model -> setState('element.id',JRequest::getInt('id'));

        $document = JFactory::getDocument();
        $viewType = $document->getType();

        JLoader::register('JVisualContentViewElement',JPATH_ADMINISTRATOR.'/components/com_jvisualcontent/views/element/view.html.php');

        $view   = $this -> getView('Element',$viewType,'JVisualContentView');
        $view -> setModel($model,true);
        $view -> setLayout(JRequest::getCmd('layout','form'));
        $view -> display();
        die();
    }

}