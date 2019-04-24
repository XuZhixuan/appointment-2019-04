<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class Initialize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:initialize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '项目初始化，创建用户、队列';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (Cache::has('queue')) {
            Cache::forget('queue');
        }

        $queue = [];

        Cache::forever('queue', $queue);
        print "成功创建队列\n";

        $admin = new User([
            'role' => 'admin'
        ]);

        $admin->save();
        print "成功创建管理员\n";


    }
}
