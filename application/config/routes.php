<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == "OPTIONS") {
    http_response_code(200);
    exit();
}
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'fronted/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['abouts'] = 'Welcome/demo';

$route['wfwefwe'] = 'PageController/index';
$route['about'] = 'PageController/aboutUs';

//$route['blog/(:any)'] = 'PageController/blog/$1';
//$route['blog/(:num)'] = 'PageController/blog/$1';

$route['welcome'] = 'PageController/demo';

// Employee Routes

//$route['employee'] = 'PageController/employee';
$route['employee'] = 'Frontend/EmployeeController/index';
$route['employee/add'] = 'Frontend/EmployeeController/create';
$route['employee/store'] = 'Frontend/EmployeeController/store';
$route['employee/create'] = 'Frontend/EmployeeController/create';
$route['employee/edit/(:any)'] = 'Frontend/EmployeeController/edit/$1';
$route['employee/update/(:any)'] = 'Frontend/EmployeeController/update/$1';
$route['employee/delete/(:any)'] = 'Frontend/EmployeeController/delete/$1';
$route['employee/remove/(:any)'] = 'Frontend/EmployeeController/remove/$1';
$route['employee/confirmdelete/(:any)']['DELETE'] = 'Frontend/EmployeeController/remove/$1';
$route['login'] = 'Frontend/EmployeeController/login';
$route['employee/loginAuth'] = 'Frontend/EmployeeController/loginAuth';
$route['employee/logout'] = 'Frontend/EmployeeController/logout';
$route['blog'] = 'BlogController/index';
$route['blog/create'] = 'BlogController/create';
$route['blog/store'] = 'BlogController/store';  // Handles form submission
$route['blog/blog_request'] = 'BlogController/blogRequest';
$route['blog/handle_request'] = 'BlogController/handleRequest';
$route['blog/delete_request/(:num)'] = 'BlogController/deleteRequest/$1';
$route['blog/update_request/(:num)'] = 'BlogController/updateRequest/$1';
//$route['blog/(:num)'] = 'blogs/show/$1';  // View a specific blog post
$route['blog/edit/(:num)'] = 'BlogController/edit/$1';  // Edit a blog post
$route['blog/update/(:num)'] = 'BlogController/update/$1';  // Update blog post
$route['blog/delete/(:num)'] = 'BlogController/delete/$1';  // Delete a blog post
$route['blog/session'] = 'BlogController/checkSession';
$route['blog/profile'] = 'BlogController/profile';
$route['blog/liveSearch'] = 'BlogController/liveSearch';
$route['blog/blog_individual/(:any)'] = 'BlogController/blogIndividual/$1';
$route['blog/blog_individual_admin/(:any)'] = 'BlogController/blogIndividualAdmin/$1';
$route['blog/blog_my_posts/(:any)'] = 'BlogController/blogMyPosts/$1';
$route['blog/blog_search'] = 'BlogController/blogSearch';
$route['blog/worldcup/(:any)'] = 'BlogController/worldcup/$1';
$route['blog/blog_admin/(:any)'] = 'BlogController/blogAdmin/$1';
$route['home'] = 'HomeController/index';
$route['rewards'] = 'HomeController/rewards';
// Tareas
$route['task_manager'] = 'TaskController/index';
$route['tasks/create'] = 'TaskController/create';
$route['tasks/store'] = 'TaskController/store';
$route['tasks/edit/(:num)'] = 'TaskController/edit/$1';
$route['tasks/update/(:num)'] = 'TaskController/update/$1';
$route['tasks/advance/(:num)'] = 'TaskController/advance/$1';
$route['tasks/delete/(:num)'] = 'TaskController/delete/$1';
$route['tasks/status/(:num)/(:any)'] = 'TaskController/changeStatus/$1/$2';

// FTP Files
$route['ftp_center'] = 'FileTransferController/index';
$route['ftp/upload'] = 'FileTransferController/uploadFile';
$route['ftp/delete/(:num)'] = 'FileTransferController/deleteFile/$1';
$route['ftp/download/(:num)'] = 'FileTransferController/download/$1';

// SMTP
$route['smtp'] = 'SmtpController/index';
$route['smtp/send'] = 'SmtpController/send';

// Location
$route['location/save'] = 'LocationController/save';
$route['location'] = 'LocationController/view';

$route['blog/toggle_like_admin'] = 'BlogController/toggle_like_admin';
$route['champions/timeline'] = 'ChampionsController/timeline';
$route['api/champions'] = 'api/champions';
$route['api/external/teams'] = 'ExternalApi/teams';
$route['api/external/matches'] = 'ExternalApi/matches';
$route['api/external/champions'] = 'ExternalApi/champions';

$route['mundiales/create'] = 'MundialesAdmin/create';
$route['mundiales/store'] = 'MundialesAdmin/store';
$route['mundiales/(:num)'] = 'MundialesAdmin/view/$1';

$route['blog/add_comment'] = 'BlogController/addComment';

$route['api/create_chat'] = 'ChatController/create_chat';
$route['api/save_message'] = 'ChatController/save_message';
$route['api/messages/(:num)'] = 'ChatController/get_messages/$1';
$route['api/user_chats/(:num)'] = 'ChatController/get_user_chats/$1';

$route['api/users'] = 'Api/users';
