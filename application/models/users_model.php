<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Users_model extends Crud
{
    public $table = 'users';
    
    private $_fields = array('password', 'email', 'protected_ip', 'group');


    public function get_fields()
    {
        return (isset($this->_fields) ? $this->_fields : false);
    }
    
    /**
     * Поиск пользователя по login или email
     * 
     * @param string $login
     * @param string $email
     *
     * @return array
     */
    public function get_user_by_login_or_email($login, $email)
    {
        $this->db->where('login', $login);
        $this->db->or_where('email', $email);
        
        return $this->db->get($this->table, 1)->row_array();
    }

    /**
     * Возвращает все данные о пользователю по его ID
     * Данные пользователя
     * Данные об его аккаунтах
     * Название серверов
     *
     * @param integer $user_id
     *
     * @return array
     */
    /*public function get_user_info_by_id($user_id)
    {
        $data = array();

        // Инфо о юзере
        $data['user_info'] = $this->db
            ->where('user_id', $user_id)
            ->get('users', 1)
            ->row_array();



        return $data;
    }*/
}