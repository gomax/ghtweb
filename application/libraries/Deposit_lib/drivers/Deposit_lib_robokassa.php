<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deposit_lib_robokassa extends CI_Driver
{
    private $_CI;



    public function __construct()
    {
        $this->_CI =& get_instance();
    }



    public function get_form_url()
    {
        return $this->_CI->config->item('robokassa_form_url');
    }

    public function get_form_fields(array $data)
    {
        $login   = $this->_CI->config->item('robokassa_login');
        $desc    = $this->_CI->config->item('robokassa_description');
        $pass1   = $this->_CI->config->item('robokassa_password');
        $item_id = $data['item_id'];
        $lang    = 'ru';

        $signature = md5($login . ':' . $data['sum'] . ':' . $data['id'] . ':' . $pass1 . ':Shp_item=' . $item_id);

        $inputs = form_hidden('MrchLogin', $login) .
            form_hidden('OutSum', $data['sum']) .
            form_hidden('InvId', $data['id']) .
            form_hidden('Desc', $desc) .
            form_hidden('SignatureValue', $signature) .
            form_hidden('Shp_item', $item_id) .
            form_hidden('IncCurrLabel') .
            form_hidden('Culture', $lang);

        return $inputs;
    }

    public function check_signature(array $data, $pass_number = 1)
    {
        $pass_number = ($pass_number == 1 ? '' : $pass_number);

        if(!isset($data['OutSum']) || !isset($data['InvId']) || !isset($data['SignatureValue']) || !isset($data['Shp_item']))
        {
            return FALSE;
        }

        $pass   = $this->_CI->config->item('robokassa_password' . $pass_number);
        $crc    = strtoupper($data['SignatureValue']);
        $my_crc = strtoupper(md5($data['OutSum'] . ':' . $data['InvId'] . ':' . $pass . ':Shp_item=' . $data['Shp_item']));

        if($crc != $my_crc)
        {
            return FALSE;
        }

        return (int) $data['InvId'];
    }

    public function get_error()
    {
        return 'ERROR';
    }

    public function get_success()
    {
        return 'OK';
    }
}
