<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Characters extends Controllers_Backend_Base
{
    /**
     * @var array: Названия полей которые будут браться из $_GET для поиска
     */
    private $_search_fields = array('account_name', 'char_name');



	public function index()
	{
        $server_id = (int) $this->uri->segment(3);
        $login     = $this->uri->segment(4);

        if(!isset($this->l2_settings['servers'][$server_id]))
        {
            $server_id = key($this->l2_settings['servers']);
        }


        if($this->l2_settings['servers'])
        {
            $limit = (int) $this->config->item('characters_per_page', 'backend');


            $data_db_like = array();

            // Поиск
            if($this->input->get())
            {
                $get = $this->input->get();

                foreach($get as $key => $val)
                {
                    $val = trim($val);

                    if(in_array($key, $this->_search_fields) && $val != '')
                    {
                        $data_db_like[$key] = $val;
                    }
                }
            }

            $data_db_where = array();

            if($login)
            {
                $data_db_where['account_name'] = $login;
            }

            $this->lineage
                ->set_id($server_id)
                ->set_type('servers');

            $count = $this->lineage->get_count_characters($data_db_where, $data_db_like);

            // Пагинация
            $this->load->library('pagination');

            Pagination::initialize(array(
                'base_url'   => '/backend/characters/' . $server_id . '/' . ($login ? $login . '/' : ''),
                'total_rows' => $count,
                'per_page'   => $limit,
            ));

            $pagination = Pagination::create_links();
            $offset     = Pagination::$offset;
            $content    = $this->lineage->get_characters($limit, $offset, $data_db_where, 'level', 'DESC', $data_db_like);
        }
        else
        {
            $message = Message::info('Сервер не найден.<br /> Нажмите <a href="/backend/servers/add/">сюда</a> чтобы создать первый сервер.');
        }


        $view_data = array(
            'server_list' => $this->l2_settings,
            'server_id'   => $server_id,
            'pagination'  => isset($pagination) ? $pagination : '',
            'content'     => isset($content) ? $content : '',
            'count'       => isset($count) ? $count : '',
            'offset'      => isset($offset) ? $offset : '',
            'message'     => isset($message) ? $message : '',
        );

        $this->view_data['content'] = $this->load->view('characters/index', $view_data, TRUE);
    }
}