<?php

namespace App\Console\Commands;

use App\Holiday;
use App\Models\Book;
use App\Models\Fee;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spusm:check-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking of transactions if it is expired or overdue';

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
        //Checking For Overdue
        $transactions = Transaction::where('status', 'borrowed')->get();

        if ($transactions) {
            Log::info('with transaction');
        }
//        dd($transactions->toArray());


        $count = 0;
        foreach ($transactions as $trans_key => $transaction) {

            if ($this->isWeekend(Carbon::now())) {
                continue;
            }

            if ($this->isHoliday(Carbon::now())) {
                continue;
            }


            $book = Book::with('subjects')->where('id', $transaction->book_id)->first();
            $amount = 0;
            foreach ($book->subjects as $subject) {
                if (strpos($subject->name, 'Fiction') !== false) {
                    $transactions[$trans_key]->is_fiction = true;
                    $transactions[$trans_key]->added_amount = 3;
                    $amount = 8;
                } else {
                    $transactions[$trans_key]->is_fiction = false;
                    $transactions[$trans_key]->added_amount = 5;
                    $amount = 10;
                }
            }

            if ($transaction->type == 'reserved' || $transaction->type == 'non-reserved') {

                if (Carbon::now() > $transaction->return_at) {
                    $fees = $transaction->fees()->first();

                    if (!$fees) {
                        Transaction::where('id', $transaction->id)->update(['is_overdue' => true]);

                        $fees = new Fee();
                        $fees->transaction_id = $transaction->id;
                        $fees->user_id = $transaction->user_id;
                        $fees->type = 'overdue';
                        $fees->amount = $amount;
                        $fees->receipt_no = '';
                        $fees->overdue_day_counts = 0;
                        $fees->save();
                    }

                    if ($transaction->is_fiction) {
                        $overdue_day_counts = Carbon::now()->diffInWeeks(Carbon::parse($transaction->return_at));
                    } else {
                        $overdue_day_counts = $this->isBetweenHolidayOrWeekend(
                            Carbon::parse($transaction->return_at),
                            Carbon::now(),
                            $fees->overdue_day_counts
                        );
                    }

                    $fees->overdue_day_counts = $overdue_day_counts;
                    $fees->save();

                    if ($transaction->is_fiction) {
                        $total_amount = ($fees->overdue_day_counts * $transaction->added_amount) + $amount;
                    } else {
                        if ($fees->overdue_day_counts > 1) {
                            if ($fees->overdue_day_counts == 1) {
                                $total_amount = $amount + $transaction->added_amount;
                            } else {
                                $total_amount = ($fees->overdue_day_counts * $transaction->added_amount) + $transaction->added_amount;
                            }
                        } else {
                            $total_amount = $amount;
                        }
                    }

                    $fees->transaction_id = $transaction->id;
                    $fees->user_id = $transaction->user_id;
                    $fees->type = 'overdue';
                    $fees->receipt_no = '';
                    $fees->amount = $total_amount;
                    $fees->overdue_day_counts = $overdue_day_counts;

                    $fees->save();

                    $count++;
                }
            }
        }

        //Checking and resetting transaction and book quantity if expired
        $expired_transactions = Transaction::where('status', 'reserved')->get();

        foreach($expired_transactions as $expired_transaction) {
            $count_of_days_it_was_reserved = Carbon::now()->diffInDays(Carbon::parse($expired_transaction->reserved_at));

            if ($count_of_days_it_was_reserved >= 2) {
                $book = Book::where('id', $expired_transaction->book_id)->first();

                if ($book) {
                    $book->available_quantity += 1;
                    $book->save();
                }

                $expired_transaction->status = 'expired';
                $expired_transaction->is_expired = true;
                $expired_transaction->expired_at = Carbon::now();

                $expired_transaction->save();
            }
        }

    }

    private function isHoliday($today)
    {
        $holiday = Holiday::where('event_date', $today->format('Y-m-d'))->first();

        if ($holiday) {
            return true;
        }

        return false;
    }

    private function isWeekend($today)
    {
        if ($today->isWeekEnd()) {
            return true;
        } else {
            return false;
        }
    }

    private function isBetweenHolidayOrWeekend($from, $to, $overdue_days_count)
    {
        $holidays = Holiday::where('event_date', '>=', $from->format('Y-m-d'))
            ->where('event_date', '<=', $to->format('Y-m-d'))
            ->groupBy('event_date')
            ->get();

        $new_overdue_days_count  = Carbon::now()->diffInWeekdays(Carbon::parse($from));
        if ($overdue_days_count > 0 ) {
            $new_overdue_days_count -= $holidays->count();
        }

        return $new_overdue_days_count;
    }
}
