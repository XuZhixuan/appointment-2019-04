<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class QueueController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        if (!Cache::has('keys:user:' . Auth::id())) {
            return $this->response->errorBadRequest('还未取号');
        }

        $key = Cache::get('keys:user:' . Auth::id());
        $queue = Cache::get('queue');

        $last = array_search($key, $queue) + 1;

        return $this->response->array([
            'queue' => int_to_string($key),
            'last' => $last
        ]);
    }

    /**
     * @return mixed
     */
    public function store()
    {
        if (Cache::has('keys:user:' . Auth::id())) {
            return $this->response->errorBadRequest('请勿重复取号');
        }

        $queue = Cache::get('queue');
        $key = (last($queue) ? last($queue) : 0) + 1;

        if ($key > config('basic.queue.max_length') && !array_search(0, $queue)) {
            $key = 0;
        }

        array_push($queue, $key);

        Cache::put('keys:user:' . Auth::id(), $key, now()->addHour());
        Cache::forever('queue', $queue);

        $last = count($queue);

        return $this->response->array([
            'queue' => int_to_string($key),
            'last' => $last
        ])->setStatusCode(201);
    }

    /**
     * @return \Dingo\Api\Http\Response|void
     */
    public function delete()
    {
        if (!Cache::has('keys:user:' . Auth::id())) {
            return $this->response->errorBadRequest('还未取号');
        }

        $queue = Cache::get('queue');
        $key = Cache::get('keys:user:' . Auth::id());

        unset($queue[array_search($key, $queue)]);

        $queue = array_values($queue);

        Cache::forever('queue', $queue);
        Cache::forget('keys:user:' . Auth::id());

        return $this->response->noContent();
    }
}
