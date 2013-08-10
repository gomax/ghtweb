<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Main extends Controllers_Cabinet_Base
{
	public function index()
	{
        $user_id = $this->auth->get('user_id');

        $cache_name = 'cabinet/main_' . $user_id;

        if(!($data = $this->cache->get($cache_name)))
        {
            $this->load->model('accounts_model');

            $data = $this->accounts_model->get_count_accounts($user_id, $this->get_enabled_logins());

            $this->cache->save($cache_name, $data, 300);
        }

        if($this->session->flashdata('message'))
        {
            $message = $this->session->flashdata('message');
        }


        // Meta
        $this->set_meta_title('Мастер аккаунт');

        $view_data = array(
            'count_game_accounts' => $data,
            'message'             => isset($message) ? $message : '',
        );

        $this->view_data['content'] = $this->load->view('cabinet/main', $view_data, TRUE);
	}
}