<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Character extends Controllers_Backend_Base
{
    public function index()
	{
        $server_id = (int) $this->uri->segment(3);
        $char_id   = (int) $this->uri->segment(4);

        if(!isset($this->l2_settings['servers'][$server_id]))
        {
            $server_id = key($this->l2_settings['servers']);
        }


        if($this->l2_settings['servers'])
        {
            $content['content'] = array();

            $this->lineage
                ->set_id($server_id)
                ->set_type('servers');

            $count = $this->lineage->get_count_character_items($char_id);
            $limit = (int) $this->config->item('users_items_per_page', 'backend');


            // Пагинация
            $this->load->library('pagination');

            Pagination::initialize(array(
                'base_url'   => '/backend/character/' . $server_id . '/' . $char_id,
                'total_rows' => $count,
                'per_page'   => $limit,
            ));


            $pagination = Pagination::create_links();
            $offset     = Pagination::$offset;
            $content    = $this->lineage->get_character_items($limit, $offset, array('owner_id' => $char_id), 'count', 'DESC');
            $char_data  = $this->lineage->get_character_by_char_id($char_id);

            if($content)
            {
                $items_id = array();

                // Названия предметов
                foreach($content as $item)
                {
                    $items_id[] = $item['item_id'];
                }

                $this->load->model('all_items_model');
                $items_name_res = $this->all_items_model->get_list_where_in_by_id($items_id);
                $items_name     = array();

                foreach($items_name_res as $row)
                {
                    $items_name[$row['item_id']] = $row['name'];
                }

                foreach($content as $key => $item)
                {
                    if(isset($items_name[$item['item_id']]))
                    {
                        $content[$key]['item_name'] = $items_name[$item['item_id']];
                    }
                }

                unset($items_name_res, $items_name);
            }
        }
        else
        {
            $message = Message::info('Серверов нет');
        }


        $view_data = array(
            'server_list' => $this->l2_settings,
            'server_id'   => $server_id,
            'content'     => isset($content) ? $content : '',
            'pagination'  => isset($pagination) ? $pagination : '',
            'char_data'   => isset($char_data) ? $char_data : '',
            'count'       => isset($count) ? $count : 0,
            'message'     => isset($message) ? $message : '',
        );

        $this->view_data['content'] = $this->load->view('character/index', $view_data, TRUE);
    }

    public function delete_item($server_id, $item_obj_id)
    {
        if(isset($this->l2_settings['servers'][$server_id]))
        {
            $this->lineage
                ->set_id($server_id)
                ->set_type('servers')
                ->del_item($item_obj_id);

            $message = Message::true('Предмет удалён');
        }

        $this->session->set_flashdata('message', $message);
        redirect_back();
    }
}