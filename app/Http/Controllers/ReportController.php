<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Utils\Response;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\User;
use App\Exceptions\GeneralException;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Storage;
class ReportController extends Controller

{
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

            // Generar un nombre único para el archivo
            $fileName = 'reports/users_report_' . now()->format('YmdHis') . '.xlsx';

            // Guardar el archivo en storage/app/public/reports
            Excel::store(new UsersExport($users->toArray()), $fileName, 'public');

            // Obtener la URL pública del archivo
            $fileUrl = Storage::url($fileName);

            // Guardar el reporte en la base de datos con el enlace al archivo
            $report = Report::create([
                'report_id' => Str::uuid(),
                'user_id' => auth()->id(),
                'title' => $request->title,
                'description' => $request->description ?? '',
                'report_link' => $fileUrl, // Enlace al archivo
                'active' => 1,
                'created_by' => auth()->id(),
            ]);

            return response()->json(Response::success(200, 'Reporte generado exitosamente.', [
                'report_id' => $report->report_id,
                'report_link' => $fileUrl
            ]), 200);
        } catch (GeneralException $e) {
            return response()->json(Response::error(500, $e->getMessage(), __FUNCTION__), 500);
        }
    }
}
