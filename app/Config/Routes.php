<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/data', 'Usuarioscontroller::data');
$routes->get('/test', 'Home::test');
$routes->get('/getUsuarios', 'Usuarioscontroller::index');
$routes->get('/loginAPI', 'Usuarioscontroller::firstlogin');
$routes->get('/getUserInmuebles','Usuarioscontroller::getUserInmuebles');
$routes->get('/getUserData/(:any)','Usuarioscontroller::getUserData/$1');
$routes->get('/getUserPagos/(:any)','Usuarioscontroller::getUserPagos/$1');
$routes->get('/geTorreByProyecto/(:any)','Usuarioscontroller::geTorreByProyecto/$1');
$routes->get('/geTiposByTorre/(:any)/(:any)','Usuarioscontroller::geTiposByTorre/$1/$2');
$routes->get('/getDisponiblesCotizador/(:any)/(:any)/(:any)','Usuarioscontroller::getDisponiblesCotizador/$1/$2/$3');
$routes->get('/getPisosDisponiblesByTorreProyecto/(:any)/(:any)','Usuarioscontroller::getPisosDisponiblesByTorreProyecto/$1/$2');
$routes->get('/cotizar/(:any)/(:any)/(:any)','Usuarioscontroller::cotizar/$1/$2/$3');
$routes->get('/registrar/(:any)/(:any)/(:any)/(:any)','Usuarioscontroller::registrar/$1/$2/$3/$4');
$routes->get('/registrarVisita/(:any)/(:any)/(:any)','Usuarioscontroller::registrarVisita/$1/$2/$3');
$routes->get('/savePass/(:any)/(:any)','Usuarioscontroller::savePass/$1/$2');
$routes->get('/getPass/(:any)','Usuarioscontroller::getPass/$1');
$routes->get('/checkPass/(:any)/(:any)','Usuarioscontroller::checkPass/$1/$2');
$routes->get('/getNoticias','Usuarioscontroller::getNoticias');
$routes->get('/getBonus','Usuarioscontroller::getBonus');
$routes->get('/getObra/(:any)','Usuarioscontroller::getObra/$1');
$routes->get('/getGaleria/(:any)','Usuarioscontroller::getGaleria/$1');
$routes->post('/sendEmail','Usuarioscontroller::sendEmail');
$routes->get('/BorrarDatosPropietario/(:any)','Usuarioscontroller::BorrarDatosPropietario/$1');
$routes->get('/BorrarDatosVisitante/(:any)','Usuarioscontroller::BorrarDatosVisitante/$1');



//$routes->get('/sendEmail/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)','Usuarioscontroller::sendEmail/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10');




/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
$routes->get('/client', 'Client::test');

$routes->get('/test', 'Home::test');

$routes->get('/getUsuarios', 'Usuarioscontroller::index');
    //$routes->resource('employee');

}
