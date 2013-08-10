<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Auth
{
    private $_CI;
    
    /**
     * @var array: Массив с данными о пользователе
     */
    private $_user_data = array();

    /**
     * @var integer: Время через которое юзер будет считаться оффлайн
     */
    private $_time_for_user_offline = 15;
    
    /**
     * @var integer: Время на сколько ставить куки, в днях
     */
    private $_cookie_time = 7;
    
    
    
    public function __construct()
    {
        $this->_CI =& get_instance();
        $this->_CI->load->helper('cookie');
        $this->_CI->load->library('encrypt');
        $this->_CI->load->model('crud');
        $this->_CI->load->model('users_model');

        // Дефолтные значения
        $this->_user_data['is_logged'] = FALSE;
        
        
        $cookie_data = $this->get_cookie();

        if((isset($cookie_data['cookie_hash']) && strlen($cookie_data['cookie_hash']) == 32) && (isset($cookie_data['user_id']) && (int) $cookie_data['user_id'] > 0))
        {
            $data_db_where = array(
                'user_id'     => (int) $cookie_data['user_id'],
                'cookie_hash' => (string) $cookie_data['cookie_hash'],
            );

            $query = $this->_CI->users_model->get_row($data_db_where);
            
            if($query)
            {
                $this->_user_data = $query;
                $this->_user_data['is_logged'] = TRUE;

                if(strtotime($this->_user_data['last_access']) < (time() - ($this->_time_for_user_offline * 60)))
                {
                    $data_db = array(
                        'last_access' => db_date(),
                    );

                    $this->_CI->db->update('users', $data_db, $data_db_where, 1);
                }
            }
        }
    }
    
    public function set(array $data, $save_db = FALSE)
    {
        foreach($data as $key => $val)
        {
           $this->_user_data[$key] = $val; 
        }
        
        if($save_db)
        {
            $data_db_where = array(
                'user_id' => $this->get('user_id'),
            );

            return $this->_CI->users_model->edit($data, $data_db_where);
        }
        
        return TRUE;
    }
    
    public function get($key, $default = FALSE)
    {
        return (isset($this->_user_data[$key]) ? $this->_user_data[$key] : $default);
    }
    
    public function get_all()
    {
        return $this->_user_data;
    }
    
    private function get_cookie()
    {
        $cookie_name         = $this->get_cookie_name();
        $data                = get_cookie($cookie_name);
        $data                = $this->_CI->encrypt->decode($data);
        $data                = json_decode($data, TRUE);
        //$data['last_access'] = get_cookie('last_access');

        return $data;
    }
    
    public function is_logged()
    {
        return $this->_user_data['is_logged'];
    }
    
    /**
     * Сверяет совпадения паролей
     * 
     * @param string $new_password: Пароль без шифрования
     * @param string $old_password: Зашифрованный пароль
     * 
     * @return boolean
     */
    public function check_passwords($new_password, $old_password)
    {
        return ($this->password_encript($new_password) == $old_password);
    }
    
    /**
     * Шифрует пароль
     * 
     * @param string $password
     * 
     * @return string
     */
    public function password_encript($password)
    {
        //return sha1(md5($password) . config_item('encryption_key'));
        return md5(sha1($password));
    }
    
    /**
     * Авторизовывание пользователя
     * 
     * @param integer $user_id: ID пользователя который будет авторизован
     * 
     * @return boolean
     */
    public function auth($user_id)
    {
        if(!is_numeric($user_id)) return;

        $ip = $this->_CI->input->ip_address();
        
        $this->_CI->load->helper('string');
        
        $cookie_hash = md5($ip . random_string('alnum', 40));

        $cookie_name  = $this->get_cookie_name();

        $cookie_value = json_encode(array(
            'user_id'     => $user_id,
            'cookie_hash' => $cookie_hash,
        ));

        $cookie_value = $this->_CI->encrypt->encode($cookie_value);

        set_cookie($cookie_name, $cookie_value, $this->_cookie_time * 86400);
        //set_cookie('last_access', time(), $this->_cookie_time * 86400);


        // Логирую вход в Мастер аккаунт
        $this->_CI->load->model('users_login_logs_model');

        $this->_CI->users_login_logs_model->add(array(
            'user_id'    => $user_id,
            'ip'         => $ip,
            'user_agent' => $this->_CI->input->user_agent(),
            'status'     => 1,
        ));

        return $this->_CI->users_model->edit(array(
            'cookie_hash' => $cookie_hash,
            'last_access' => db_date(),
        ), array(
            'user_id' => $user_id,
        ));
    }
    
    /**
     * Выход
     */
    public function logout()
    {
        $this->_user_data = array(
            'is_logged' => FALSE,
        );
        
        $cookie_data = $this->get_cookie();

        if(count($cookie_data) == 3)
        {
            $data_db = array(
                'cookie_hash' => NULL,
            );

            $data_db_where = array(
                'user_id'     => (int) $cookie_data['user_id'],
                'cookie_hash' => (string) $cookie_data['cookie_hash'],
            );

            $this->_CI->users_model->edit($data_db, $data_db_where);
        }

        $cookie_name = $this->get_cookie_name();

        delete_cookie($cookie_name);
        //delete_cookie('last_access');
    }

    private function get_cookie_name()
    {
        $host = $this->_CI->input->server('HTTP_HOST');
        $host = md5($host . config_item('encryption_key'));
        return substr($host, 0, 16);
    }
}