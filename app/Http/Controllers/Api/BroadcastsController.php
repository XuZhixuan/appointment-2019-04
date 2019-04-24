<?php

namespace App\Http\Controllers\Api;

use App\Models\Broadcast;

class BroadcastsController extends Controller
{
    public function index()
    {
        $broadcasts = Broadcast::all()->sortByDesc('created_at');

        $data = [];
        foreach ($broadcasts as $broadcast) {
            $data[] = [
                'id' => $broadcast->id,
                'title' => $broadcast->title,
            ];
        }

        return $this->response->array($data);
    }

    public function show(Broadcast $broadcast)
    {
        return $this->response->array([
            'title' => $broadcast->title,
            'body' => $broadcast->body,
            'created_at' => $broadcast->created_at->toDateTimeString(),
        ]);
    }
}
