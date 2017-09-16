<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Subject;
use App\Models\Transaction;
use Bzarzuela\ModelFilter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{

    private $model_filter;

    private $filter_rules = [

    ];

    /**
     * PagesController constructor.
     */
    public function __construct()
    {
//        $this->middleware('auth');
        $this->model_filter = new ModelFilter('opac');

        $this->model_filter->setRules($this->filter_rules);
    }

    public function index(Request $request)
    {
        $filters = $this->model_filter->getFormData();
        $filter_by = isset($filters['filter_by']) ? $filters['filter_by'] : '';
        $search_keyword = isset($filters['search-keyword']) ? $filters['search-keyword'] : '';
        $search = isset($filters['search']) ? $filters['search'] : '';

        $req_book_title = isset($filters['book_title']) ? $filters['book_title'] : '';
        $req_authors_name = isset($filters['authors_name']) ? $filters['authors_name'] : '';
        $req_subject_name = isset($filters['subject_name']) ? $filters['subject_name'] : '';
        $req_publisher_name = isset($filters['publisher_name']) ? $filters['publisher_name'] : '';
        $req_call_number = isset($filters['call_number']) ? $filters['call_number'] : '';

//        dd(!empty($filter_by), $search_keyword, $search, $req_book_title,
//            $req_authors_name, $req_subject_name, $req_publisher_name, $req_call_number);
//        dd($filters);

        if (!empty($filter_by) && !empty($search_keyword) && empty($search)) {
            $type = 'default-search';
            $keyword = $search_keyword;

            $books = DB::select("
                                SELECT
                                    books.archive,
                                    books.id,
                                    books.card_number,
                                    books.call_number,
                                    books.published_year,
                                    books.title,
                                    books.barcode,
                                    substring_index(group_concat(books.publisher SEPARATOR ','), ',', 1) as publisher,
                                    group_concat(authors.name) as author_name,
                                    substring_index(group_concat(subjects.name SEPARATOR ','), ',', 1) as subject_name
                                   from books
                                   LEFT JOIN author_book on author_book.book_id = books.id
                                   LEFT JOIN authors on author_book.author_id = authors.id
                                   LEFT JOIN book_subject on book_subject.book_id = books.id
                                   LEFT JOIN subjects on book_subject.subject_id = subjects.id
                                   WHERE (books.title LIKE '%{$keyword}%' 
                                   OR publisher LIKE '%{$keyword}%' 
                                   OR subjects.name LIKE '%{$keyword}%' 
                                   OR authors.name LIKE '%{$keyword}%' 
                                   OR books.call_number LIKE '%{$keyword}%')
                                   GROUP BY books.id
                                ");
            $result = json_decode(json_encode($books), true);

            $page = $request->get('page');
            $perPage = 20;
            $offset = ($page * $perPage) - $perPage;

            $data = new LengthAwarePaginator(array_slice($result, $offset, $perPage, true),
                count($result), $perPage, $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

        } else if (!empty($filter_by) && !empty($search)) {
            $type = 'default-search';

            $query = Book::with('authors', 'subjects');

            $keyword = $search;

            switch ($filter_by) {
                case 'books.title' :
                    $book_title = Book::select('title')->where('id', $keyword)->first();

                    $books = $query->where('title', 'LIKE', "%$book_title->title%");
                    break;
                case 'authors.name' :
                    $author_name = Author::select('name')->where('id', $keyword)->first();

                    $books = $query->whereHas('authors', function ($query) use ($author_name) {
                        $query->where('name', 'like', "%$author_name->name%");
                    });

                    break;
                case 'subjects.name' :
                    $subject_name = Subject::select('name')->where('id', $keyword)->first();

                    $books = $query->whereHas('subjects', function ($query) use ($subject_name) {
                        $query->where('name', 'like', "%$subject_name->name%");
                    });

                    break;
                case 'books.publisher' :
                    $book_title = Book::select('publisher')->where('id', $keyword)->first();

                    $books = $query->where('publisher', '=', "$book_title->publisher");
                    break;
                case 'books.call_number' :
                    $book_title = Book::select('call_number')->where('id', $keyword)->first();

                    $books = $query->where('call_number', '=', "$book_title->call_number");
                    break;
                default :
                    $books = $query;
            }

            $result = $books->get();

            $page = $request->get('page');
            $perPage = 20;
            $offset = ($page * $perPage) - $perPage;

            $data = new LengthAwarePaginator(array_slice($result->toArray(), $offset, $perPage, true),
                count($result), $perPage, $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $type = 'multiple-search';

            $select = "
                SELECT
                    books.archive,
                    books.id,
                    books.card_number,
                    books.call_number,
                    books.published_year,
                    books.title,
                    books.barcode,
                    substring_index(group_concat(books.publisher SEPARATOR ','), ',', 1) as publisher,
                    group_concat(authors.name) as author_name,
                    substring_index(group_concat(subjects.name SEPARATOR ','), ',', 1) as subject_name
                   from books
                   LEFT JOIN author_book on author_book.book_id = books.id
                   LEFT JOIN authors on author_book.author_id = authors.id
                   LEFT JOIN book_subject on book_subject.book_id = books.id
                   LEFT JOIN subjects on book_subject.subject_id = subjects.id
        WHERE (books.title LIKE '%{$req_book_title}%'
    AND publisher LIKE '%{$req_publisher_name}%'
    AND subjects.name LIKE '%{$req_subject_name}%'
    AND authors.name LIKE '%{$req_authors_name}%'
    AND books.call_number LIKE '%{$req_call_number}%')
                   GROUP BY books.id
        ";

            $books = DB::select($select);
            $result = json_decode(json_encode($books), true);
//            dd($result, $request->all());
            $page = $request->get('page');
            $perPage = 20;
            $offset = ($page * $perPage) - $perPage;

            $data = new LengthAwarePaginator(array_slice($result, $offset, $perPage, true),
                count($result), $perPage, $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }

        return view('pages.index', compact('request', 'data', 'type'));
    }

    public function search(Request $request)
    {
        $this->model_filter->setFormData($request->except('_token'));

        return redirect('/');
    }
}
