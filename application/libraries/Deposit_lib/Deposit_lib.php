<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deposit_lib extends CI_Driver_Library
{
    public $valid_drivers = array();
    private $_CI;



    public function __construct()
    {
        $this->_CI =& get_instance();

        $this->_CI->config->load('deposit');

        foreach($this->_CI->config->item('payment_systems') as $key => $payment)
        {
            $this->valid_drivers[] = 'Deposit_lib_' . $key;
        }
    }
}