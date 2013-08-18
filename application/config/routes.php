<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = 'frontend/main';
$route['404_override'] = 'errors/e404';



// Cabinet
$route['cabinet'] = 'cabinet/main';
$route['cabinet/(accounts|profile|logout)'] = 'cabinet/$1';
$route['cabinet/register-account'] = 'cabinet/register_account';
$route['cabinet/add-account'] = 'cabinet/add_account';
$route['cabinet/profile/change/(:any)'] = 'cabinet/profile/change/$1';
$route['cabinet/edit-account/(:num)/(:any)'] = 'cabinet/edit_account/index/$1/$2';


$route['cabinet/(characters)/(:num)/(:any)'] = 'cabinet/$1/index/$2/$3';
$route['cabinet/characters/teleport/(:num)/(:num)/(:any)'] = 'cabinet/characters/teleport/$1/$2/$3';
$route['cabinet/(:any)'] = 'cabinet/main/index';





// Frontend

// Статистика
$route['stats'] = 'frontend/stats';
$route['stats/(:num)/(:any)'] = 'frontend/stats';

// Статические страницы
$route['page/(:any)'] = 'frontend/page/index/$1';

// Новости
$route['news'] = 'frontend/news/index';
$route['news/detail/(:num)'] = 'frontend/news/detail/$1';

// Регистрация - активация по Email
$route['register/activation/(:any)'] = 'frontend/register/activation/$1';

// Авторизация, восстановление пароля, галерея, регистрация
$route['(login|gallery|register)'] = 'frontend/$1';

// Восстановление пароля
$route['forgotten-password'] = 'frontend/forgotten_password';
$route['forgotten-password/(:any)'] = 'frontend/forgotten_password/step2/$1';

// Депозит
$route['deposit'] = 'frontend/deposit';
$route['deposit/(success|fail)'] = 'frontend/deposit/sf_result/$1';
$route['deposit/(:num)'] = 'frontend/deposit/index';
$route['deposit/(:any)'] = 'frontend/deposit/$1';

// Статистика
$route['stats/'] = 'frontend/stats';
$route['stats/(:num)'] = 'frontend/stats';
$route['stats/(:num)/(:any)'] = 'frontend/stats';

// Ajax
$route['ajax/(:any)'] = 'frontend/ajax/$1';





// Backend
$route['backend'] = 'backend/main';
$route['backend/accounts/(:num)'] = 'backend/accounts';
$route['backend/characters/(:num)'] = 'backend/characters';
$route['backend/characters/(:num)/(:any)'] = 'backend/characters';
$route['backend/character/(:num)/(:any)'] = 'backend/character';
$route['backend/character/delete-item/(:num)/(:num)'] = 'backend/character/delete_item/$1/$2';

$route['backend/news'] = 'backend/news';

/* End of file routes.php */
/* Location: ./application/config/routes.php */