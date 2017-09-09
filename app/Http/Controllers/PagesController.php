<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Subject;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{

    /**
     * PagesController constructor.
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('pages.index', compact('request'));
    }

    public function search(Request $request)
    {
        if (!is_null($request->get('filter_by')) && !is_null($request->get('search-keyword')) && is_null($request->get('search'))) {
            $search = $request->get('search-keyword');

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
                                   WHERE (books.title LIKE '%{$search}%' 
                                   OR publisher LIKE '%{$search}%' 
                                   OR subjects.name LIKE '%{$search}%' 
                                   OR authors.name LIKE '%{$search}%' 
                                   OR books.call_number LIKE '%{$search}%')
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

        } else if (!is_null($request->get('filter_by')) && !is_null($request->get('search'))) {
            $query = Book::with('authors', 'subjects');

            $filter_by = $request->get('filter_by');
            $search = $request->get('search');

            switch ($filter_by) {
                case 'books.title' :
                    $book_title = Book::select('title')->where('id', $search)->first();

                    $books = $query->where('title', 'LIKE', "%$book_title->title%");
                    break;
                case 'authors.name' :
                    $author_name = Author::select('name')->where('id', $search)->first();

                    $books = $query->whereHas('authors', function ($query) use ($author_name) {
                        $query->where('name', 'like', "%$author_name->name%");
                    });

                    break;
                case 'subjects.name' :
                    $subject_name = Subject::select('name')->where('id', $search)->first();

                    $books = $query->whereHas('subjects', function ($query) use ($subject_name) {
                        $query->where('name', 'like', "%$subject_name->name%");
                    });

                    break;
                case 'books.publisher' :
                    $book_title = Book::select('publisher')->where('id', $search)->first();

                    $books = $query->where('publisher', '=', "$book_title->publisher");
                    break;
                case 'books.call_number' :
                    $book_title = Book::select('call_number')->where('id', $search)->first();

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
        }

        return view('pages.index', compact('request', 'data'));
    }

}
