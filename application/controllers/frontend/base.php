<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Controllers_Frontend_Base extends GW_Controller
{
	public function __construct()
    {
        parent::__construct();
        
        $this->load->set_view_path($this->config->item('template'));

        $this->view_data['meta_title']       = $this->config->item('meta_title');
        $this->view_data['meta_keywords']    = $this->config->item('meta_title');
        $this->view_data['meta_description'] = $this->config->item('meta_title');

        // Страницы для меню
        // $this->view_data['page_in_menu'] = $this->pages_model->get_pages_in_menu();
    }
}