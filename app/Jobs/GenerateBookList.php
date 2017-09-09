<?php

namespace App\Jobs;

use App\Events\ReportFinished;
use App\Jobs\Job;
use App\Models\Book;
use App\Models\Report;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDO;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Bus\DispatchesJobs;

class GenerateBookList extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

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
        $this->report->type = 'Book List';
        $this->report->save();

        ini_set('memory_limit', '1G');

        $query = Book::with('authors', 'subjects', 'transactions');

        $request_values = ['call_number', 'card_number', 'published_year'];

        foreach ($request_values as $request_value) {
            if ($this->request[$request_value] != '') {
                $query = $query->where($request_value, $this->request[$request_value]);
            }
        }

        if (isset($this->request['title']) && $this->request['title'] != '') {
            $query->where('title', 'LIKE', "%" . $this->request['title'] . '%');
        }

        if (isset($this->request['publisher']) && $this->request['publisher'] != '') {
            $query->where('publisher', 'LIKE', "%" . $this->request['publisher'] . '%');
        }

        if (isset($this->request['filter_by']) && $this->request['filter_by'] != '') {
            if ($this->request['filter_by'] == 'borrowed') {
                $query
                    ->whereHas('transactions', function ($q) {
                        $q->where('status', 'borrowed');
                    });
            }
            if ($this->request['filter_by'] == 'reserved') {
                $query
                    ->whereHas('transactions', function ($q) {
                        $q->where('status', 'reserved');
                    });
            }
            if ($this->request['filter_by'] == 'archived') {
                $query->where('archive', 'Yes');

            }
        }

        if ($query->count() > 500) {
            $chunk_size = 200;
            $query->chunk($chunk_size, function($books) use(&$chunk_size) {
                $request = $this->request;
                $pdf    = \PDF::loadView('admin.reports.book-list', compact('books', 'request'));

                $pdf->output();
                $dom_pdf    = $pdf->getDomPDF();
                $canvas = $dom_pdf ->get_canvas();
                $canvas->page_text(590, 10, "{PAGE_NUM} of {PAGE_COUNT}", null, 6, array(0, 0, 0));

                Storage::makeDirectory('book_list/' . date('Y-m-d') . " - {$this->report->id}");

                $fn = sha1(microtime());
                $final_filename = storage_path("app/book_list/" . date('Y-m-d') . " - {$this->report->id}/{$fn}.pdf");

                if (file_exists($final_filename)) {
                    unlink($final_filename);
                }

                $pdf->setPaper('a4', 'portrait')->save($final_filename);

            });
        } else {
            if (isset($this->request['order']) && $this->request['order'] != '') {
                $books = $query->orderBy($this->request['sort'], $this->request['order'])->get();
            } else {
                $books = $query->get();
            }

            $fn = sha1(microtime());

            $request = $this->request;
            $pdf    = \PDF::loadView('admin.reports.book-list', compact('books', 'request'));

            $pdf->output();
            $dom_pdf    = $pdf->getDomPDF();
            $canvas = $dom_pdf ->get_canvas();
            $canvas->page_text(590, 10, "{PAGE_NUM} of {PAGE_COUNT}", null, 6, array(0, 0, 0));

            Storage::makeDirectory('book_list/' . date('Y-m-d') . " - {$this->report->id}");
            $final_filename = storage_path("app/book_list/" . date('Y-m-d') . " - {$this->report->id}/{$fn}.pdf");

            if (file_exists($final_filename)) {
                unlink($final_filename);
            }

            $pdf->setPaper('a4', 'portrait')->save($final_filename);
        }

        $final_fn = storage_path("app/book_list/" . date('Y-m-d') . " - {$this->report->id}");
        $this->report->file_size = File::size($final_fn);
        $this->report->filename = $final_fn . ".zip";
        $this->report->status = Report::READY;
        $this->report->save();

        $this->_zipFile($final_fn);

        event(new ReportFinished($final_fn));
    }

    private function _zipFile($zipFile)
    {
        $zipname = basename($zipFile) . '.zip';
        $zip = new \ZipArchive();
        if ($zip->open(storage_path('app/book_list/') . $zipname, \ZipArchive::CREATE) === true) {
            foreach ( glob( $zipFile . '/*' ) as $fileName )
            {
                $file = basename( $fileName );
                $zip->addFile( $fileName, $file );
            }
            $zip->close();
        }

        $this->recursiveRemoveDirectory($zipFile);
    }

    private function recursiveRemoveDirectory($zipFile)
    {
        foreach(glob("{$zipFile}/*") as $file)
        {
            if(is_dir($file)) {
                $this->recursiveRemoveDirectory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($zipFile);
    }
}
