<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Fee;
use App\Models\Subject;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class UtilitiesController extends Controller
{
    public function searchAuthor(Request $request)
    {
        $q = $request->input('q');

        $authors = Author::where('name', 'like', '%' . $q . '%')
          ->paginate(10);

        // Format for select2
        $results = [];
        foreach ($authors as $author) {
            $author->text = $author->name;
            $results[] = [
              'id'   => $author->id,
              'text' => $author->name
            ];
        }

        return [
          'results' => $results,
          'total' => $authors->total(),
          'pagination' => [
            'more' => ($authors->lastPage() > $authors->currentPage()),
            'page' => $authors->currentPage(),
          ],
        ];
    }

    public function searchSubject(Request $request)
    {
        $q = $request->input('q');

        $subjects = Subject::where('name', 'like','%' . $q . '%')
          ->paginate(10);

        // Format for select2
        $results = [];
        foreach ($subjects as $subject) {
            $subject->text = $subject->name;
            $results[] = [
              'id'   => $subject->id,
              'text' => $subject->name
            ];
        }

        return [
          'results' => $results,
          'total' => $subjects->total(),
          'pagination' => [
            'more' => ($subjects->lastPage() > $subjects->currentPage()),
            'page' => $subjects->currentPage(),
          ],
        ];
    }

    public function searchBook(Request $request)
    {
        $q = $request->input('q');

        $books = Book::select('id', 'title')->where('title', 'like','%' . $q . '%')
          ->groupBy('title')
          ->paginate(10);

        // Format for select2
        $results = [];
        foreach ($books as $book) {
            $book->text = $book->title;
            $results[]  = [
              'id'    => $book->id,
              'text'  => $book->title
            ];
        }

        return [
          'results' => $results,
          'total' => $books->total(),
          'pagination' => [
            'more' => ($books->lastPage() > $books->currentPage()),
            'page' => $books->currentPage(),
          ],
        ];
    }

    public function searchUser(Request $request)
    {
        $q = $request->input('q');

        $users = User::where('name', 'like', $q . '%')
          ->paginate(10);

        // Format for select2
        $results = [];
        foreach ($users as $user) {
            $user->text = $user->name;
            $results[]  = $user->toArray();
        }

        return [
          'results' => $results,
          'total' => $users->total(),
          'pagination' => [
            'more' => ($users->lastPage() > $users->currentPage()),
            'page' => $users->currentPage(),
          ],
        ];
    }


    public function searchPublisher(Request $request)
    {
        $q = $request->input('q');

        $books = Book::select('id', 'publisher')->where('publisher', 'like','%' . $q . '%')
            ->groupBy('publisher')
            ->paginate(10);

        // Format for select2
        $results = [];
        foreach ($books as $book) {
            $book->text = $book->publisher;
            $results[]  = [
                'id'    => $book->id,
                'text'  => $book->publisher
            ];
        }

        return [
            'results' => $results,
            'total' => $books->total(),
            'pagination' => [
                'more' => ($books->lastPage() > $books->currentPage()),
                'page' => $books->currentPage(),
            ],
        ];
    }

    public function searchCallNumber(Request $request)
    {
        $q = $request->input('q');

        $books = Book::select('id', 'call_number')->where('call_number', 'like','%' . $q . '%')
            ->groupBy('call_number')
            ->paginate(10);

        // Format for select2
        $results = [];
        foreach ($books as $book) {
            $book->text = $book->call_number;
            $results[]  = [
                'id'    => $book->id,
                'text'  => $book->call_number
            ];
        }

        return [
            'results' => $results,
            'total' => $books->total(),
            'pagination' => [
                'more' => ($books->lastPage() > $books->currentPage()),
                'page' => $books->currentPage(),
            ],
        ];
    }

    public function printReceipt(Request $request, $transaction_id)
    {
        $added_penalty = $request->get('added_penalty');
        $total_amount = $request->get('total-amount');
        $given_amount = $request->get('given-amount');
        $change = $request->get('change');
        $is_damaged = $request->get('is_damaged');

        $query = Transaction::where('id', $transaction_id)->with('book', 'user', 'fees');

        $transaction = $query->first();

        return view('printouts.receipt', compact('transaction', 'added_penalty', 'total_amount', 'given_amount', 'change','is_damaged'));
    }

    public function printLostBookReceipt(Request $request, $transaction_id)
    {
//        dd($request->all());
        $total_amount = $request->get('total-amount');
        $given_amount = $request->get('given-amount');
        $change = $request->get('change');

        $query = Transaction::where('id', $transaction_id)->with('book', 'user', 'fees');

        $transaction = $query->first();

//        dd($transaction->toArray());

        return view('printouts.lost-book-receipt', compact('transaction', 'total_amount', 'given_amount', 'change'));
    }


}
