<?php

namespace App\Console\Commands;

use App\Models\Item;
use App\Helper\Helper;
use Illuminate\Console\Command;

class StartLastWeekOffers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:lastWeekOffers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update last week column to true in items table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $items = Item::where(['last_week' => 1])->get();
        foreach ($items as $item) {
            $item->last_week = 0;
            $item->last_week_start = 0;
            $item->update();
        }
    }
}
