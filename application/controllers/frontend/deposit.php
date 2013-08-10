<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deposit extends Controllers_Frontend_Base
{
    /**
     * Список серверов где донат включён
     * @var array
     */
    private $_servers_donat_enable = array();

    /**
     * Включённые платёжные системы
     * @var array
     */
    private $_payment_system_enable = array();

    private $_meta_title = 'Пожертвования';



    public function __construct()
    {
        parent::__construct();

        // Список серверов где включён донат
        foreach($this->l2_settings['servers'] as $server)
        {
            if((int) $server['donat_allow'])
            {
                $this->_servers_donat_enable[$server['id']] = $server['name'];
            }
        }

        // Если нет серверов со включенным донатом
        if(!$this->_servers_donat_enable && $this->uri->segment(2) != 'disabled')
        {
            redirect('deposit/disabled');
        }

        $this->config->load('deposit');

        foreach($this->config->item('payment_systems') as $k => $v)
        {
            if($this->config->item($k . '_allow'))
            {
                $this->_payment_system_enable[$k] = $v;
            }
        }

        // Если не включена ни одна платёжная система
        if(!$this->_payment_system_enable && $this->uri->segment(2) != 'disabled')
        {
            redirect('deposit/disabled');
        }


        $this->config->load('deposit');
        $this->load->driver('Deposit_lib');
        $this->load->model('transactions_model');
    }


	public function index()
	{
        $server_id = (int) $this->uri->segment(2);


        if($server_id > 0 && isset($this->_servers_donat_enable[$server_id]))
        {
            $item_name = $this->l2_settings['servers'][$this->uri->segment(2)]['donat_item_name'];

            if(!empty($_POST))
            {
                $this->load->library('form_validation');

                $this->form_validation->set_error_delimiters('', '<br />');

                $this->form_validation->set_rules('count_items', 'Кол-во ' . $item_name, 'trim|required|is_natural_no_zero');
                $this->form_validation->set_rules('char_name', 'Имя персонажа', 'trim|required|callback__check_char_name_exists');
                $this->form_validation->set_rules('payment_system', ' Платёжная система', 'trim|required|callback__check_payment_system');

                if($this->form_validation->run())
                {
                    $data_db = array(
                        'payment_system' => $_POST['payment_system'],
                        'char_name'      => $_POST['char_name'],
                        'char_id'        => $this->character['char_id'],
                        'item_count'     => (int) $_POST['count_items'],
                        'server_id'      => $server_id,
                        'sum'            => (int) $_POST['count_items'] * $this->l2_settings['servers'][$server_id]['donat_item_cost'],
                        'item_id'        => $this->l2_settings['servers'][$server_id]['donat_item_id'],
                    );

                    if($transaction_id = $this->transactions_model->add($data_db))
                    {
                        $this->session->set_userdata('transaction_id', $transaction_id);
                        redirect('deposit/processed');
                    }
                }

                if(validation_errors())
                {
                    $message = Message::false(validation_errors());
                }
            }

            $view_data = array(
                'item_name'      => $item_name,
                'payment_system' => $this->_payment_system_enable,
                'meta_title'     => $this->_meta_title . ' на сервере: ' . $this->l2_settings['servers'][$server_id]['name'],
            );

            $content = $this->load->view('deposit/step2', $view_data, TRUE);
        }
        else
        {
            $view_data = array(
                'servers'    => $this->_servers_donat_enable,
                'meta_title' => $this->_meta_title,
            );

            $content = $this->load->view('deposit/step1', $view_data, TRUE);
        }

        $this->set_meta_title($this->_meta_title);

        $view_data = array(
            'content' => isset($content) ? $content : '',
            'message' => isset($message) ? $message : '',
        );

        $this->view_data['content'] = $this->load->view('deposit/index', $view_data, TRUE);
	}

    public function disabled()
    {
        if($this->_payment_system_enable && $this->_servers_donat_enable)
        {
            redirect('deposit');
        }

        $this->view_data['content'] = $this->load->view('deposit/disabled', NULL, TRUE);
    }

    public function processed()
    {
        $transaction_id = (int) $this->session->userdata('transaction_id');

        $data_db_where = array(
            'id' => $transaction_id,
        );

        $transaction_data = $this->transactions_model->get_row($data_db_where);

        if(isset($transaction_data['status']) && $transaction_data['status'] == 0)
        {
            $server = $this->l2_settings['servers'][$transaction_data['server_id']];

            $ps = $this->deposit_lib->{$transaction_data['payment_system']};

            $view_data = array(
                'transaction_id' => $transaction_id,
                'payment_system' => $this->config->item($transaction_data['payment_system'], 'payment_systems'),
                'item_name'      => $server['donat_item_name'],
                'item_count'     => $transaction_data['item_count'],
                'char_name'      => $transaction_data['char_name'],
                'server_name'    => $this->l2_settings['servers'][$transaction_data['server_id']]['name'],
                'sum'            => $transaction_data['sum'],
                'action'         => $ps->get_form_url(),
                'form_fields'    => $ps->get_form_fields($transaction_data),
            );

            $view_data = array(
                'message'    => isset($message) ? $message : '',
                'content'    => $this->load->view('deposit/step3', $view_data, TRUE),
                'meta_title' => $this->_meta_title,
            );

            $this->view_data['content'] = $this->load->view('deposit/index', $view_data, TRUE);
        }
        else
        {
            $this->session->unset_userdata('transaction_id');
            redirect('deposit');
        }
    }

    public function _check_char_name_exists($char_name)
    {
        $server_id = (int) $this->uri->segment(2);

        $this->character = $this->lineage
            ->set_id($server_id)
            ->set_type('servers')
            ->get_character_by_char_name($char_name);

        if(!$this->character)
        {
            $this->form_validation->set_message('_check_char_name_exists', 'Персонаж не найден');
            return FALSE;
        }

        if($this->character['online'] > 0)
        {
            $this->form_validation->set_message('_check_char_name_exists', 'Персонаж в игре');
            return FALSE;
        }

        return TRUE;
    }

    public function _check_payment_system($payment)
    {
        if(!$this->config->item($payment, 'payment_systems'))
        {
            $this->form_validation->set_message('_check_payment_system', 'Выберите платёжную систему');
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Обработка транзакции и получение юзером шмотки
     */
    public function result()
    {
        $payment_type = 'robokassa';

        if(isset($_POST['wSignature']))
        {
            $payment_type = 'waytopay';
        }

        $payment_system = $this->deposit_lib->$payment_type;

        // Проверка подписи
        if($transaction_id = $payment_system->check_signature($_POST, 2))
        {
            $data_db_where = array(
                'id'     => $transaction_id,
            );

            $data = $this->transactions_model->get_row($data_db_where);

            if(!$data)
            {
                // Транзакция не найдена
                echo $payment_system->get_error();
                log_file("Транзакция не найдена\ntransaction_id: " . $transaction_id, $payment_type);
            }
            elseif($data['status'] == 1)
            {
                // Транзакция уже обработана
                echo $payment_system->get_error();
                log_file("Транзакция уже обработана\ntransaction_id: " . $transaction_id, $payment_type);
            }
            elseif($data['payment_system'] != $payment_type)
            {
                // Платёжные системы не совпадают
                echo $payment_system->get_error();
                log_file("Платёжные системы не совпадают\n" . $data['payment_system'] . " != " . $payment_type, $payment_type);
            }
            else
            {
                $this->lineage
                    ->set_id($data['server_id'])
                    ->set_type('servers');

                // Проверяю персонажа
                $char_data = $this->lineage->get_character_by_char_name($data['char_name']);

                if(!$char_data)
                {
                    // Персонаж не найден
                    echo $payment_system->get_error();
                    log_file("Персонаж не найден\ntransaction_id: " . $transaction_id, $payment_type);
                }
                elseif($char_data['online'] == 1)
                {
                    // Персонаж в игре
                    echo $payment_system->get_error();
                    log_file("Персонаж в игре\ntransaction_id: " . $transaction_id, $payment_type);
                }
                else
                {
                    $insert = FALSE;
                    $error  = FALSE;

                    // Кидаю предмет в игру
                    if($this->l2_settings['servers'][$data['server_id']]['donat_item_type'])
                    {
                        // Stock
                        $item_data = $this->lineage->get_character_items(1, 0, array(
                            'item_id'  => $data['item_id'],
                            'owner_id' => $char_data['char_id'],
                        ));

                        if($item_data)
                        {
                            if(!$this->lineage->edit_item($item_data['object_id'], $data['item_count'] + $item_data['count'], $char_data['char_id']))
                            {
                                echo $payment_system->get_error();
                                log_file("Не удалось отредактировать предмет\ntransaction_id: " . $transaction_id, $payment_type);
                                $error = TRUE;
                            }
                        }
                        else
                        {
                            $insert = TRUE;
                        }
                    }
                    else
                    {
                        $insert = TRUE;
                    }


                    if($insert)
                    {
                        // No stock
                        if(!$this->lineage->insert_item($data['item_id'], $data['item_count'], $char_data['char_id']))
                        {
                            $error = TRUE;
                            echo $payment_system->get_error();
                            log_file('Не удалось добавить предмет в игру', $payment_type);
                        }
                    }

                    if(!$error)
                    {
                        $data_db = array(
                            'status' => 1,
                        );

                        $this->transactions_model->edit($data_db, $data_db_where, 1);

                        echo $payment_system->get_success() . $transaction_id;
                    }
                }
            }
        }
        else
        {
            // Подпись не правильная
            echo $payment_system->get_error();
            log_file('Подпись не правильная', $payment_type);
        }

        die;
    }

    /**
     * Результат оплаты
     *
     * @param string $status
     */
    public function sf_result($status)
    {
        $this->session->unset_userdata('transaction_id');

        $message = Message::true('Оплата прошла успешно');

        if($status == 'fail')
        {
            $message = Message::false('Оплата не прошла');
        }

        $view_data = array(
            'message'    => $message,
            'meta_title' => $this->_meta_title,
            'content'    => '',
        );

        $this->view_data['content'] = $this->load->view('deposit/index', $view_data, TRUE);
    }
}