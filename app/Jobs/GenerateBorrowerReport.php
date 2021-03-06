<?php

namespace App\Jobs;

use App\Events\ReportFinished;
use App\Jobs\Job;
use App\Models\Report;
use App\Models\Transaction;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class GenerateBorrowerReport extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $report;
    private $request;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Report $report, array $request)
    {
        $this->report = $report;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->report->status = Report::GENERATING;
        $this->report->type = 'Borrower Report';
        $this->report->save();

        ini_set('memory_limit', '1G');

        $query = Transaction::with('book', 'user', 'fees');

        $request_values = ['created_at_from', 'created_at_to'];

//        foreach ($request_values as $request_value) {
//            dd($request_value);
//            if ($this->request[$request_value] != '') {
//                $query = $query->where($request_value, $this->request[$request_value]);
//            }
//        }

        if (isset($this->request['created_at_from']) && $this->request['created_at_to']) {
            $query = $query->where('created_at', '>=', $this->request['created_at_from'] . ' 00:00:00')
                           ->where('created_at', '<=', $this->request['created_at_to'] . ' 23:59:59');
        }

        $user = isset($this->request['user_id']) ? $this->request['user_id'] : '';

        if ($user != '') {
            $query->where('user_id', $user);
        }

        $data = $query->get();

        $pdf    = \PDF::loadView('admin.reports.borrower_pdf', compact('data'));

        $pdf->output();
        $dom_pdf    = @$pdf->getDomPDF();
        $canvas = @$dom_pdf ->get_canvas();
        $canvas->page_text(590, 10, "{PAGE_NUM} of {PAGE_COUNT}", null, 6, array(0, 0, 0));

        Storage::makeDirectory('/reports/borrower-report/' . date('Y-m-d H:i:s') . " - {$this->report->id}");
        $final_filename = storage_path("app/reports/borrower-report/" . date('Y-m-d H:i:s') . " - {$this->report->id}/borrower-report{$this->report->id}.pdf");

        if (file_exists($final_filename)) {
            unlink($final_filename);
        }

        $pdf->setPaper('a4', 'portrait')->save($final_filename);


//        $this->report->rows = $row_count;

        $this->report->file_size = File::size($final_filename);
        $this->report->filename = $final_filename;
        $this->report->status = Report::READY;
        $this->report->save();

        event(new ReportFinished($final_filename));

    }
}
