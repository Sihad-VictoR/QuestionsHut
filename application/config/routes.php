<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'HomeController';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'AuthController/loadLogin';
$route['signup'] = 'AuthController/loadSignUp';
$route['registerUser'] = 'AuthController/registerUser';
$route['isUserLoggedIn'] = 'AuthController/isUserLoggedIn';
$route['authenticateUser'] = 'AuthController/authenticateUser';
$route['logout'] = 'AuthController/logout';
$route['askQuestion'] = 'HomeController/askQuestion';
$route['categoryQuestion'] = 'HomeController/sortQuestionsCategory';
$route['question/(:num)'] = 'HomeController/viewQuestion/$1';
$route['category/(:num)'] = 'HomeController/sortQuestionsCategory/$1';
$route['search/(:any)'] = 'HomeController/searchQuestion/$test';
$route['users'] = 'HomeController/loadUsers';
$route['profile'] = 'HomeController/loadUserView';
$route['forgotPassword'] = 'HomeController/loadForgotPasswordView';
$route['changePassword/(:num)'] = 'AuthController/changePassword/$2';