<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Transactions_model extends Crud
{
    public $table = 'transactions';
    
    private $_fields = array('payment_type', 'char_name', 'char_id', 'item_count', 'status', 'server_id', 'sum');


    public function get_fields()
    {
        return $this->_fields;
    }
}