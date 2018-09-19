<?php

namespace App\Console\Commands;

use App\Entity\SubjectBalance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class clearData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clear data in tables of invoice, fund, cost, asset, asset_alter, sallary, voucher and their items';

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
     * @throws \Exception
     */
    public function handle()
    {
        $tables = [
            'invoice', 'invoice_item', 'cost', 'cost_item', 'salary',
            'fund', 'fund_item', 'voucher', 'voucher_item', 'asset',
            'asset_alter', 'subject_balances',
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        SubjectBalance::subjectBalanceNew('1', '2018-8-1');

        DB::table('subject_balances')->where('account_subject_number', '1002')->update(['qmye_j' => 100000]);
        DB::table('subject_balances')->where('account_subject_number', '3001')->update(['qmye_d' => 100000]);
    }
}
