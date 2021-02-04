<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'        => config('golden-work-order.route.prefix'),
    'middleware'    => config('golden-work-order.rout.middleware'),
    'namespace'     => 'Chenfeizhou\WorkOrder\Controllers',
], function (Route $router) {
    // 工单回调地址
    $router->post('/notify/golden-work-order/callback', 'WorkOrderController@auditCallback');
});
