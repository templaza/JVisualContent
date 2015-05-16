<?php
/*------------------------------------------------------------------------

# JVisual Content Extension

# ------------------------------------------------------------------------

# author    DuongTVTemPlaza

# copyright Copyright (C) 2012 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.document.document');

class JVisualContentDocument extends JDocument{


    protected $page_content     = null;

    protected $_sys_styleSheet  = array();
    protected $_sys_scripts     = array();
    protected $_custom          = array();

    public function __construct($options = array())
    {
        $this -> _sys_styleSheet = array(
            'bootstrap/'.JVISUALCONTENT_BOOTSTRAP_NAME.'/css/bootstrap.min.css',
            'fonts/'.JVISUALCONTENT_FONTAWESOME_NAME.'/css/font-awesome.min.css',
            'css/layout.admin.css'
        );
        $this -> _sys_scripts = array(
            'js/jquery-ui.min.js',
            'js/jvisualcontent.min.js'
        );
        parent::__construct($options);
        if($body = JVisualContentLoader::$body){
            $this -> page_content   = $body;
        }
    }

//    public function addStyleSheet($url, $type = 'text/css', $media = null, $attribs = array())
//    {
//        $this->_styleSheets[$url]['mime'] = $type;
//        $this->_styleSheets[$url]['media'] = $media;
//        $this->_styleSheets[$url]['attribs'] = $attribs;
//
//        return $this;
//    }


    public function addSystemStyleSheet($url=null, $type = 'text/css', $media = null, $attribs = array()){
        if($url){
            $this -> addStyleSheet($url,$type,$media,$attribs);
        }else{
            if(count($this -> _sys_styleSheet)){
                foreach($this -> _sys_styleSheet as $path){
                    $this -> _styleSheets[JVisualContentUri::root(true,null,true).'/'.$path]   = array(
                        'mime' => 'text/css',
                        'media' => null,
                        'attribs' => array()
                    );
                }
            }
        }
    }

    public function addSystemScript($url=null, $type = "text/javascript", $defer = false, $async = false){
        if($url){
            $this -> addScript($url,$type,$defer,$async);
        }else{
            if(count($this -> _sys_scripts)){
                foreach($this -> _sys_scripts as $path){
                    $this -> _scripts[JVisualContentUri::root(true,null,true).'/'.$path]   = array(
                        'mime' => 'text/javascript',
                        'defer' => false,
                        'async' => false
                    );
                }
            }
        }
    }

    public function addCustomTag($html)
    {
        $this->_custom[] = trim($html);

        return $this;
    }

    public function render($cache = false, $params = array()){
        if(count($params)){
            if($params['system']){
                $this -> addSystemStyleSheet();
                $this -> addSystemScript();
            }
        }

        if($page_content = $this -> page_content){
            // Add css
            if($this -> _styleSheets && count($this -> _styleSheets)){
                $css_html   = array();
                foreach($this -> _styleSheets as $path => $attr){
                    $_attr      = null;
                    if($attr['attribs'] && is_array($attr['attribs'])){
                        $_attr  = join(' ',$attr['attribs']);
                    }
                    $css_html[] = '<link rel="stylesheet" href="'.$path.'" type="'.$attr['mime'].'" '.$_attr.'/>';
                }
                $css_html   = join("\n",$css_html);

                if(preg_match_all('/<link.*?rel=[\"|\']stylesheet[\"|\'].*?href=[\"|\'].*?[\"|\'].*?\/>/',$page_content,$matches)){
                    $matches    = $matches[0];
                    $i  = 0;
                    $page_content   = preg_replace_callback('/<link.*?rel=[\"|\']stylesheet[\"|\'].*?href=[\"|\'].*?[\"|\'].*?\/>/',function($m) use(&$i,$css_html,$matches){
                        $i++;
                        if($i == count($matches) - 1){
                            return $m[0]."\n".$css_html;
                        }
                        return $m[0];
                    },$page_content);
                    $this -> page_content   = $page_content;
                }
            }

            // Add script file
            if($this -> _scripts && count($this -> _scripts)){
                $scripts_html   = array();
                foreach($this -> _scripts as $src => $attr){
                    $scripts_html[] = '<script src="'.$src.'" type="'.$attr['mime'].'"'
                        .((isset($attr['defer']) && $attr['defer'])?' defer="true"':'')
                        .((isset($attr['async']) && $attr['async'])?' async="true"':'').'></script>';
                }
                $scripts_html   = join("\n",$scripts_html);
                if(preg_match_all('/<script.*?src=[\"|\'].*?[\"|\'].*?\><\/script>/',$page_content,$scripts)){
                    $scripts    = $scripts[0];
                    $i  = 0;
                    $page_content   = preg_replace_callback('/<script.*?src=[\"|\'].*?[\"|\'].*?\><\/script>/',function($m) use(&$i,$scripts_html,$scripts){
                        $i++;
                        if($i == count($scripts) - 1){
                            return $m[0]."\n".$scripts_html;
                        }
                        return $m[0];
                    },$page_content);
                    $this -> page_content   = $page_content;
                }
            }

            // Add script code
            if($this -> _script && count($this -> _script)){
                $script_html   = array();
                foreach($this -> _script as $type => $content){
                    $script_html[] = '<script type="'.$type.'">'.$content.'</script>';
                }
                $script_html   = join("\n",$script_html);
                if(preg_match_all('/<\/head>/s',$page_content,$script)){
                    $script    = $script[0];
                    $i  = 0;
                    $page_content   = preg_replace_callback('/<\/head>/s',function($m) use(&$i,$script_html,$script){

                        $i++;
                        if(($i == count($script) - 1) || (count($script) == 1)){
                            return $script_html."\n".$m[0];
                        }
                        return $m[0];
                    },$page_content);
                    $this -> page_content   = $page_content;
                }
            }

            // Add custom script code
            if($this -> _custom && count($this -> _custom)){
                $custom_html   = array();
                foreach($this -> _custom as $type => $content){
                    $custom_html[] = $content;
                }
                $custom_html   = join("\n",$custom_html);
                if(preg_match_all('/<\/head>/s',$page_content,$custom)){
                    $custom    = $custom[0];
                    $i  = 0;
                    $page_content   = preg_replace_callback('/<\/head>/s',function($m) use(&$i,$custom_html,$custom){

                        $i++;
                        if(($i == count($custom) - 1) || (count($custom) == 1)){
                            return $custom_html."\n".$m[0];
                        }
                        return $m[0];
                    },$page_content);
                    $this -> page_content   = $page_content;
                }
            }
        }
        return $this -> page_content;
    }
}