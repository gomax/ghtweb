<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gallery extends Controllers_Frontend_Base
{
	public function index()
	{
        $this->load->model('gallery_model');

        $limit = (int) $this->config->item('gallery_per_page');
        
        $data_db_where = array(
            'allow' => 1,
        );
        
        $this->load->library('pagination');

        Pagination::initialize(array(
            'base_url'   => '/gallery/',
            'total_rows' => $this->gallery_model->get_count($data_db_where),
            'per_page'   => $limit,
        ));


        // Meta
        $this->set_meta_title('Галерея');


        $view_data = array(
            'pagination' => Pagination::create_links(),
            'content'    => $this->gallery_model->get_list(Pagination::$offset, $limit, $data_db_where, 'created_at', 'DESC'),
        );

        $this->view_data['content'] = $this->load->view('gallery', $view_data, TRUE);
	}
}