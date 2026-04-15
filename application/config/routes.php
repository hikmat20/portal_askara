<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Portal';
$route['404_override']       = '';
$route['translate_uri_dashes'] = FALSE;

// Portal Routes
$route['apps/store']    = 'Portal/store';
$route['apps/update']   = 'Portal/update';
$route['apps/delete']   = 'Portal/delete';
$route['apps/toggle']   = 'Portal/toggle';
$route['apps/test']     = 'Portal/test';
$route['search_ajax']   = 'Portal/search_ajax';
$route['portal']        = 'Portal/index';
