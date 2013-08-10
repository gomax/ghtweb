<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends Controllers_Frontend_Base
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('news_model');
    }
    
    
	public function index()
	{
        $limit = (int) $this->config->item('news_per_page');

        $data_db_where = array(
            'allow' => 1,
        );
        
        $this->load->library('pagination');
        
        Pagination::initialize(array(
            'base_url'   => '/news/',
            'total_rows' => $this->news_model->get_count($data_db_where),
            'per_page'   => $limit,
        ));

        $view_data = array(
            'pagination' => Pagination::create_links(),
            'content'    => $this->news_model->get_list(Pagination::$offset, $limit, $data_db_where, 'created_at', 'DESC'),
        );

        $this->view_data['content'] = $this->load->view('news', $view_data, TRUE);
	}
    
    public function detail($news_id)
    {
        if(!($content = $this->cache->get('news/' . $news_id)))
        {
            $data_db_where = array(
                'id'    => $news_id,
                'allow' => 1,
            );
            
            $content = $this->news_model->get_row($data_db_where);
            
            if($content)
            {
                $this->cache->save('news/' . $news_id, $content);
            }
        }

        if($content)
        {
            // Meta
            $this->set_meta_title($content['seo_title']);
            $this->set_meta_keywords($content['seo_keywords']);
            $this->set_meta_description($content['seo_description']);
        }

        $view_data = array(
            'content' => $content,
        );

        $this->view_data['content'] = $this->load->view('news_detail', $view_data, TRUE);
    }
}