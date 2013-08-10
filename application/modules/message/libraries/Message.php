<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Message
{
    public static function true($text, $array = array())
    {
        return self::generate($text, 'true', $array);
    }
    
    public static function false($text, $array = array())
    {
        return self::generate($text, 'false', $array);
    }
    
    public static function info($text, $array = array())
    {
        return self::generate($text, 'info', $array);
    }
    
    public static function alert($text, $array = array())
    {
        return self::generate($text, 'alert', $array);
    }
    
    private static function generate($text, $type, $array)
    {
        $CI =& get_instance();

        $text = (is_array($array) && count($array) > 0 ? strtr($text, $array) : $text);
        
        return $CI->load->view('messages/' . $type, array('text' => $text), TRUE);
    }
}