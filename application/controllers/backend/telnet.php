<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Telnet extends Controllers_Backend_Base
{
	public function index()
	{
        if(isset($_POST['submit']))
        {
            // Отправка команды
            if($this->l2_settings['servers'])
            {
                $server_id = key($this->l2_settings['servers']);

                if(count($this->l2_settings['servers']) > 1)
                {
                    $server_id = (int) $_POST['server_id'];
                }

                // Если настроек телнета у сервера нет то die
                $servers_config = $this->l2_settings['servers'][$server_id];

                $telnet_host = $servers_config['telnet_host'];
                $telnet_port = $servers_config['telnet_port'];
                $telnet_pass = $servers_config['telnet_pass'];

                if($telnet_host == '' || $telnet_port == '')
                {
                    $message = Message::false('Для выбранного сервера настройки для TELNET не установлены <br /><a href="/backend/servers/edit/' . $server_id . '/#telnet" target="_blank">перейти к настройкам сервера</a>');
                }
                elseif(!$this->input->post('command', true))
                {
                    $message = Message::false('Необходимо ввести команду');
                }
                else
                {
                    $command = iconv('utf-8', 'cp1251', $this->input->post('command', true));

                    $config = array(
                        'host' => $telnet_host,
                        'port' => $telnet_port,
                        'pass' => $telnet_pass,
                    );

                    $this->load->library('telnet_', $config);

                    if($this->telnet_->send_command($command))
                    {
                        $message = Message::true('Команда отправлена');
                    }
                    else
                    {
                        if($this->telnet_->get_errors())
                        {
                            $message = Message::false($this->telnet_->get_errors());
                        }
                        else
                        {
                            $message = Message::false('Неверная команда');
                        }
                    }
                }
            }
            else
            {
                $message = Message::info('Для управления нужно добавить хотябы один сервер');
            }
        }

        $view_data = array(
            'message'     => isset($message) ? $message : '',
            'server_list' => $this->get_all_servers_for_select(),
        );

        $this->view_data['content'] = $this->load->view('telnet/index', $view_data, TRUE);
    }
}