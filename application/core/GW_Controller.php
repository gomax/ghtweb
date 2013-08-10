<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class GW_Controller extends CI_Controller
{
    public $view_data   = array(
        'message' => '',
    );

    public $view_layout = 'layouts/site';

    
    /**
     * Глобальный массив для AJAX запросов
     * @var array
     */
    public $_ajax_data = array(
        'status'   => 'fail', // (fail|success)
        'message'  => '',
        'redirect' => '',
    );

    /**
     * Глобальный массив настроек для серверов и логинов
     * @var array
     */
    public $l2_settings = array(
        'servers' => array(),
        'logins'  => array(),
    );



    public function __construct()
    {
        parent::__construct();

        // Достаю настройки
        $settings = $this->settings_model->get_settings_list();
        $this->config->set_item($settings);
        unset($settings);

        // Recaptcha
        $this->load->library('recaptcha');

        if($this->config->item('migration_enabled') === TRUE)
        {
            $this->load->library('Migration');

            // Update DB
            if($this->migration->get_db_version() < $this->migration->get_fs_version())
            {
                $this->migration->latest();
                // $this->migration->version(0);
            }
        }




        if(!defined('TPL'))
        {
            define('TPL', base_url() . 'templates/' . $this->config->item('template') . '/');
        }

        // Сервера
        $this->l2_settings['servers'] = $this->servers_model->get_all();

        // Логины
        $this->l2_settings['logins'] = $this->logins_model->get_all();

        // Группы пользователей
        //$this->_user_groups = $this->user_groups_model->get_groups_list();

        // Конфиг Lineage
        $this->config->load('lineage', true);
        
        // Драйвер для Lineage
        $this->load->driver('Lineage');

        // Profiler
        if($this->config->item('enable_profiler') && !$this->input->is_ajax_request())
        {
            $this->output->enable_profiler(TRUE);
        }
    }



    /** CALLBACK **/

    /**
     * Возвращает список включённых серверов
     *
     * @return array
     */
    public function get_enabled_servers()
    {
        $return  = array();
        $servers = $this->l2_settings['servers'];

        foreach($servers as $server)
        {
            if($server['allow'] == 1)
            {
                $return[$server['id']] = $server;
            }
        }

        return $return;
    }

    /**
     * Возвращает список включённых логинов
     *
     * @return array
     */
    public function get_enabled_logins()
    {
        $return = array();
        $logins = $this->l2_settings['logins'];

        foreach($logins as $login)
        {
            if($login['allow'] == 1)
            {
                $return[$login['id']] = $login;
            }
        }

        return $return;
    }

    /**
     * Возвращает включённые сервера
     *
     * server_id => server_name
     *
     * @return array
     */
    public function get_servers_for_select()
    {
        $servers = $this->get_enabled_servers();
        $return  = array();

        foreach($servers as $server)
        {
            $return[$server['id']] = $server['name'];
        }

        return $return;
    }

    /**
     * Возвращает включённые логины
     *
     * login_id => login_name
     *
     * @return array
     */
    public function get_logins_for_select()
    {
        $logins = $this->get_enabled_logins();
        $return = array();

        foreach($logins as $login)
        {
            $return[$login['id']] = $login['name'];
        }

        return $return;
    }

    public function get_all_logins_for_select()
    {
        $logins = $this->l2_settings['logins'];
        $return = array();

        foreach($logins as $login)
        {
            $return[$login['id']] = $login['name'];
        }

        return $return;
    }

    public function get_all_servers_for_select()
    {
        $servers = $this->l2_settings['servers'];
        $return = array();

        foreach($servers as $server)
        {
            $return[$server['id']] = $server['name'];
        }

        return $return;
    }

    /**
     * Проверка логина (users)
     * 
     * Используется при регистрации и в админке, создание пользователя
     * 
     * @return boolean
     */
    public function _check_user_login()
    {
        $data_db_where = array(
            'login' => $this->input->post('login', true),
        );
        
        if($this->users_model->get_row($data_db_where))
        {
            $this->form_validation->set_message('_check_user_login', 'Логин уже занят');
            return false;
        }
        
        return true;
    }

    /**
     * Проверка Email
     * 
     * Используется при регистрации и в админке, создание пользователя
     *
     * @param string $value
     *
     * @return boolean
     */
    public function _check_user_email($value)
    {
        // Если в конфиге отключена проверка по EMAIL
        if(!$this->config->item('one_account_per_email'))
        {
            return TRUE;
        }
        
        $data_db_where = array(
            'email' => $value,
        );
        
        if($this->users_model->get_row($data_db_where))
        {
            $this->form_validation->set_message('_check_user_email', 'На этот Email уже зарегистрирован аккаунт');
            return FALSE;
        }
    }
    
    /**
     * Проверка группы пользователя
     *
     * @param string $value
     *
     * @return boolean 
     */
    public function _check_user_group($value)
    {
        $groups = $this->user_groups_model->get_groups_names();
        
        if(!isset($groups[$value]))
        {
            $this->form_validation->set_message('_check_user_group', 'Группа пользователя не правильная');
            return false;
        }
        
        return true;
    }
    
    /**
     * Проверка капчи
     * 
     * @return boolean
     */
    public function _check_captcha()
    {
        if(!$this->captcha->check_captcha($this->input->post('captcha'), $this->input->post('captcha_id')))
        {
            $this->form_validation->set_message('_check_captcha', 'Код с картинки введен не верно');
            return false;
        }
        
        return true;
    }

    /**
     * Проверка валидности IP адреса(ов)
     *
     * @param string $ips (Список IP адресов через запятую)
     *
     * @return bool
     */
    public function _check_valid_ip($ips)
    {
        // Если IP не введены
        if($ips == '')
        {
            return TRUE;
        }


        $ips = explode(',', $ips);

        foreach($ips as $ip)
        {
            if(filter_var(trim($ip), FILTER_VALIDATE_IP) === FALSE)
            {
                $this->form_validation->set_message('_check_valid_ip', 'Один из IP адресов введён не верно');
                return FALSE;
            }
        }

        return TRUE;
    }

    /**
     * Возвращает массив серверов которые прицеплены к логину
     *
     * @param integer $login_id
     * @param boolean $allowed
     *
     * @return array
     */
    protected  function get_servers_by_login($login_id, $allowed = TRUE)
    {
        $return = array();

        if($allowed)
        {
            $servers = $this->get_enabled_servers();
        }
        else
        {
            $servers = $this->l2_settings['servers'];
        }

        foreach($servers as $server)
        {
            if($server['login_id'] == $login_id)
            {
                $return[$server['id']] = $server['name'];
            }
        }

        return $return;
    }

    public function set_meta_title($str)
    {
        if($str == '')
        {
            return;
        }

        $this->view_data['meta_title'] .= $this->config->item('meta_title_separator') . $str;
    }

    public function set_meta_keywords($str)
    {
        if($str == '')
        {
            return;
        }

        $this->view_data['meta_keywords'] .= $this->config->item('meta_title_separator') . $str;
    }

    public function set_meta_description($str)
    {
        if($str == '')
        {
            return;
        }

        $this->view_data['meta_description'] .= $this->config->item('meta_title_separator') . $str;
    }

    public function _output($output)
    {
        // $class  = $this->router->fetch_class();
        // $method = $this->router->fetch_method();

        echo $this->load->view($this->view_layout, $this->view_data, TRUE) . $output .
            "\n\n<!-- " . strrev('BEWTHG') . " v" . VERSION . " -->";
    }
}