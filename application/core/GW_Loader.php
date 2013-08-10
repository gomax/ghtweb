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

    public function view($view, $vars = array(), $return = FALSE)
    {
        $view = str_replace('::', '/', strtolower($view));

        return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
    }
}