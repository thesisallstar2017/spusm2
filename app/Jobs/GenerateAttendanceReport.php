<?php

namespace App\Jobs;

use App\Events\ReportFinished;
use App\Jobs\Job;
use App\Models\AuditTrail;
use App\Models\Report;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class GenerateAttendanceReport extends Job implements ShouldQueue
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
        $this->report->type = 'Attendance Report';
        $this->report->save();

        ini_set('memory_limit', '1G');

        $query = AuditTrail::with('user')
            ->where('role', '!=', 'admin')
            ->where('role', '!=', 'faculty');

        if (isset($this->request['created_at_from']) && $this->request['created_at_to']) {
            $query = $query->where('created_at', '>=', $this->request['created_at_from'] . ' 00:00:00')
                ->where('created_at', '<=', $this->request['created_at_to'] . ' 23:59:59');
        }

        $data = $query->get();

        $pdf    = \PDF::loadView('admin.reports.attendance_pdf', compact('data'));

        $pdf->output();
        $dom_pdf    = @$pdf->getDomPDF();
        $canvas = @$dom_pdf ->get_canvas();
        $canvas->page_text(590, 10, "{PAGE_NUM} of {PAGE_COUNT}", null, 6, array(0, 0, 0));

        Storage::makeDirectory('/reports/attendance-report/' . date('Y-m-d H:i:s') . " - {$this->report->id}");
        $final_filename = storage_path("app/reports/attendance-report/" . date('Y-m-d H:i:s') . " - {$this->report->id}/attendance_report{$this->report->id}.pdf");

        if (file_exists($final_filename)) {
            unlink($final_filename);
        }

        $pdf->setPaper('a4', 'portrait')->save($final_filename);

//        $this->report->rows = $row_count;

        $this->report->file_size = isset($final_filename) ? File::size($final_filename) : '';
        $this->report->filename = isset($final_filename) ? $final_filename : '';
        $this->report->status = Report::READY;
        $this->report->save();

        event(new ReportFinished($final_filename));
    }
}
