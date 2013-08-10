<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Accounts extends Controllers_Backend_Base
{
    /**
     * @var array: Названия полей которые будут браться из $_GET для поиска
     */
    private $_search_fields = array('login');



	public function index()
	{
        $login_id = (int) $this->uri->segment(3);

        if(!isset($this->l2_settings['logins'][$login_id]))
        {
            $login_id = key($this->l2_settings['logins']);
        }


        if($this->l2_settings['logins'])
        {
            $limit = (int) $this->config->item('accounts_per_page', 'backend');


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

            $this->lineage
                ->set_id($login_id)
                ->set_type('logins');


            $count = $this->lineage->get_count_accounts(NULL, $data_db_like);

            // Пагинация
            $this->load->library('pagination');

            Pagination::initialize(array(
                'base_url'   => '/backend/accounts/' . $login_id . '/',
                'total_rows' => $count,
                'per_page'   => $limit,
            ));

            $pagination = Pagination::create_links();
            $offset     = Pagination::$offset;
            $content    = $this->lineage->get_accounts($limit, $offset, NULL, 'login', NULL, $data_db_like);
        }
        else
        {
            $message = Message::info('Сервер не найден.<br /> Нажмите <a href="/backend/servers/add/">сюда</a> чтобы создать первый сервер.');
        }


        $view_data = array(
            'server_list' => $this->l2_settings,
            'login_id'    => $login_id,
            'pagination'  => isset($pagination) ? $pagination : '',
            'content'     => isset($content) ? $content : '',
            'count'       => isset($count) ? $count : '',
            'offset'      => isset($offset) ? $offset : '',
            'message'     => isset($message) ? $message : '',
            'servers'     => $this->get_servers_by_login($login_id),
        );

        $this->view_data['content'] = $this->load->view('accounts/index', $view_data, TRUE);
    }
}