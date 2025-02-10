<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use App\Models\Report;

class ReportGenerated implements ShouldBroadcast
{
    use SerializesModels;

    public $report;


    public function __construct($reportId)
    {
        $this->report = Report::where('report_id', $reportId)->first();

    }

    public function broadcastOn()
    {
        return new Channel('reports');
    }
    public function broadcastAs()
    {
        return 'generated';
    }
    public function broadcastWith()
    {
        return [
            'report_id'   => $this->report->report_id,
            'user_id'     => $this->report->user_id,
            'title'       => $this->report->title,
            'description' => $this->report->description,
            'report_link' => $this->report->report_link,
            'active'      => $this->report->active,
            'created_by'  => $this->report->created_by,
            'updated_by'  => $this->report->updated_by,
            'created_at'  => Carbon::parse($this->report->created_at)->format('d/m/Y'),
            'updated_at'  => $this->report->updated_at ? Carbon::parse($this->report->updated_at)->format('d/m/Y') : null,
        ];
    }
}
