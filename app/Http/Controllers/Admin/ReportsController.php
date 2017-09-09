<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\GenerateAttendanceReport;
use App\Jobs\GenerateBorrowerReport;
use App\Jobs\GeneratePenaltyReport;
use App\Jobs\GenerateTransactionReport;
use App\Models\AuditTrail;
use App\Models\Fee;
use App\Models\Report;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bzarzuela\ModelFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.reports.index');
    }

    public function attendanceReport()
    {

        $model_filter = new ModelFilter('audit_trail');

        $model_filter->setRules([
            'created_at_from'        => ['from', 'created_at'],
            'created_at_to'     => ['to', 'created_at']
        ]);

        $query = AuditTrail::with('user')
                        ->where('role', '!=', 'admin')
                        ->where('role', '!=', 'faculty');

        $filters = $model_filter->getFormData();

        $data = $model_filter->filter($query)->paginate(20);

        return view('admin.reports.attendance-report', compact('data', 'filters'));
    }

    public function filterAttendanceReport(Request $request)
    {
        $model_filter = new ModelFilter('audit_trail');

        $model_filter->setFormData($request->except('_token'));

        return redirect('/admin/reports/attendance');
    }

    public function downloadAttendanceReport(Request $request)
    {
        $report = new Report($request->all());
        $report->created_by_id = Auth::user()->id;
        $report->save();

        $this->dispatch(new GenerateAttendanceReport($report, $request->except('_token')));

        return response()->json([
            'success' => true,
            'message' => 'Success'
        ]);
    }

    public function transactionReport()
    {
        $model_filter = new ModelFilter('transaction_report');

        $model_filter->setRules([
            'created_at_from'        => ['from', 'created_at'],
            'created_at_to'     => ['to', 'created_at']
        ]);

        $query = Transaction::with('book', 'user', 'fees');

        $filters = $model_filter->getFormData();
        $user = isset($filters['user_id']) ? $filters['user_id'] : '';

        if ($user != '') {
            $query->where('user_id', $user);
        }

        $data = $model_filter->filter($query)->paginate(20);

//        dd($data->toArray());

        $users = User::lists('name', 'id')->toArray();

        return view('admin.reports.transaction-report', compact('data', 'filters', 'users'));
    }

    public function filterTransactionReport(Request $request)
    {
        $model_filter = new ModelFilter('transaction_report');

        $model_filter->setFormData($request->except('_token'));

        return redirect('/admin/reports/transaction-report');
    }

    public function downloadTransactionReport(Request $request)
    {
        $report = new Report($request->all());
        $report->created_by_id = Auth::user()->id;
        $report->save();

        $this->dispatch(new GenerateTransactionReport($report, $request->except('_token')));

        return response()->json([
            'success' => true,
            'message' => 'Success'
        ]);
    }

    public function penaltyReport()
    {
        $model_filter = new ModelFilter('penalty_report');

        $model_filter->setRules([
            'created_at_from'        => ['from', 'created_at'],
            'created_at_to'     => ['to', 'created_at']
        ]);

        $query = Transaction::with('book', 'user', 'fees');

        $filters = $model_filter->getFormData();
        $user = isset($filters['user_id']) ? $filters['user_id'] : '';

        if ($user != '') {
            $query->where('user_id', $user);
        }

        $data = $model_filter->filter($query)->paginate(20);

        foreach ($data as $key => $value) {
            $fees = Fee::where('transaction_id', $value->id)->first();

            if ($fees) {
                $data[$key]->fees = $value;
            } else {
                unset($data[$key]);
            }
        }

        $users = User::lists('name', 'id')->toArray();

        return view('admin.reports.penalty_report', compact('data', 'filters', 'users'));
    }

    public function filterPenaltyReport(Request $request)
    {
        $model_filter = new ModelFilter('penalty_report');

        $model_filter->setFormData($request->except('_token'));

        return redirect('/admin/reports/penalty-report');
    }

    public function downloadPenaltyReport(Request $request)
    {
        $report = new Report($request->all());
        $report->created_by_id = Auth::user()->id;
        $report->save();

        $this->dispatch(new GeneratePenaltyReport($report, $request->except('_token')));

        return response()->json([
            'success' => true,
            'message' => 'Success'
        ]);
    }

    public function booksReport()
    {
        $model_filter = new ModelFilter('books_report');

        $model_filter->setRules([
            'created_at_from'        => ['from', 'created_at'],
            'created_at_to'     => ['to', 'created_at']
        ]);

        $query = Transaction::with('book', 'user', 'fees');

        $filters = $model_filter->getFormData();
        $user = isset($filters['user_id']) ? $filters['user_id'] : '';

        if ($user != '') {
            $query->where('user_id', $user);
        }

        $data = $model_filter->filter($query)->paginate(20);

        foreach ($data as $key => $value) {
            $fees = Fee::where('transaction_id', $value->id)->first();

            if ($fees) {
                $data[$key]->fees = $value;
            } else {
                unset($data[$key]);
            }
        }

        $users = User::lists('name', 'id')->toArray();

        return view('admin.reports.penalty_report', compact('data', 'filters', 'users'));
    }

//    public function filterPenaltyReport(Request $request)
//    {
//        $model_filter = new ModelFilter('penalty_report');
//
//        $model_filter->setFormData($request->except('_token'));
//
//        return redirect('/admin/reports/penalty-report');
//    }
//
//    public function downloadPenaltyReport(Request $request)
//    {
//        $report = new Report($request->all());
//        $report->created_by_id = Auth::user()->id;
//        $report->save();
//
//        $this->dispatch(new GeneratePenaltyReport($report, $request->except('_token')));
//
//        return response()->json([
//            'success' => true,
//            'message' => 'Success'
//        ]);
//    }

//
    public function lists($type)
    {
        $report_type = '';
        if ($type == 'attendance-report') {
            $report_type = 'Attendance Report';
        } elseif ($type == 'book-list') {
            $report_type = 'Book List';
        } elseif ($type == 'transaction-report') {
            $report_type = 'Transaction Report';
        } elseif ($type == 'penalty-report') {
            $report_type = 'Penalty Report';
        } elseif ($type == 'borrower-report') {
            $report_type = 'Borrower Report';
        }
        $reports = Report::where('type', $report_type)->orderBy('id', 'desc')->paginate(20);

        return view('admin.reports.list', compact('reports'));
    }

    public function download($id)
    {
        $report = Report::findOrFail($id);

//        if ($report->file_size == 0) {
//            alert()->error("Wasn't able to download, file size is 0");
//            return back();
//        }

        return response()->download($report->filename);
    }

    public function deleteReportFile($id)
    {
        $report = Report::findOrFail($id);

        File::delete($report->filename);

        $report->delete();

        return back();
    }


    public function borrowerReport()
    {
        $model_filter = new ModelFilter('borrower');

        $model_filter->setRules([
            'created_at_from'        => ['from', 'created_at'],
            'created_at_to'     => ['to', 'created_at']
        ]);

        $query = Transaction::with('book', 'user', 'fees');

        $filters = $model_filter->getFormData();
        $user = isset($filters['user_id']) ? $filters['user_id'] : '';

        if ($user != '') {
            $query->where('user_id', $user);
        }

        $data = $model_filter->filter($query)->paginate(20);

//        dd($data->toArray());

        $users = User::lists('name', 'id')->toArray();

        return view('admin.reports.borrower-report', compact('data', 'filters', 'users'));
    }

    public function filterBorrowerReport(Request $request)
    {
        $model_filter = new ModelFilter('borrower');

        $model_filter->setFormData($request->except('_token'));

        return redirect('/admin/reports/borrower-report');
    }

    public function downloadBorrowerReport(Request $request)
    {
        $report = new Report($request->all());
        $report->created_by_id = Auth::user()->id;
        $report->save();

        $this->dispatch(new GenerateBorrowerReport($report, $request->except('_token')));

        return response()->json([
            'success' => true,
            'message' => 'Success'
        ]);
    }

}
