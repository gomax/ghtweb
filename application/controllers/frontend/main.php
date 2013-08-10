<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Main extends Controllers_Frontend_Base
{
	public function index()
	{
        if($this->config->item('home_page_type') == 'page' && $this->config->item('home_page_name') != '')
        {
            $this->get_page();
        }
        else
        {
            $this->get_news();
        }
	}
    
    /**
     * Формирует главную страницу на основе статической страницы
     */
    private function get_page()
    {
        $page_name  = $this->config->item('home_page_name');
        $cache_name = 'pages/' . $page_name;

        if(!($content = $this->cache->get($cache_name)))
        {
            $this->load->model('pages_model');

            $data_db_where = array(
                'page'  => $page_name,
                'allow' => 1,
            );
            
            $content = $this->pages_model->get_row($data_db_where);

            if($content)
            {
                $this->cache->save($cache_name, $content);
            }
        }

        $view_data = array(
            'content' => $content
        );
        
        $this->view_data['content'] = $this->load->view('page', $view_data, TRUE);
    }
    
    /**
     * Формирует главную страницу на основе новостей
     */
    private function get_news()
    {
        $this->load->model('news_model');

        $limit = (int) $this->config->item('news_per_page');
        
        $data_db_where = array(
            'allow' => 1,
        );
        
        $this->load->library('pagination');
        
        $this->pagination->initialize(array(
            'base_url'   => '/',
            'total_rows' => $this->news_model->get_count($data_db_where),
            'per_page'   => $limit,
        ));
        

        $view_data = array(
            'pagination' => $this->pagination->create_links(),
            'content'    => $this->news_model->get_list(Pagination::$offset, $limit, $data_db_where, 'created_at', 'DESC'),
        );

        $this->view_data['content'] = $this->load->view('news', $view_data, TRUE);
    }
}