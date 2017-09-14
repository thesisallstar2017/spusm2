<?php

namespace App\Http\Controllers\Admin;

use App\Events\BookReserved;
use App\Models\Author;
use App\Models\Book;
use App\Models\Fee;
use App\Models\Material;
use App\Models\Subject;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bzarzuela\ModelFilter;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionsController extends Controller
{
    private $model_filter;

    private $filter_rules = [
      'book_id'       => ['=', 'book_id'],
      'status'        => ['=', 'status'],
//      'is_lost'     => ['=', 'is_lost'],
//      'is_expired'  => ['=', 'is_expired'],
//      'is_overdue'  => ['=', 'is_overdue']
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->model_filter = new ModelFilter('transactions');

        $this->model_filter->setRules($this->filter_rules);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filters = $this->model_filter->getFormData();

        $order_by = isset($filters['order']) ? $filters['order'] : '';

        $filter_by_date = isset($filters['filter_by_date']) ? $filters['filter_by_date'] : '';
        $from = isset($filters['from']) ? $filters['from'] : '';
        $to = isset($filters['to']) ? $filters['to'] : '';
        $barcode = isset($filters['barcode']) ? $filters['barcode'] : '';
        $user = isset($filters['user_id']) ? $filters['user_id'] : '';
        $is_lost = isset($filters['is_lost']) ? $filters['is_lost'] : '';
        $is_expired = isset($filters['is_expired']) ? $filters['is_expired'] : '';
        $is_overdue = isset($filters['is_overdue']) ? $filters['is_overdue'] : '';
//        $book_title = isset($filters['book_id']) ? $filters['book_id'] : '';

        $this->model_filter->setFormData($filters);

        $query = Transaction::with('book', 'user', 'fees');

        if (!Auth::user()->hasRole('admin')){
            $filters['user_id'] = '';
            $query->where('user_id', Auth::user()->id);
        }

        if ($user != '') {
            $query->where('user_id', $user);
        }

        if (!empty($is_lost) && $is_lost != 2) {
            $data = $this->model_filter->filter($query)
                ->where('is_lost', $is_lost);
//                ->orderBy($filters['sort'], $order_by);
        }
        if (!empty($is_expired) && $is_expired != 2) {
            $data = $this->model_filter->filter($query)
                ->where('is_expired', $is_expired);
//                ->orderBy($filters['sort'], $order_by);
        }
        if (!empty($is_overdue) && $is_overdue != 2) {
            $data = $this->model_filter->filter($query)
                ->where('is_overdue', $is_overdue);
//                ->orderBy($filters['sort'], $order_by);
        }

        if ($order_by != '') {
            $data = $this->model_filter->filter($query)
              ->orderBy($filters['sort'], $order_by);
        } elseif ($from != '' && $to != '') {
            $data = $this->model_filter->filter($query)
              ->where($filter_by_date, '>=', $from . ' 00:00:00')
              ->where($filter_by_date, '<=', $to . ' 23:59:59')
              ->orderBy($filters['sort'], $order_by);
        } elseif ($barcode != '') {
            $book = Book::where('barcode', $barcode)->first(['id']);
            if (!is_null($book)) {
                $data = $this->model_filter->filter($query)
                    ->where('book_id', $book->id);
            } else {
                $data = $this->model_filter->filter($query)
                        ->whereRaw('book_id IS NULL');
            }
        } else {
            if (!Auth::user()->hasRole('admin')) {
                $data = $this->model_filter->filter($query)->orderBy('return_at', 'DESC');
            } else {
                $data = $this->model_filter->filter($query)->orderBy('id', 'DESC');
            }
        }

        $transactions = $data->paginate(10);
        $book_ids = [];

        foreach ($transactions as $key => $transaction) {
            $material = Material::select('name')->where('id', $transaction->book->material_id)->first();
            $authors = Book::with('authors', 'subjects')->where('id', $transaction->book_id)->first();

            $auth_name = [];
            foreach ($authors->authors as $author) {
                $auth_name[] = $author;
            }

            $subj_name = [];
            foreach ($authors->subjects as $subject) {
                $subj_name[] = $subject;
                if (strpos($subject, 'Fiction') !== false) {
                    $is_fiction = true;
                } else {
                    $is_fiction = false;
                }
            }

            $transactions[$key]->authors = $auth_name;
            $transactions[$key]->subjects = $subj_name;
            $transactions[$key]->material_name = $material->name;
            $transactions[$key]->is_fiction = $is_fiction;
            $book_ids[] = $transaction->book_id;
        }

        $book_list = Book::whereIn('id', $book_ids)->lists('title', 'id')->toArray();

        $users = User::lists('name', 'id')->toArray();



//        dd($users);
//        dd($filters);
//        dd($transactions->toArray());

        return view('admin.transaction.index', compact('transactions', 'filters', 'users', 'book_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.transaction.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function filter(Request $request)
    {
        $this->model_filter->setFormData($request->except('_token'));

        return redirect('/admin/transaction');
    }

    public function searchByBarcode(Request $request)
    {
        $barcode = $request->input('barcode');

        $transactions = Book::with('transactions')->where('barcode', $barcode)->first();

        if ($transactions) {
            $success = true;
        } else {
            $success = false;
        }

        return response()->json(['success' => $success, 'transactions' => $transactions]);
    }

    public function reserve($id)
    {
        $book = Book::with('subjects')->findOrFail($id);

        //bahala si james dito
        $general_reference = [
            'encyclopedia',
            'atlas',
            'dictionar',
            'pamphlet',
            'periodical',
            'newspaper',
            'magazine'
        ];

        foreach ($book->subjects as $subject) {
            $sub_name = trim(strtolower($subject->name));

            foreach ($general_reference as $value) {
                if (strpos($sub_name, $value) !== false) {
                    alert()->info('For Library Use Only!')->persistent('Okay');
                    return back();
                }
            }
        }
        //up to here

        $check_transaction_record = Transaction::select(DB::raw('type, status, book_id, user_id'))
          ->where('user_id', Auth::user()->id)
          ->where('status', '!=','returned')->get();

        $count = 0;
        $is_non_reserved = false;
        $non_reserve_count = 0;
        foreach($check_transaction_record as $value ) {

            if (!($value->status == 'rejected' || $value->status == 'canceled' || $value->status == 'lost_and_replaced')) {
                if (!Auth::user()->hasRole('admin') && $value->book_id == $book->id) {
                    alert()->warning("Please choose another book with a different title.",  'You already have an active reservation for this book')->persistent('Okay');
                    return back();
                }

                if ($value->type == 'non-reserved') {
                    $is_non_reserved = true;
                    $non_reserve_count++;
                    $count--;
                }

                $count++;
            }
        }

        if (!Auth::user()->hasRole('admin') && $count >= 2) {
            alert()->warning("Sorry, You have exceeded the number of books that can be reserved through OPAC.")->persistent('Okay');
            return back();
        }

//        if (!Auth::user()->hasRole('admin') && $)

        if ($book->available_quantity > 0) {
            $borrowed_quantity = ($book->available_quantity - 1);

            $borrow_book = Transaction::create([
              'book_id'     => $book->id,
              'user_id'     => Auth::user()->id,
              'quantity'    => $book->available_quantity,
              'type'        => Auth::user()->hasRole('admin') ? 'non-reserved' : 'reserved',
              'status'      => 'reserved',
              'reserved_at' => Carbon::now(),
              'borrowed_at' => null,
              'returned_at' => null,
              'is_expired'  => false,
              'is_overdue'  => false,
              'is_lost'     => false
            ]);

            $borrow_book->save();

            $book->available_quantity = $borrowed_quantity;
            $book->save();
            alert()->success(strtoupper($book->title) . " is now reserved.")->persistent('Okay');

            $reserved_by = User::where('id', $borrow_book->user_id)->first()->name;
            $book_reserved = strtoupper($book->title);
            event(new BookReserved($book_reserved, $reserved_by));
        } else {
            alert()->warning("There are no available copies of this book.")->persistent('Okay');
            return back();
        }

        return redirect('admin/transaction');

    }

    private function _appr($id)
    {
        $transaction = Transaction::find($id);

        $book = Book::where('id', $transaction->book_id)->first();

        $subs = [];
        foreach ($book->subjects as $subject) {
            $subs[] = $subject->name;
        }

        $return_at = '';

        foreach($subs as $key => $value) {
            if (strpos($value, 'Fiction') !== false) {
                $return_at = Carbon::parse($transaction->reserved_at)->addWeek();
            } elseif($transaction->type == 'non-reserved' && strpos($value, 'Fiction') === false) {
                $return_at = Carbon::now()->addDays(2);
            } else {
                $return_at = Carbon::now()->tomorrow()->hour(9);
            }
        }

        $transaction->status = 'borrowed';
        $transaction->borrowed_at = Carbon::now();
        $transaction->return_at = $return_at;
        $transaction->save();

        return $transaction;
    }

    public function approveBookReservation($id)
    {
        $transaction = Transaction::find($id);

        $book = Book::where('id', $transaction->book_id)->first();

        $subs = [];
        foreach ($book->subjects as $subject) {
            $subs[] = $subject->name;
        }

        $return_at = '';

        foreach($subs as $key => $value) {
            if (strpos($value, 'Fiction') !== false) {
                $return_at = Carbon::parse($transaction->reserved_at)->addWeek();
            } elseif($transaction->type == 'non-reserved' && strpos($value, 'Fiction') === false) {
                $return_at = Carbon::now()->addDays(2);
            } else {
                $return_at = Carbon::now()->tomorrow()->hour(9);
            }
        }

        $transaction->status = 'borrowed';
        $transaction->borrowed_at = Carbon::now();
        $transaction->return_at = $return_at;
        $transaction->save();

        return json_encode($transaction);
    }

    public function rejectReservation($id)
    {
        $transaction = Transaction::find($id);

        $book = Book::where('id', $transaction->book_id)->first();

        $book->available_quantity += 1;
        $book->save();

        if (Auth::user()->hasRole('admin')) {
            $transaction->status = 'rejected';
        } else {
            $transaction->status = 'canceled';
        }

        $transaction->is_rejected = true;
        $transaction->save();

        return $transaction;
    }

    public function returnBookWithExactAmount($id)
    {
        $transaction = Transaction::find($id);

        $transaction->status = 'returned';
        $transaction->returned_at = Carbon::now();

        $transaction->save();

        $book = Book::find($transaction->book_id);

        $book->available_quantity += 1;
        $book->save();

    }

    public function returnBook(Request $request, $id)
    {
        $initial_amount = $request->get('initial-amount');
        $added_penalty = $request->get('added_penalty');
        $total_amount = $request->get('total-amount');
        $given_amount = $request->get('given-amount');
        $change = $request->get('change');
        $is_damaged = $request->get('is_damaged');

        $transaction = Transaction::find($id);
        $fees = Fee::where('transaction_id', $id)->first();
        $book = Book::find($transaction->book_id);

        if ($is_damaged == 'With Damage') {
            $transaction->is_damaged = true;
        }

        if ($given_amount < $total_amount) {
            $fees->amount -= $given_amount;
            if ($change > 0) {
                $fees->with_change = true;
            } else {
                $fees->with_change = false;
            }
            $fees->receipt_no = $fees->id;
            $fees->change = $change;
            $fees->save();

            $transaction->save();

        } else {
            $transaction->status = 'returned';
            $transaction->returned_at = Carbon::now();
            $transaction->save();

            $fees->is_paid = true;
            $fees->receipt_no = $fees->id;
            if ($change > 0) {
                $fees->with_change = true;
            } else {
                $fees->with_change = false;
            }
            $fees->change = $change;
            $fees->save();

            $book->available_quantity += 1;
            $book->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Succcc'
        ]);
    }

    public function validateReturnBook(Request $request, $id)
    {
        $initial_amount = $request->get('initial-amount');
        $added_penalty = $request->get('added_penalty');
        $total_amount = $request->get('total-amount');
        $given_amount = $request->get('given-amount');
        $change = $request->get('change');
        $is_damaged = $request->get('is_damaged');

        $message = [];

        if (empty($given_amount)) {
            $message[] = 'Amount Received must not be empty';
        }

        if (empty($added_penalty) && $is_damaged == 'With Damage') {
            $message[] = 'Added Penalty must not be empty';
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function issueBooks()
    {
        if (!Auth::user()->hasRole('admin')) {
            alert()->error('Your role does not have permission to access this page.');
            return back();
        }
        return view('admin.transaction.issue-books');
    }

    public function lostBook(Request $request, $id)
    {
        $total_lost_amount = $request->get('total-lost-amount');
        $lost_given_amount = $request->get('lost-given-amount');
        $lost_change = $request->get('lost-change');
        $is_lost = true;

        $transaction = Transaction::find($id);
        $fees = Fee::where('transaction_id', $id)->first();
        $book = Book::find($transaction->book_id);

        $transaction->status = 'lost_and_replaced';
        $transaction->is_lost = true;
        $transaction->returned_at = Carbon::now();
        $transaction->save();

        if ($fees) {
            $fees->is_paid = true;
            $fees->receipt_no = $fees->id;
            if ($lost_change > 0) {
                $fees->with_change = true;
            } else {
                $fees->with_change = false;
            }
            $fees->amount = $total_lost_amount;
            $fees->change = $lost_change;
            $fees->save();
        } else {
            if ($lost_change > 0) {
                $with_change = true;
            } else {
                $with_change = false;
            }
            $c_fee = Fee::select('id')->orderBy('id', 'desc')->first();

            if ($c_fee) {
                $receipt_number = $c_fee->id += 1;
            } else {
                $receipt_number = 1;
            }

            Fee::create([
               'transaction_id' => $id,
               'user_id'    => Auth::user()->id,
               'type'       => 'lost',
               'amount'     => $total_lost_amount,
               'is_paid'    => true,
               'receipt_no' => $receipt_number,
               'with_change'=> $with_change,
               'change'     => $lost_change,
            ]);
        }

        $book->available_quantity -= 1;
        $book->save();

        return response()->json([
            'success' => true,
            'message' => 'Succcc'
        ]);
    }

    public function reserveBookAdmin(Request $request, $id)
    {
        $user = User::with('roles')->find($request->get('user_id'));

        $book = Book::with('subjects')->findOrFail($id);

        $success = true;
        $message = '';

        //bahala si james dito
        $general_reference = [
            'encyclopedia',
            'atlas',
            'dictionar',
            'pamphlet',
            'periodical',
            'newspaper',
            'magazine'
        ];

        foreach ($book->subjects as $subject) {
            $sub_name = trim(strtolower($subject->name));

            foreach ($general_reference as $value) {
                if (strpos($sub_name, $value) !== false) {
                    $message = 'For Library Use Only!';
                    $success = false;

                    return response()->json([
                        'success' => $success,
                        'message' => $message
                    ]);
                }
            }
        }
        //up to here

        $check_transaction_record = Transaction::select(DB::raw('type, status, book_id, user_id'))
            ->where('user_id', $request->get('user_id'))
            ->where('status', '!=','returned')->get();

        $count = 0;
        $is_non_reserved = false;
        $non_reserve_count = 0;
        foreach($check_transaction_record as $value ) {
            if (!auth()->user()->hasRole('admin') && $value->book_id == $book->id) {
                $message = 'Please choose another book with a different title.';
                $success = false;

                return response()->json([
                    'success' => $success,
                    'message' => $message
                ]);
            }

            if (!($value->status == 'rejected' || $value->status == 'canceled')) {
                if ($value->type == 'non-reserved') {
                    $is_non_reserved = true;
                    $non_reserve_count++;
                }

                $count++;
            }

        }

        if ($non_reserve_count >= 1) {
            $message = "Sorry, {$user->name} have exceeded the number of books that can be processed by admin.";
            $success = false;

            return response()->json([
                'success' => $success,
                'message' => $message
            ]);
        }

        if (!$user->hasRole('admin') && $count >= 2 && $non_reserve_count >= 1) {
            $message = 'Sorry, You have exceeded the number of books that can be reserved through OPAC.';
            $success = false;

            return response()->json([
                'success' => $success,
                'message' => $message
            ]);
//            alert()->warning("Sorry, You have exceeded the number of books that can be reserved through OPAC.")->persistent('Okay');
//            return back();
        }

//        if (!Auth::user()->hasRole('admin') && $)

        if ($book->available_quantity > 0) {
            $borrowed_quantity = ($book->available_quantity - 1);

            $borrow_book = Transaction::create([
                'book_id'     => $book->id,
                'user_id'     => $request->get('user_id'),
                'quantity'    => $book->available_quantity,
                'type'        => Auth::user()->hasRole('admin') ? 'non-reserved' : 'reserved',
                'status'      => 'reserved',
                'reserved_at' => Carbon::now(),
                'borrowed_at' => null,
                'returned_at' => null,
                'is_expired'  => false,
                'is_overdue'  => false,
                'is_lost'     => false
            ]);

            $borrow_book->save();
            $this->_appr($borrow_book->id);

            $book->available_quantity = $borrowed_quantity;
            $book->save();
            $message = strtoupper($book->title) . " is now borrowed.";
            $success = true;
//            alert()->success(strtoupper($book->title) . " is now reserved.")->persistent('Okay');
        } else {
            $message = 'There are no available copies of this book';
            $success = false;

            return response()->json([
                'success' => $success,
                'message' => $message
            ]);
//            alert()->warning("There are no available copies of this book.")->persistent('Okay');
//            return back();
        }

        return response()->json([
            'success' => $success,
            'message' => $message
        ]);
    }
}
