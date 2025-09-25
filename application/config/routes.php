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

// Default Routes
$route['default_controller'] = 'auth/login';
$route['404_override'] = 'errorhandler/not_found';
$route['translate_uri_dashes'] = FALSE;

// Authentication Routes
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['forgot-password'] = 'auth/forgot_password';
$route['reset-password/(:any)'] = 'auth/reset_password/$1';

// Dashboard Routes
$route['dashboard'] = 'dashboard/index';

// Company Management Routes
$route['company'] = 'company/index';
$route['company/form'] = 'company/form';
$route['company/form/(:num)'] = 'company/form/$1';
$route['company/delete/(:num)'] = 'company/delete/$1';
$route['company/ajax_save'] = 'company/ajax_save';

// Bank Management Routes
$route['bank'] = 'bank/index';
$route['bank/form'] = 'bank/form';
$route['bank/form/(:num)'] = 'bank/form/$1';
$route['bank/save'] = 'bank/save';
$route['bank/delete/(:num)'] = 'bank/delete/$1';

// Client Management Routes
$route['client'] = 'clients/index';
$route['client/create'] = 'clients/create';
$route['client/store'] = 'clients/store';
$route['client/edit/(:num)'] = 'clients/edit/$1';
$route['client/update'] = 'clients/update';
$route['client/delete/(:num)'] = 'clients/delete/$1';

// Contact Management Routes
$route['contacts'] = 'contacts/index';
$route['contacts/form'] = 'contacts/form';
$route['contacts/form/(:num)'] = 'contacts/form/$1';
$route['contacts/save'] = 'contacts/save';
$route['contacts/delete/(:num)'] = 'contacts/delete/$1';

// Mode Management Routes
$route['mode'] = 'mode/index';
$route['mode/form'] = 'mode/form';
$route['mode/form/(:num)'] = 'mode/form/$1';
$route['mode/save'] = 'mode/save';
$route['mode/delete/(:num)'] = 'mode/delete/$1';

// Category Management Routes
$route['category'] = 'category/index';
$route['category/form'] = 'category/form';
$route['category/form/(:num)'] = 'category/form/$1';
$route['category/save'] = 'category/save';
$route['category/delete/(:num)'] = 'category/delete/$1';

// Product Category Management Routes
$route['product-category'] = 'ProductCategory/index';
$route['product-category/form'] = 'ProductCategory/form';
$route['product-category/form/(:num)'] = 'ProductCategory/form/$1';
$route['product-category/ajax_save'] = 'ProductCategory/ajax_save';
$route['product-category/save'] = 'ProductCategory/save';
$route['product-category/delete/(:num)'] = 'ProductCategory/delete/$1';

// Product Service Management Routes
$route['product-service'] = 'ProductService/index';
$route['product-service/get_all'] = 'ProductService/get_all';
$route['product-service/ajax_save'] = 'ProductService/ajax_save';
$route['product-service/save'] = 'ProductService/save';
$route['product-service/edit/(:num)'] = 'ProductService/edit/$1';
$route['product-service/delete/(:num)'] = 'ProductService/delete/$1';

// Legacy routes for backward compatibility
$route['productservice'] = 'ProductService/index';
$route['productservice/get_all'] = 'ProductService/get_all';
$route['productservice/list'] = 'ProductService/list';
$route['productservice/ajax_save'] = 'ProductService/ajax_save';
$route['productservice/save'] = 'ProductService/save';
$route['productservice/edit/(:num)'] = 'ProductService/edit/$1';
$route['productservice/delete/(:num)'] = 'ProductService/delete/$1';

// Friendly dashed routes
$route['product-service/list'] = 'ProductService/list';

// User Management Routes
$route['user'] = 'user/index';
$route['user/list'] = 'user/list';
$route['user/get/(:num)'] = 'user/get/$1';
$route['user/ajax_save'] = 'user/ajax_save';
$route['user/save'] = 'user/save';
$route['user/delete/(:num)'] = 'user/delete/$1';

// Quotation Management Routes
$route['quotation'] = 'quotation/index';
$route['quotation/create'] = 'quotation/create';
$route['quotation/store'] = 'quotation/store';
$route['quotation/edit/(:num)'] = 'quotation/edit/$1';
$route['quotation/update/(:num)'] = 'quotation/update/$1';
$route['quotation/view/(:num)'] = 'quotation/view/$1';
$route['quotation/delete/(:num)'] = 'quotation/delete/$1';
$route['quotation/duplicate/(:num)'] = 'quotation/duplicate/$1';

// PDF Routes
$route['quotation/view_pdf/(:num)'] = 'quotation/view_pdf/$1';
$route['quotation/generate_pdf/(:num)'] = 'quotation/generate_pdf/$1';

// Mail Routes
$route['quotation/send_mail'] = 'quotation/send_mail';
$route['quotation/mail_logs/(:num)'] = 'quotation/mail_logs/$1';

// AJAX Helper Routes
$route['quotation/get_clients_by_company/(:num)'] = 'quotation/get_clients_by_company/$1';
$route['quotation/get_banks_by_company/(:num)'] = 'quotation/get_banks_by_company/$1';
$route['quotation/get_products_by_category/(:num)'] = 'quotation/get_products_by_category/$1';
$route['quotation/get_product_details/(:num)'] = 'quotation/get_product_details/$1';

// Analytics Routes
$route['analytics'] = 'analytics_dashboard/index';
$route['analytics/health'] = 'analytics_dashboard/health';
$route['analytics/performance'] = 'analytics_dashboard/performance';
$route['analytics/security'] = 'analytics_dashboard/security';

// Error Routes
$route['error/access-denied'] = 'errorhandler/access_denied';
$route['error/not-found'] = 'errorhandler/not_found';
$route['error/server-error'] = 'errorhandler/server_error';

// Settings Routes
$route['settings'] = 'settings/index';
$route['settings/setup'] = 'settings/setup';
$route['settings/add'] = 'settings/add';
$route['settings/edit/(:num)'] = 'settings/edit/$1';
$route['settings/delete/(:num)'] = 'settings/delete/$1';
$route['error/csrf-error'] = 'errorhandler/csrf_error';
$route['error/maintenance'] = 'errorhandler/maintenance';

// Tools / Utilities
$route['mail-test'] = 'MailTest/index';
$route['mail-test/send'] = 'MailTest/send';

// Test Routes (Development Only)
$route['test'] = 'test/all';
$route['test/security'] = 'test/security';
$route['test/database'] = 'test/database';
$route['test/cache'] = 'test/cache';
$route['test/upload'] = 'test/upload';
