<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Gallery_model extends Crud
{
    public $table = 'gallery';
    
    private $_fields = array('img', 'allow');


    public function get_fields()
    {
        return (isset($this->_fields) ? $this->_fields : false);
    }
}