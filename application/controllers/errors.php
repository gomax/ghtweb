<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Errors extends Controllers_Frontend_Base
{
    public function e404()
    {
        $this->view_data['content'] = $this->load->view('errors/e404', NULL, TRUE);
    }
}