<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\GenerateBookList;
use App\Models\Author;
use App\Models\Book;
use App\Models\Report;
use App\Models\Subject;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bzarzuela\ModelFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BooksController extends Controller
{
    private $model_filter;

    private $filter_rules = [
      'call_number' => ['=', 'call_number'],
      'card_number' => ['=', 'card_number'],
      'title' => ['like', 'title'],
      'publisher' => ['like', 'publisher'],
      'published_year' => ['=', 'published_year'],
    ];

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show']]);
        $this->model_filter = new ModelFilter('books');

        $this->model_filter->setRules($this->filter_rules);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        dd(Hash::check('password', auth()->user()->password));
//        dd(auth()->user()->password == bcrypt('password'));
        if (!Auth::user()->hasRole('admin')) {
            alert()->error('Access Denied!');
            return back();
        }

        $filters = $this->model_filter->getFormData();

        $order_by = isset($filters['order']) ? $filters['order'] : '';
        $filter_by = isset($filters['filter_by']) ? $filters['filter_by'] : '';

        $this->model_filter->setFormData($filters);

        $query = Book::with('authors', 'subjects', 'transactions');


        if ($filter_by != '') {
            if ($filters['filter_by'] == 'borrowed') {
                $query
                    ->whereHas('transactions', function ($q) {
                        $q->where('status', 'borrowed');
                    });
            }
            if ($filters['filter_by'] == 'reserved') {
                $query
                    ->whereHas('transactions', function ($q) {
                        $q->where('status', 'reserved');
                    });
            }
            if ($filters['filter_by'] == 'archived') {
                $query->where('archive', 'Yes');
            }
        }

        if ($order_by != '') {
            $books = $this->model_filter->filter($query)->orderBy($filters['sort'], $order_by)->paginate(10);
        } else {
            $books = $this->model_filter->filter($query)->orderBy('id', 'ASC')->paginate(10);
        }


        return view('admin.books.index', compact('books', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->hasRole('admin')) {
            alert()->error('You are not allowed to manage Books');
            return back();
        }

        $barcode = '';
        $check_barcode = date('Ymd').sprintf("%06d", mt_rand(1, 999999));

        $book = Book::where('barcode', '=', $check_barcode)->first();

        if (!$book) {
            $barcode = date('Ymd').sprintf("%06d", mt_rand(1, 999999));
        } else {
            $barcode = $check_barcode;
        }

        return view('admin.books.create', compact('barcode'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\BookRequest $request)
    {
//        dd($request->except('_token', 'author', 'subject'));
        $request_vals = $request->except('_token', 'author', 'subject');

        foreach ($request_vals as $request_val) {
            $request_vals['available_quantity'] = $request->get('quantity');
        }

        if (!Auth::user()->hasRole('admin')) {
            alert()->error('You are not allowed to manage Books');
            return back();
        }

        $author_arr = $request->get('author');
        $subject_arr = $request->get('subject');

        $author_ids = [];
        $subject_ids = [];

        foreach ($author_arr as $author) {
            $check_author = Author::where('name', $author)->first();

            if (!$check_author) {
                $author_data = Author::firstOrCreate([
                  'name' => $author,
                ]);

                $author_ids[] = $author_data->id;
             } else {
                $author_ids[] = $check_author->id;
            }
        }

        foreach ($subject_arr as $subject) {
            $check_subject = Subject::where('name', $subject)->first();

            if (!$check_subject) {
                $subject_data = Subject::firstOrCreate([
                   'name' => $subject,
                ]);

                $subject_ids[] = $subject_data->id;
            } else {
                $subject_ids[] = $check_subject->id;
            }
        }

        $books = Book::create($request_vals);
        $books->save();

        $books->authors()->sync($author_ids);
        $books->subjects()->sync($subject_ids);

        alert()->success(strtoupper($books->title) . " successfully added");

        return redirect('admin/books');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::with('authors', 'subjects', 'material')->findOrFail($id);

        $users = User::lists('username', 'id')->toArray();

        return view('admin.books.show', compact('book', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->hasRole('admin')) {
            alert()->error('You are not allowed to manage Books');
            return back();
        }
        $book_base = Book::find($id);

        $book_subjects = $book_base->subjects;
        $author_books = $book_base->authors;

        $book   = $book_base->toArray();
        $book['subjects']  = [];
        foreach ($book_subjects as $this_subject) {
            $book['subjects'][$this_subject->id]    = $this_subject->name;
        }

        $book['authors'] = [];
        foreach ($author_books as $this_author) {
            $book['authors'][$this_author->id]     = $this_author->name;
        }

        $subjects = Subject::whereIn('id', array_keys($book['subjects']))->lists('name', 'name')->toArray();

        $authors = Author::whereIn('id', array_keys($book['authors']))->lists('name', 'name')->toArray();

//        dd($authors, $subjects, $book);
        if (!$book) {
            alert()->error('Book: ' . $id . ' not found');

            return back();
        }

        return view('admin.books.edit', compact('book', 'subjects', 'authors'));
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

        $request_vals = $request->except('_token', 'author', 'subject');

        foreach ($request_vals as $request_val) {
            $request_vals['available_quantity'] = $request->get('quantity');
        }

        $author_arr = $request->get('authors');
        $subject_arr = $request->get('subjects');

        $author_ids = [];
        $subject_ids = [];

        foreach ($author_arr as $author) {
            $check_author = Author::where('name', $author)->first();

            if (!$check_author) {
                $author_data = Author::firstOrCreate([
                  'name' => $author,
                ]);

                $author_ids[] = $author_data->id;
            } else {
                $author_ids[] = $check_author->id;
            }
        }

        foreach ($subject_arr as $subject) {
            $check_subject = Subject::where('name', $subject)->first();

            if (!$check_subject) {
                $subject_data = Subject::firstOrCreate([
                  'name' => $subject,
                ]);

                $subject_ids[] = $subject_data->id;
            } else {
                $subject_ids[] = $check_subject->id;
            }
        }

//        dd($author_ids, $subject_ids);
        $book   = Book::with('authors', 'subjects')->find($id);
        $book->update($request_vals);

        $book->save();

        $book->authors()->sync($author_ids);
        $book->subjects()->sync($subject_ids);

        alert()->success("{$book->title} successfully edited");
        return redirect('admin/books');
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

        return redirect('/admin/books');
    }

    public function downloadBooks(Request $request)
    {
        $report = new Report($request->all());
        $report->created_by_id = Auth::user()->id;
        $report->save();

        $this->dispatch(new GenerateBookList($report, $request->except('_token')));

        return response()->json([
            'success' => true,
            'message' => 'Success'
        ]);
    }

    public function confirmPassword($password)
    {
        if (Hash::check($password, auth()->user()->password)) {
            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false
        ]);
    }

    public function archive(Request $request, $id)
    {
//        dd($request->all());
        $this->validate($request, [
            'reason_for_weeding' => 'required'
        ]);

        $book = Book::find($request->get('book-id'));

        $book->reason_for_weeding = $request->get('reason_for_weeding');
        $book->archive = 'Yes';
        $book->archived_by = auth()->user()->name;
        $book->save();

        return response()->json([
            'success' => true,
            'message' => 'Success'
        ]);
    }
}
