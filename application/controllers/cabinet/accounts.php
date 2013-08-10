<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Accounts extends Controllers_Cabinet_Base
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('accounts_model');
    }
    
    
    
    public function index()
    {
        $user_id    = $this->auth->get('user_id');
        $cache_name = 'cabinet/accounts_' . $user_id;

        if(!($data = $this->cache->get($cache_name)))
        {
            $data = array();

            $user_accounts = $this->accounts_model->get_list(0, 0, array(
                'user_id' => $user_id
            ));

            foreach($user_accounts as $account)
            {
                // Если логин выключен
                if(!$this->l2_settings['logins'][$account['login_id']]['allow'])
                {
                    continue;
                }

                $data[$account['login_id']]['accounts'][] = $account['account'];
            }

            foreach($data as $login_id => $row)
            {
                $data[$login_id]['name']    = $this->l2_settings['logins'][$login_id]['name'];
                $data[$login_id]['servers'] = $this->get_servers_by_login($login_id, FALSE);

                // Список аккаунтов с сервера
                $data[$login_id]['accounts'] = $this->lineage
                    ->set_id($login_id)
                    ->set_type('logins')
                    ->get_accounts_by_login($data[$login_id]['accounts']);
            }

            if($data)
            {
                $this->cache->save($cache_name, $data, $this->config->item('cache_game_accounts_time') * 60);
            }
        }


        // Meta
        $this->set_meta_title('Игровые аккаунты');

        $view_data = array(
            'message'  => isset($message) ? $message : '',
            'accounts' => $data,
        );

        $this->view_data['content'] = $this->load->view('cabinet/accounts', $view_data, TRUE);
    }
}