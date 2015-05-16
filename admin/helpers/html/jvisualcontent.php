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

abstract class JHtmlJVisualContent
{

    public static function addStyleSheet($url=null,$main=true){
        $doc    = JFactory::getDocument();
        if($main){
            $doc -> addStyleSheet(JUri::root(true).'/administrator/components/com_jvisualcontent/css/jvisualcontent-admin.min.css');
        }
    }

    public static function getHtmlStyleSheet($url=null,$bootstrap = true,$main=true){

    }

    public static function guidV4() {
        return sprintf('%04x%04x-%04x',

            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public static function jsaddslashes($s)
    {
        $o="";
        $l=strlen($s);
        for($i=0;$i<$l;$i++)
        {
            $c=$s[$i];
            switch($c)
            {
                case '<': $o.='\\x3C'; break;
                case '>': $o.='\\x3E'; break;
                case '\'': $o.='\\\''; break;
                case '\\': $o.='\\\\'; break;
                case '"':  $o.='\\"'; break;
                case "\n": $o.='\\n'; break;
                case "\r": $o.='\\r'; break;
                default:
                    $o.=$c;
            }
        }
        return $o;
    }

    public static function jsstripslashes($s)
    {
        $o="";
        $l=strlen($s);
        for($i=0;$i<$l;$i++)
        {
            $c=$s[$i];
            switch($c)
            {
                case '\\x3C': $o.='<'; break;
                case '\\x3E': $o.='>'; break;
                case '\\\'': $o.='\''; break;
                case '\\\\': $o.='\\'; break;
                case '\\"':  $o.='"'; break;
                case "\\n": $o.='\n'; break;
                case "\\r": $o.='\r'; break;
                default:
                    $o.=$c;
            }
        }
        return $o;
    }
}