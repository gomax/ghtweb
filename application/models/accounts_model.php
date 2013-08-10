<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Accounts_model extends Crud
{
    public $table = 'accounts';


    /**
     * Возвращает кол-во игровых аккаунтов для включенных логин серверов
     *
     * @param integer $user_id
     * @param array $logins
     *
     * @return integer
     */
    public function get_count_accounts($user_id, array $logins)
    {
        if(!$logins)
        {
            return 0;
        }

        $lids = array();

        foreach($logins as $login)
        {
            $lids[] = $login['id'];
        }

        $this->db->where_in('login_id', $lids);
        $this->db->where('user_id', $user_id);
        return $this->db->count_all_results($this->table);
    }
}
