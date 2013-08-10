<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deposit_lib_waytopay extends CI_Driver
{
    private $_CI;



    public function __construct()
    {
        $this->_CI =& get_instance();
    }



    public function get_form_url()
    {
        return 'https://waytopay.org/merchant/index';
    }

    public function get_form_fields(array $data)
    {
        $services_id = $this->_CI->config->item('waytopay_services_id');
        $desc        = $this->_CI->config->item('waytopay_description');

        $inputs = form_hidden('MerchantId', $services_id) .
            form_hidden('OutSum', $data['sum']) .
            form_hidden('InvId', $data['id']) .
            form_hidden('InvDesc', $desc) .
            form_hidden('IncCurr', 1);

        return $inputs;
    }

    public function check_signature(array $data, $pass_number = 1)
    {
        if(!isset($data['wOutSum']) || !isset($data['wInvId']) || !isset($data['wIsTest']) || !isset($data['wSignature']))
        {
            return FALSE;
        }

        $services_id = $this->_CI->config->item('waytopay_services_id');
        $key         = $this->_CI->config->item('waytopay_key');

        $crc    = strtoupper($data['wSignature']);
        $my_crc = strtoupper(md5($services_id . ':' . (float) $data['wOutSum'] . ':' . (int) $data['wInvId'] . ':' . $key));

        if($crc != $my_crc)
        {
            return FALSE;
        }

        return (int) $data['wInvId'];
    }

    public function get_error()
    {
        return 'ERROR_';
    }

    public function get_success()
    {
        return 'OK_';
    }
}
