<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => 'bindings',
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function ($api) {
        // 小程序登录
        $api->post('authorizations', 'AuthorizationsController@store')
            ->name('api.authorization.store');

        // 刷新Token
        $api->put('authorizations/current', 'AuthorizationsController@update')
            ->name('api.authorizations.update');
    });

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        //
        $api->group(['middleware' => 'api.auth'], function ($api) {
            $api->get('queue', 'QueueController@store')
                ->name('api.queue.store');

            $api->get('queue/current', 'QueueController@index')
                ->name('api.queue.index');

            $api->delete('queue/current', 'QueueController@delete')
                ->name('api.queue.delete');
        });

        $api->get('broadcasts', 'BroadcastsController@index')
            ->name('api.broadcasts.index');

        $api->get('broadcasts/{broadcast}', 'BroadcastsController@show')
            ->name('api.broadcasts.show');
    });
});
