<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class News_model extends Crud
{
    public $table = 'news';
    
    private $_fields = array(
        'title', 'description', 'text', 'seo_title', 'seo_keywords',
        'seo_description', 'allow', 'author');


    public function get_fields()
    {
        return (isset($this->_fields) ? $this->_fields : false);
    }
}