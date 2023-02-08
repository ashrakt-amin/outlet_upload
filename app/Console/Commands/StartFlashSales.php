<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Console\Command;

class StartFlashSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:flash_sales';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update flash sales column to false in items table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $items = Item::where(['flash_sales' => 1])->get();
        foreach ($items as $item) {
            $item->flash_sales = 0;
            $item->update();
        }
    }
}
