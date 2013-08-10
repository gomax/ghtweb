<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// Профайлер
$config['enable_profiler'] = FALSE;

// Кэш
$config['cache_allow'] = TRUE;

// Время кэщирования для списка аккаунтов, в минутах (cabinet/accounts)
$config['cache_game_accounts_time'] = 5;

// Время кэщирования для списка персонажей на аккаунте, в минутах (cabinet/characters)
$config['cache_characters_time'] = 5;




// Папка где хранятся картинки для галереи
$config['gallery_path'] = 'uploads/gallery';

// Время ключа при смене данных в профиле (В минутах)
$config['profile_change_time'] = 10;