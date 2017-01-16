<?php
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
|	https://codeigniter.com/user_guide/general/routing.html
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
//$route['default_controller'] = 'c_login';
$route['default_controller'] = 'c_boss';
//my routes
$route['cart/actions']								= 'frontend/c_cart/action';
$route['cart/actions/(:num)']						= 'frontend/c_cart/action/$1';
$route['cart/clear']								= 'frontend/c_cart/clear';
$route['cart/applycoupon']							= 'frontend/c_cart/check_and_applycoupon';
$route['cart/removecoupon/(:any)']					= 'frontend/c_cart/delete_coupon/$1';
$route['checkout/cart/submit']						= 'frontend/c_cart/submit';
$route['checkout/success']							= 'c_boss/checkout_success';
$route['checkout/cart']								= 'c_boss/checkout';
$route['home'] 										= 'c_boss';
$route['shop'] 										= 'c_boss/shop/sets';
$route['shop/sets'] 								= 'c_boss/shop/sets';
$route['shop/sets/(:any)'] 							= 'c_boss/shop/sets/$1';
$route['shop/shopitem/(:any)']						= 'c_boss/shop_item/$1';
$route['events'] 									= 'c_boss/events';
$route['delivery'] 									= 'c_boss/delivery';
$route['profile']									= 'c_boss/clientprofile';
$route['profile/vieworder/(:num)']					= 'c_boss/view_order/$1';
$route['profile/(:any)']							= 'c_boss/clientprofile/$1';

$route['backend']									= 'c_login';
$route['backend/login/check'] 						= 'c_login/check';
$route['backend/userlogout'] 						= 'c_login/logout';
$route['backend/login'] 							= 'c_login';
$route['backend/dashboard'] 						= 'c_dashboard';
$route['backend/users']								= 'backend/c_users';
$route['backend/users/(:any)']						= 'backend/c_users/$1';
$route['backend/users/(:any)/(:any)']				= 'backend/c_users/$1/$2';
$route['backend/userprofile/(:num)'] 				= 'backend/c_users/profile/$1';
$route['backend/userprofile/(:num)/(:any)'] 		= 'backend/c_users/profile/$1/$2';
$route['backend/attrgroups']						= 'backend/c_attrgroups';
$route['backend/attrgroups/(:any)']					= 'backend/c_attrgroups/$1';
$route['backend/attrgroups/(:any)/(:any)']			= 'backend/c_attrgroups/$1/$2';
$route['backend/attributes']						= 'backend/c_attributes';
$route['backend/attributes/(:any)']					= 'backend/c_attributes/$1';
$route['backend/attributes/(:any)/(:any)']			= 'backend/c_attributes/$1/$2';
$route['backend/deserts']							= 'backend/c_deserts';
$route['backend/deserts/(:any)']					= 'backend/c_deserts/$1';
$route['backend/deserts/(:any)/(:any)']				= 'backend/c_deserts/$1/$2';
$route['backend/flavors']							= 'backend/c_flavors';
$route['backend/flavors/(:any)']					= 'backend/c_flavors/$1';
$route['backend/flavors/(:any)/(:any)']				= 'backend/c_flavors/$1/$2';
$route['backend/colors']							= 'backend/c_colors';
$route['backend/colors/(:any)']						= 'backend/c_colors/$1';
$route['backend/colors/(:any)/(:any)']				= 'backend/c_colors/$1/$2';
$route['backend/products']							= 'backend/c_products';
$route['backend/products/(:any)']					= 'backend/c_products/$1';
$route['backend/products/(:any)/(:any)']			= 'backend/c_products/$1/$2';
$route['backend/sets']								= 'backend/c_sets';
$route['backend/sets/getitems']						= 'backend/c_sets/get_items/$1';
$route['backend/sets/getfull']						= 'backend/c_sets/get_full/$1';
$route['backend/sets/getattributes']				= 'backend/c_sets/get_attributes/$1';
$route['backend/sets/createsetsbmt']				= 'backend/c_sets/sets';
$route['backend/sets/(:any)']						= 'backend/c_sets/$1';
$route['backend/sets/(:any)/(:any)']				= 'backend/c_sets/$1/$2';
$route['backend/setactions']						= 'backend/c_sets/set_actions';
$route['backend/setactions/(:any)']					= 'backend/c_sets/set_actions/$1';
$route['backend/setactions/(:any)/(:any)']			= 'backend/c_sets/set_actions/$1/$2';
$route['backend/coupons']							= 'backend/c_coupons';
$route['backend/coupons/(:any)']					= 'backend/c_coupons/$1';
$route['backend/coupons/(:any)/(:any)']				= 'backend/c_coupons/$1/$2';
$route['backend/clients']							= 'backend/c_clients';
$route['backend/clients/getitems']					= 'backend/c_clients/get_items/$1';
$route['backend/clients/getfull']					= 'backend/c_clients/get_full/$1';
$route['backend/clients/getattributes']				= 'backend/c_clients/get_attributes/$1';
$route['backend/clients/createclientsbmt']			= 'backend/c_clients/clients';
$route['backend/clients/(:any)']					= 'backend/c_clients/$1';
$route['backend/clients/(:any)/(:any)']				= 'backend/c_clients/$1/$2';
$route['backend/clientactions']						= 'backend/c_clients/client_actions';
$route['backend/clientactions/(:any)']				= 'backend/c_clients/client_actions/$1';
$route['backend/clientactions/(:any)/(:any)']		= 'backend/c_clients/client_actions/$1/$2';

$route['backend/orders']							= 'backend/c_orders';
$route['backend/orderdetails/(:num)']				= 'backend/c_orders/details/$1';
$route['backend/orders/save']						= 'backend/c_orders/change_order_status';
$route['backend/orders/history']					= 'backend/c_orders/get_history';
$route['backend/orders/(:any)']						= 'backend/c_orders/$1';

$route['backend/settings']							= 'backend/c_settings';
$route['backend/settings/save']						= 'backend/c_settings/save_settings';

$route['useregistration']							= 'frontend/c_fclients/register_index';
$route['useregistrationsubmit']						= 'frontend/c_fclients/register_submit';
$route['login']										= 'frontend/c_fclients/login';
$route['logincheck']								= 'frontend/c_fclients/validate_login';
$route['logout']									= 'frontend/c_fclients/logout';
$route['activateaccount']							= 'frontend/c_fclients/activate_account';
$route['cprofile/clactions']						= 'frontend/c_fclients/client_actions';
//$route['cprofile/clactions/(:num)']					= 'frontend/c_fclients/client_actions/$1';



/*$route['company'] 							= 'c_company';
$route['company_insert']					= 'c_company/insert';
$route['job_titles']						= 'c_positions';
$route['sectors']							= 'c_sectors';
$route['clients']							= 'c_clients';
$route['clientprofile/(:num)'] 		  		= 'c_clients/profile/$1';
$route['clientprofile/(:num)/(:any)'] 		= 'c_clients/profile/$1/$2';
$route['departments']						= 'c_departments';
$route['assignments']						= 'c_assignments';
$route['operations']						= 'c_operations';
$route['projects']							= 'c_projects';
$route['projectdetails/(:num)']		  		= 'c_projects/details/$1';
$route['projectdetails/(:num)/(:any)'] 		= 'c_projects/details/$1/$2';
$route['timesheets']						= 'c_timesheets';
//$route['tsactions']						= 'c_timesheets/timesheet_actions_index';
$route['tsactions/(:any)']					= 'c_timesheets/timesheet_actions_index/$1';
$route['tsactions/(:any)/(:any)']			= 'c_timesheets/timesheet_actions_index/$1/$2';
$route['tsactions/(:any)/(:any)/(:any)']	= 'c_timesheets/timesheet_actions_index/$1/$2/$3';
$route['pending_timesheets']				= 'c_timesheets/pending_timesheets_index';
$route['timesheetdetails']					= 'c_timesheets/timesheet_details_index';
$route['timesheetdetails/(:num)']			= 'c_timesheets/timesheet_details_index/$1';
$route['timesheetdetails/(:num)/(:any)'] 	= 'c_timesheets/timesheet_details_index/$1/$2';
$route['timesheetdetails/(:num)/(:any)/(:any)'] 	= 'c_timesheets/timesheet_details_index/$1/$2/$3';
$route['timesheetedit/(:num)/(:num)'] 		= 'c_timesheets/timesheet_edit_index/$1/$2';
$route['reports'] 							= 'c_reports';
*/
//->end of my routes

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
