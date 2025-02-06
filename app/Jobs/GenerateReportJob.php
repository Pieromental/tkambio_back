<?php

namespace App\Jobs;

use App\Models\Report;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GenerateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reportId;
    protected $users;

    public function __construct($reportId, $users)
    {
        $this->reportId = $reportId;
        $this->users = $users;
    }

    public function handle()
    {
        try {
       
            $fileName = 'reports/users_report_' . now()->format('YmdHis') . '.xlsx';
        
            Excel::store(new UsersExport($this->users), $fileName, 'public');
        
            $fileUrl = Storage::url($fileName);

            Report::where('report_id', $this->reportId)->update([
                'report_link' => $fileUrl
            ]);
        } catch (\Exception $e) {
            Log::error("Error generando el reporte: " . $e->getMessage());
        }
    }
}