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

$route['default_controller'] 	= "home";
$route['404_override'] 			= 'nietgevonden';


// Website

$route['gratis-workshop'] 		                    = "workshops/gratis_workshop/";
$route['workshops/(:any)'] 						    = "workshops/detail/$1";
$route['kennismakingsworkshops/(:any)'] 		    = "kennismakingsworkshops/detail/$1";
$route['aanmelden/intake/(:any)'] 				    = "aanmelden/index/intake/$1";
$route['aanmelden/auditie/(:any)'] 				    = "aanmelden/index/auditie/$1";
$route['aanmelden/stemtest/(:any)']                 = "aanmelden/index/stemtest/$1";
$route['aanmelden/workshop/(:any)/(:any)']          = "aanmelden/index/workshop/$1/$2";
$route['aanmelden/workshop/(:any)'] 			    = "aanmelden/index/workshop/$1";
$route['aanmelden/kennismakingsworkshop/(:any)']    = "aanmelden/index/kennismakingsworkshop/$1";
$route['aanmelden/bevestigen'] 					    = "aanmelden/bevestigen";
$route['aanmelden/producten'] 					    = "aanmelden/producten";
$route['aanmelden/annuleringsverzekering']          = "aanmelden/annuleringsverzekering";
$route['aanmelden/aangemeld/(:any)/(:any)'] 	    = "aanmelden/aangemeld/$1/$2";
$route['aanmelden/aangemeld/(:any)/(:any)/(:any)'] 	= "aanmelden/aangemeld/$1/$2/$3";
$route['nieuws/pagina/(:num)'] 					    = "nieuws/pagina/$1";
$route['nieuws/(:any)'] 						    = "nieuws/artikel/$1";
$route['reacties/pagina/(:num)'] 				    = "reacties/pagina/$1";
$route['reacties/(:any)'] 						    = "reacties/reactie/$1";
$route['blog/pagina/(:num)'] 				        = "blog/pagina/$1";
$route['blog/(:any)'] 						        = "blog/blog_info/$1";
$route['over-ons'] 								    = "over_ons";
$route['visie'] 								    = "visie";


// Cursistenmodule

$route['cursistenmodule'] 										= "cursistenmodule/dashboard";
$route['cursistenmodule/workshop'] 								= "cursistenmodule/dashboard/workshop";
$route['cursistenmodule/workshop/(:num)'] 						= "cursistenmodule/dashboard/workshop/$1";
$route['cursistenmodule/workshop/(:num)/(:num)'] 				= "cursistenmodule/dashboard/workshop/$1/$2";
$route['cursistenmodule/lessen/(:num)'] 						= "cursistenmodule/lessen/les/$1";
$route['cursistenmodule/lessen/workshop'] 						= "cursistenmodule/lessen/workshop";
$route['cursistenmodule/lessen/workshop/(:num)'] 				= "cursistenmodule/lessen/workshop/$1";
$route['cursistenmodule/berichten/(:num)'] 						= "cursistenmodule/berichten/bericht/$1";
$route['cursistenmodule/aanmelden/intake/(:any)'] 				= "cursistenmodule/aanmelden/index/intake/$1";
$route['cursistenmodule/aanmelden/auditie/(:any)'] 				= "cursistenmodule/aanmelden/index/auditie/$1";
$route['cursistenmodule/aanmelden/stemtest/(:any)']            	= "cursistenmodule/aanmelden/index/stemtest/$1";
$route['cursistenmodule/aanmelden/workshop/(:any)'] 			= "cursistenmodule/aanmelden/index/workshop/$1";
$route['cursistenmodule/aanmelden/bevestigen'] 					= "cursistenmodule/aanmelden/bevestigen";
$route['cursistenmodule/aanmelden/producten'] 					= "cursistenmodule/aanmelden/producten";
$route['cursistenmodule/aanmelden/aangemeld/(:any)/(:any)'] 	= "cursistenmodule/aanmelden/aangemeld/$1/$2";

// Extra opdrachten module

$route['opdrachten'] 										    = "opdrachten/dashboard";
$route['opdracht/(:any)'] 						                = "opdrachten/opdrachten/opdracht/$1";
$route['opdrachten/opdrachten/(:num)'] 						    = "opdrachten/opdrachten/$1";
$route['opdrachten/inzendingen/(:num)'] 						= "opdrachten/inzendingen/$1";
$route['opdrachten/media/(:num)'] 				                = "opdrachten/media/detail/$1";

// CMS

$route['cms'] 							= "cms/dashboard";
$route['cms/aanmeldingen/(:num)'] 		= "cms/aanmeldingen/detail/$1";
$route['cms/afspraken/(:num)'] 			= "cms/afspraken/detail/$1";
$route['cms/bestellingen/(:num)'] 		= "cms/bestellingen/detail/$1";
$route['cms/workshops/(:num)'] 			= "cms/workshops/detail/$1";
$route['cms/lessen/(:num)'] 			= "cms/lessen/detail/$1";
$route['cms/lessen/workshop/(:num)'] 	= "cms/lessen/workshops/$1";
$route['cms/groepen/(:num)'] 			= "cms/groepen/detail/$1";
$route['cms/producten/(:num)'] 			= "cms/producten/detail/$1";
$route['cms/deelnemers/(:num)'] 		= "cms/deelnemers/detail/$1";
$route['cms/beheerders/(:num)'] 		= "cms/beheerders/detail/$1";
$route['cms/docenten/(:num)'] 			= "cms/docenten/detail/$1";
$route['cms/notities/(:num)'] 			= "cms/notities/detail/$1";
$route['cms/paginas/(:num)'] 			= "cms/paginas/detail/$1";
$route['cms/gegevens/(:num)'] 			= "cms/gegevens/detail/$1";
$route['cms/nieuws/(:num)'] 			= "cms/nieuws/detail/$1";
$route['cms/reacties/(:num)'] 			= "cms/reacties/detail/$1";
$route['cms/media/(:num)'] 				= "cms/media/detail/$1";
$route['cms/vragen/(:num)'] 			= "cms/vragen/detail/$1";
$route['cms/berichten/(:num)'] 			= "cms/berichten/bericht/$1";
$route['cms/uitnodigingen/(:num)'] 		= "cms/uitnodigingen/detail/$1";
$route['cms/beoordelingen/(:num)'] 		= "cms/beoordelingen/detail/$1";
$route['cms/kortingscodes/(:num)'] 		= "cms/kortingscodes/detail/$1";
$route['cms/annuleringen/(:num)'] 		= "cms/annuleringen/";
$route['cms/blog/(:num)'] 			    = "cms/blog/detail/$1";
$route['cms/edit-review/(:any)']		= "cms/huiswerk/edit_review/$1";
$route['cms/rollen'] 			    	= "cms/aanmeldingen/user_role";
/* End of file routes.php */
/* Location: ./application/config/routes.php */
