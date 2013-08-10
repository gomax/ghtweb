<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class GW_Loader extends CI_Loader
{
    /**
     * Устанавливает путь до папки с VIEW
     * 
     * @param string $path 
     */
    public function set_view_path($path)
    {
        $this->_ci_view_paths = array('templates/' . $path . '/' => true);
    }
}