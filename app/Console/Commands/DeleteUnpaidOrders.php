<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class DeleteUnpaidOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:unpaid-orders {hours=24}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unpaid orders after x amount of hours';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $hours = $this->argument('hours');
        $count = Order::deleteUnpaidOrders($hours);
        $this->info("$count unpaid orders were deleted");
        return CommandAlias::SUCCESS;
    }
}
