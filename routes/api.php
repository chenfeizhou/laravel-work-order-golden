<?php

use Illuminate\Routing\Router;

Route::group([
    'prefix'        => config('golden-work-order.route.prefix'),
    'middleware'    => config('golden-work-order.route.middleware'),
    'namespace'     => 'Chenfeizhou\WorkOrder\Controllers',
], function (Router $router) {
    // 工单回调地址
    $router->post('/notify/golden-work-order/callback', 'WorkOrderController@auditCallback');
});
