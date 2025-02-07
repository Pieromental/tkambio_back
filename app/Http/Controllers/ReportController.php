<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Utils\Response;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\User;
use App\Exceptions\GeneralException;
use App\Jobs\GenerateReportJob;
class ReportController extends Controller

{
    public function getReport($report_id)
    {
        try {
           
            $report = Report::where('report_id', $report_id)->first();

            if (!$report) {
                return response()->json(Response::error(404, 'Reporte no encontrado.', __FUNCTION__), 404);
            }

            if (!$report->report_link) {
                return response()->json(Response::error(400, 'El reporte aún no tiene un archivo generado.', __FUNCTION__), 400);
            }

            $filePath = storage_path('app/public/' . str_replace('/storage/', '', $report->report_link));

            if (!file_exists($filePath)) {
                return response()->json(Response::error(404, 'El archivo del reporte no fue encontrado.', __FUNCTION__), 404);
            }

            return response()->download($filePath, basename($filePath));
    
        } catch (GeneralException $e) {
            return response()->json(Response::error(500, $e->getMessage(), __FUNCTION__), 500);
        }
    }
    public function listReports()
    {
        try {
            $reports = Report::all()->map(function ($report) {
                return [
                    'report_id' => $report->report_id,
                    'user_id' => $report->user_id,
                    'title' => $report->title,
                    'description' => $report->description,
                    'report_link' => $report->report_link,
                    'active' => $report->active,
                    'created_by' => $report->created_by,
                    'updated_by' => $report->updated_by,
                    'created_at' => Carbon::parse($report->created_at)->format('d/m/Y'),
                    'updated_at' => $report->updated_at ? Carbon::parse($report->updated_at)->format('d/m/Y') : null,
                ];
            })->toArray();
            return response()->json(Response::success(200, 'Reporte en proceso.', $reports), 200);
    
        } catch (GeneralException $e) {
            return response()->json(Response::error(500, $e->getMessage(), __FUNCTION__), 500);
        }
    }
    public function generateReport(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'start_date' => 'required|date|before_or_equal:end_date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);
    
            $users = User::whereBetween('birth_date', [$request->start_date, $request->end_date])->get();
    
            if ($users->isEmpty()) {
                return response()->json(Response::error(404, 'No hay usuarios en el rango de fechas.', __FUNCTION__), 404);
            }
    
            
            $report = Report::create([
                'report_id' => Str::uuid(),
                'user_id' => auth()->id(),
                'title' => $request->title,
                'description' => $request->description ?? '',
                'report_link' => '', 
                'active' => 1,
                'created_by' => auth()->id(),
            ]);
    
          
            GenerateReportJob::dispatch($report->report_id, $users->toArray());
    
            return response()->json(Response::success(200, 'Reporte en proceso.', [
                'report_id' => $report->report_id
            ]), 200);
    
        } catch (GeneralException $e) {
            return response()->json(Response::error(500, $e->getMessage(), __FUNCTION__), 500);
        }
    }
}
