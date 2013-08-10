<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class GW_Config extends CI_Config
{
	public function set_item($item, $value = '')
	{
        if(is_array($item))
        {
            $this->config = array_merge($this->config, $item);
        }
        else
        {
            $this->config[$item] = $value;
        }
	}

    function load($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
    {
        $file = ($file == '') ? 'config' : str_replace('.php', '', $file);

        foreach($this->_config_paths as $path)
        {
            foreach(array($file, ENVIRONMENT . '/' . $file) as $location)
            {
                $file_path = $path.'config/'.$location.'.php';

                if(in_array($file_path, $this->is_loaded, TRUE))
                {
                    //$loaded = TRUE;
                    continue 2;
                }

                if(!file_exists($file_path))
                {
                    continue;
                }

                include($file_path);

                if(!isset($config) || !is_array($config))
                {
                    if($fail_gracefully === TRUE)
                    {
                        return FALSE;
                    }

                    show_error('Your '.$file_path.' file does not appear to contain a valid configuration array.');
                }

                if($use_sections === TRUE)
                {
                    if(isset($this->config[$file]))
                    {
                        $this->config[$file] = array_merge($this->config[$file], $config);
                    }
                    else
                    {
                        $this->config[$file] = $config;
                    }
                }
                else
                {
                    $this->config = array_merge($this->config, $config);
                }

                $this->is_loaded[] = $file_path;
                unset($config);

                log_message('debug', 'Config file loaded: '.$file_path);
            }
        }

        return TRUE;
    }
}