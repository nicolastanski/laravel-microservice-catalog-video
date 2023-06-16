<?php

namespace App\Repositories\Transaction;

use Core\UseCase\Interfaces\TransactionInterface;
use Illuminate\Support\Facades\DB;

class TransactionDB implements TransactionInterface
{
    public function __construct()
    {
        DB::beginTransaction();
    }

    public function commit()
    {
        DB::commit();
    }

    public function rollback()
    {
        DB::rollback();
    }
}
