<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class GW_Form_validation extends CI_Form_validation
{
    protected $_field_data = array();



    public function clear_fields()
    {
        $this->_field_data = array();
    }

    public function check_recaptcha()
    {
        $this->CI->recaptcha->recaptcha_check_answer($this->CI->input->ip_address(), $this->CI->input->post('recaptcha_challenge_field'), $this->CI->input->post('recaptcha_response_field'));

        if(!$this->CI->recaptcha->getIsValid())
        {
            $this->set_message(__FUNCTION__, 'Код с картинки введен не верно');
            return FALSE;
        }

        return TRUE;
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