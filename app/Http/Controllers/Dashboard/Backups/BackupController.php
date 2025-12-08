<?php

namespace App\Http\Controllers\Dashboard\Backups;

use App\Http\Controllers\Controller;
use App\Models\Backup;
use App\Traits\ApiResponse;
use App\Traits\FileUploads;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BackupController extends Controller
{
    use ApiResponse, FileUploads;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Backup::query()->orderBy('created_at', 'desc');

            return DataTables::of($query)
                ->filter(function ($query) use ($request) {
                    $searchValue = $request->input('search.value');
                    if (!empty($searchValue)) {
                        $query->where(function ($builder) use ($searchValue) {
                            $builder->where('name', 'like', '%' . $searchValue . '%')
                                ->orWhere('id', 'like', '%' . $searchValue . '%');
                        });
                    }

                    // Ensure order by created_at desc (newest first) is always applied
                    $query->orderBy('created_at', 'desc');
                }, true)
                ->addColumn('name', fn (Backup $backup) => '<span class="span_styles">' . e($backup->name) . '</span>')
                ->addColumn('created_at', fn (Backup $backup) => '<span class="span_styles">' . optional($backup->created_at)->format('Y-m-d H:i') . '</span>')
                ->addColumn('actions', function (Backup $backup) {
                    $totalBackups = Backup::count();
                    $canDelete = $totalBackups > 1;

                    $buttons = '<div class="btns-table">
                                    <a href="' . e(route('dashboard.backups.download', $backup)) . '" class="btn_styles amendment">
                                        <i class="fa fa-download"></i>
                                        تحميل
                                    </a>';

                    if ($canDelete) {
                        $buttons .= '<a href="#" class="btn_styles delete_row" style="background-color: #dc3545; color: white;" data-url="' . e(route('dashboard.backups.destroy', $backup)) . '" data-backup-name="' . e($backup->name) . '">
                                        <i class="fa fa-trash"></i>
                                        حذف
                                    </a>';
                    } else {
                        $buttons .= '<a href="#" class="btn_styles" style="background-color: #dc3545; color: white; opacity: 0.5; cursor: not-allowed;" title="يجب أن يكون هناك نسخة احتياطية واحدة على الأقل">
                                        <i class="fa fa-trash"></i>
                                        حذف
                                    </a>';
                    }

                    $buttons .= '</div>';

                    return $buttons;
                })
                ->rawColumns(['name', 'created_at', 'actions'])
                ->make(true);
        }

        return view('dashboard.pages.backups.index');
    }

    public function download(Backup $backup)
    {
        try {
            $disk = Storage::disk('local');

            if (!$disk->exists($backup->path)) {
                return redirect()->route('dashboard.backups.index')
                    ->with('error', 'ملف النسخة الاحتياطية غير موجود');
            }

            return $this->downloadFile($backup->path, $backup->name, 'local');
        } catch (Exception $e) {
            return redirect()->route('dashboard.backups.index')
                ->with('error', 'حدث خطأ أثناء تحميل النسخة الاحتياطية');
        }
    }

    public function destroy(Request $request, $backup)
    {
        try {
            // Check if the model exists
            $backupModel = Backup::find($backup);

            // If model doesn't exist, it was already deleted
            if (!$backupModel) {
                if ($request->ajax() || $request->wantsJson()) {
                    return $this->deletedResponse('تم حذف النسخة الاحتياطية بنجاح');
                }
                return redirect()->route('dashboard.backups.index')->with('success', 'تم حذف النسخة الاحتياطية بنجاح');
            }

            // Check if this is the last backup - prevent deletion
            $totalBackups = Backup::count();
            if ($totalBackups <= 1) {
                $errorMessage = 'لا يمكن حذف آخر نسخة احتياطية. يجب أن يكون هناك نسخة احتياطية واحدة على الأقل.';
                if ($request->ajax() || $request->wantsJson()) {
                    return $this->errorResponse($errorMessage, 422);
                }
                return redirect()->route('dashboard.backups.index')
                    ->with('error', $errorMessage);
            }

            $disk = Storage::disk('local');

            // Delete the backup file from storage if it exists
            if ($disk->exists($backupModel->path)) {
                $disk->delete($backupModel->path);
            }

            // Delete the backup record from database
            $backupModel->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return $this->deletedResponse('تم حذف النسخة الاحتياطية بنجاح');
            }

            return redirect()->route('dashboard.backups.index')->with('success', 'تم حذف النسخة الاحتياطية بنجاح');
        } catch (ModelNotFoundException $e) {
            // Model was already deleted, return success
            if ($request->ajax() || $request->wantsJson()) {
                return $this->deletedResponse('تم حذف النسخة الاحتياطية بنجاح');
            }
            return redirect()->route('dashboard.backups.index')->with('success', 'تم حذف النسخة الاحتياطية بنجاح');
        } catch (Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return $this->errorResponse('حدث خطأ أثناء حذف النسخة الاحتياطية', 500);
            }

            return redirect()->route('dashboard.backups.index')
                ->with('error', 'حدث خطأ أثناء حذف النسخة الاحتياطية');
        }
    }
}
