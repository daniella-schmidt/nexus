<?php
// Associação: O arquivo index.php associa o Router com os Controllers através da definição de rotas.
// Justificativa: Permite desacoplar o roteamento da lógica de negócio, facilitando manutenção e extensibilidade.

require_once 'app/core/Router.php';
require_once 'app/controllers/HomeController.php';
require_once 'app/controllers/AuthController.php';
require_once 'app/controllers/DashboardController.php';
require_once 'app/controllers/StudentController.php';
require_once 'app/controllers/VehicleController.php';
require_once 'app/controllers/RouteController.php';
require_once 'app/controllers/UserController.php';
require_once 'app/controllers/DriverController.php';

session_start();

// Associação: Instanciação do Router para gerenciar rotas.
// Justificativa: Separa responsabilidades, permitindo que o Router cuide apenas do roteamento sem conhecer detalhes dos controllers.
$router = new Router();

// ==================== ROTAS PÚBLICAS ====================
$router->addRoute('GET', '/', 'HomeController@about');
$router->addRoute('GET', '/home/about', 'HomeController@about');

// ==================== AUTENTICAÇÃO ====================
$router->addRoute('GET', '/login', 'AuthController@login');
$router->addRoute('POST', '/login', 'AuthController@login');
$router->addRoute('GET', '/register', 'AuthController@register');
$router->addRoute('POST', '/register', 'AuthController@register');
$router->addRoute('GET', '/logout', 'AuthController@logout');

// ==================== DASHBOARD ====================
$router->addRoute('GET', '/dashboard', 'DashboardController@index');

// ==================== ESTUDANTE ====================
$router->addRoute('GET', '/student/reservations', 'StudentController@reservations');
$router->addRoute('POST', '/student/reservations', 'StudentController@reservations');
$router->addRoute('POST', '/student/cancel-reservation', 'StudentController@cancelReservation');
$router->addRoute('GET', '/student/profile', 'StudentController@profile');
$router->addRoute('GET', '/student/digital-card', 'StudentController@digitalCard');

// ==================== ROTAS DO GESTOR ====================
$router->addRoute('GET', '/manager/vehicles', 'VehicleController@index');
$router->addRoute('GET', '/manager/vehicles/create', 'VehicleController@create');
$router->addRoute('POST', '/manager/vehicles', 'VehicleController@store');
$router->addRoute('GET', '/manager/vehicles/edit/{id}', 'VehicleController@edit');
$router->addRoute('POST', '/manager/vehicles/update/{id}', 'VehicleController@update');
$router->addRoute('POST', '/manager/vehicles/delete/{id}', 'VehicleController@delete');

$router->addRoute('GET', '/manager/routes', 'RouteController@index');
$router->addRoute('GET', '/manager/routes/create', 'RouteController@create');
$router->addRoute('POST', '/manager/routes', 'RouteController@store');
$router->addRoute('GET', '/manager/routes/edit/{id}', 'RouteController@edit');
$router->addRoute('POST', '/manager/routes/update/{id}', 'RouteController@update');
$router->addRoute('POST', '/manager/routes/delete/{id}', 'RouteController@delete');

$router->addRoute('GET', '/manager/users', 'UserController@index');
$router->addRoute('GET', '/manager/users/create', 'UserController@create');
$router->addRoute('POST', '/manager/users', 'UserController@store');
$router->addRoute('GET', '/manager/users/edit/{id}', 'UserController@edit');
$router->addRoute('POST', '/manager/users/update/{id}', 'UserController@update');
$router->addRoute('POST', '/manager/users/delete/{id}', 'UserController@delete');

// ==================== ROTAS DO MOTORISTA ====================
$router->addRoute('GET', '/driver/scan/checkin', 'DriverController@checkin');
$router->addRoute('POST', '/driver/scan/checkin', 'DriverController@checkin');
$router->addRoute('GET', '/driver/definition/vehicle', 'DriverController@selectVehicle');
$router->addRoute('POST', '/driver/definition/vehicle', 'DriverController@selectVehicle');
$router->addRoute('GET', '/driver/passengers', 'DriverController@passengersByRoute');
$router->addRoute('GET', '/driver/definition/routes', 'DriverController@manageRoutes');
$router->addRoute('POST', '/driver/definition/routes', 'DriverController@manageRoutes');
$router->addRoute('GET', '/driver/reports/attendance-report', 'DriverController@attendanceReport');
$router->addRoute('GET', '/driver/communication', 'DriverController@communication');
$router->addRoute('GET', '/driver/definition/profile', 'DriverController@profile');
$router->addRoute('POST', '/driver/definition/profile', 'DriverController@profile');
$router->addRoute('GET', '/driver/definition/requests', 'DriverController@requests');
$router->addRoute('POST', '/driver/definition/requests', 'DriverController@requests');
$router->addRoute('GET', '/driver/reports/import', 'DriverController@importReservations');
$router->addRoute('POST', '/driver/reports/import', 'DriverController@importReservations');
$router->addRoute('GET', '/driver/definition/return-routes', 'DriverController@setReturnRoute');
$router->addRoute('POST', '/driver/definition/return-routes', 'DriverController@setReturnRoute');
$router->addRoute('GET', '/driver/reports/export', 'DriverController@exportReports');

// ==================== ROTAS DO ESTUDANTES ====================
$router->addRoute('GET', '/student/definition/reservations', 'StudentController@reservations');
$router->addRoute('POST', '/student/definition/reservations', 'StudentController@reservations');
$router->addRoute('POST', '/student/definition/cancel-reservation', 'StudentController@cancelReservation');
$router->addRoute('GET', '/student/definition/profile', 'StudentController@profile');
$router->addRoute('POST', '/student/definition/profile', 'StudentController@profile');
$router->addRoute('GET', '/student/card/digital-card', 'StudentController@digitalCard');
$router->addRoute('GET', '/student/definition/return-routes', 'StudentController@returnRoutes');

// ==================== ROTA PADRÃO ====================
// Associação: Chamada do método dispatch do Router para processar a requisição.
// Justificativa: Delega o processamento da rota para a classe especializada, mantendo separação de responsabilidades.
$router->dispatch();
?>
