<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Page extends Controllers_Frontend_Base
{
	public function index($page_name)
	{
        if(!($content = $this->cache->get('pages/' . $page_name)))
        {
            $this->load->model('pages_model');

            $data_db_where = array(
                'page'  => $page_name,
                'allow' => 1,
            );

            $content = $this->pages_model->get_row($data_db_where);

            if($content)
            {
                $this->cache->save('pages/' . $page_name, $content);
            }
        }

        if($content)
        {
            // Meta
            $this->set_meta_title($content['seo_title']);
            $this->set_meta_keywords($content['seo_keywords']);
            $this->set_meta_description($content['seo_description']);
        }


        $this->view_data['content'] = $this->load->view('page', array('content' => $content), TRUE);
	}
}