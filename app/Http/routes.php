<?php
Route::get('/', ['as' => 'show_homepage', 'uses' => 'PagesController@index']);
Route::get('/home', 'PagesController@index');
Route::post('/', 'PagesController@search');


//Auth
Route::auth();

//Users
Route::post('/admin/users/filter', 'Admin\UsersController@filter');
Route::resource('admin/users', 'Admin\UsersController');


//Books
Route::post('/admin/books/{book_id}/archive', 'Admin\BooksController@archive');
Route::get('/admin/books/download', 'Admin\BooksController@downloadBooks');
Route::get('/admin/books/{password}/confirm-password', 'Admin\BooksController@confirmPassword');
Route::post('/admin/books/filter', 'Admin\BooksController@filter');
Route::resource('admin/books', 'Admin\BooksController');

//Materials
Route::post('/admin/materials/filter', 'Admin\MaterialsController@filter');
Route::resource('admin/materials', 'Admin\MaterialsController');

//Transactions
Route::get('/admin/transaction/issue-books', ['as' => 'admin.transaction.issue-books', 'uses' => 'Admin\TransactionsController@issueBooks']);
Route::get('/admin/return-books/{book_id}', 'Admin\TransactionsController@validateReturnBook');
Route::post('/admin/transaction/filter', 'Admin\TransactionsController@filter');
Route::post('/admin/transaction/search-by-barcode', 'Admin\TransactionsController@searchByBarcode');
Route::resource('admin/transaction', 'Admin\TransactionsController');
// reserve not admin
Route::post('/admin/reserve-books/{book_id}', 'Admin\TransactionsController@reserve');
// reserve admin
Route::post('/admin/reserve-books-admin/{book_id}', 'Admin\TransactionsController@reserveBookAdmin');

Route::post('/admin/approve-reservation/{book_id}', 'Admin\TransactionsController@approveBookReservation');
Route::post('/admin/return-books/{book_id}', 'Admin\TransactionsController@returnBook');
Route::post('/admin/return-books-exact/{book_id}', 'Admin\TransactionsController@returnBookWithExactAmount');
Route::post('/admin/reject-reservation/{book_id}', 'Admin\TransactionsController@rejectReservation');
Route::post('/admin/lost-book/{book_id}', 'Admin\TransactionsController@lostBook');


//Select2 Ajax Search
Route::get('authors/search', 'UtilitiesController@searchAuthor');
Route::get('subjects/search', 'UtilitiesController@searchSubject');
Route::get('books/search', 'UtilitiesController@searchBook');
Route::get('users/search', 'UtilitiesController@searchUser');
Route::get('publisher/search', 'UtilitiesController@searchPublisher');
Route::get('call_number/search', 'UtilitiesController@searchCallNumber');
Route::get('transaction_books/search', 'UtilitiesController@transactionBooks');

//Change Password
Route::get('change-password', 'Auth\ChangePasswordController@form');
Route::post('change-password', 'Auth\ChangePasswordController@change');

Route::get('/printouts/receipt/{transaction_id}', 'UtilitiesController@printReceipt');
Route::get('/printouts/lost-book-receipt/{transaction_id}', 'UtilitiesController@printLostBookReceipt');

Route::get('/admin/reports/download/attendance-report', 'Admin\ReportsController@downloadAttendanceReport');
Route::get('/admin/reports/attendance', 'Admin\ReportsController@attendanceReport');
Route::post('/admin/reports/attendance/filter', 'Admin\ReportsController@filterAttendanceReport');

Route::get('/admin/reports/transaction-report', 'Admin\ReportsController@transactionReport');
Route::get('/admin/reports/download/transaction-report', 'Admin\ReportsController@downloadTransactionReport');
Route::post('/admin/reports/transaction/filter', 'Admin\ReportsController@filterTransactionReport');

Route::get('/admin/reports/penalty-report', 'Admin\ReportsController@penaltyReport');
Route::get('/admin/reports/download/penalty-report', 'Admin\ReportsController@downloadPenaltyReport');
Route::post('/admin/reports/penalty/filter', 'Admin\ReportsController@filterPenaltyReport');

Route::get('/admin/reports/borrower-report', 'Admin\ReportsController@borrowerReport');
Route::get('/admin/reports/download/borrower-report', 'Admin\ReportsController@downloadBorrowerReport');
Route::post('/admin/reports/borrower/filter', 'Admin\ReportsController@filterBorrowerReport');

Route::resource('admin/reports', 'Admin\ReportsController');

Route::get('/admin/reports/list/{type}', 'Admin\ReportsController@lists');
Route::get('/admin/reports/download/{id}', 'Admin\ReportsController@download');
Route::get('/admin/reports/delete-report/{id}', 'Admin\ReportsController@deleteReportFile');

Route::resource('admin/holiday', 'Admin\HolidayController');


