<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class GW_Form_validation extends CI_Form_validation
{
    protected $_field_data = array();



    public function clear_fields()
    {
        $this->_field_data = array();
    }

    public function in($val, $params)
    {
        $params = explode(',', $params);

        if(!in_array($val, $params))
        {
            $this->set_message(__FUNCTION__, 'Выбранное значение для поля <b>%s</b> не верно.');
            return FALSE;
        }

        return TRUE;
    }
}