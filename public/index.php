<?php

require_once __DIR__ . '/../includes/bootstrap.php';

$router = new AppRouter();
$router->get('/', [HomeController::class , 'index']);
$router->get('/login', [AuthController::class , 'showLogin']);
$router->post('/login', [AuthController::class , 'login']);
$router->get('/signup', [AuthController::class , 'showSignup']);
$router->post('/signup', [AuthController::class , 'signup']);
$router->post('/logout', [AuthController::class , 'logout']);
$router->get('/dashboard', [DashboardController::class , 'index']);
$router->get('/my-links', [DashboardController::class , 'index']);
$router->get('/dashboard/link/{alias}/edit', [DashboardController::class , 'edit']);
$router->post('/dashboard/link/{alias}/update', [DashboardController::class , 'update']);
$router->post('/dashboard/link/{alias}/delete', [DashboardController::class , 'delete']);
$router->post('/shorten', [LinkController::class , 'create']);
$router->get('/analytics/{alias}', [AnalyticsController::class , 'show']);

$router->get('/analytics/{alias}/export', [AnalyticsController::class , 'export']);
$router->post('/track/{trackingKey}', [LinkController::class , 'track']);
$router->get('/{alias}', [LinkController::class , 'visit']);

$router->dispatch(request_method(), request_path());