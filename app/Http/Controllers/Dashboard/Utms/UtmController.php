<?php

namespace App\Http\Controllers\Dashboard\Utms;

use App\Http\Controllers\Controller;
use App\Models\Utm;
use App\Traits\ApiResponse;
use App\Traits\AdminActivityLogger;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UtmController extends Controller
{
    use ApiResponse, AdminActivityLogger;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Utm::query()->orderBy('created_at', 'desc');

            return DataTables::of($query)
                ->filter(function ($query) use ($request) {
                    // Filter by utm_source
                    $utmSourceFilter = $request->input('utm_source_filter');
                    if (!empty($utmSourceFilter)) {
                        $query->where('utm_source', $utmSourceFilter);
                    }

                    $searchValue = $request->input('search.value');
                    if (!empty($searchValue)) {
                        $query->where(function ($builder) use ($searchValue) {
                            $builder->where('utm_source', 'like', '%' . $searchValue . '%')
                                ->orWhere('utm_medium', 'like', '%' . $searchValue . '%')
                                ->orWhere('utm_campaign', 'like', '%' . $searchValue . '%')
                                ->orWhere('utm_id', 'like', '%' . $searchValue . '%')
                                ->orWhere('utm_ads_set_id', 'like', '%' . $searchValue . '%')
                                ->orWhere('utm_ads_set_name', 'like', '%' . $searchValue . '%')
                                ->orWhere('ad_name', 'like', '%' . $searchValue . '%')
                                ->orWhere('ad_id', 'like', '%' . $searchValue . '%')
                                ->orWhere('id', 'like', '%' . $searchValue . '%');
                        });
                    }

                    // Ensure order by created_at desc (newest first) is always applied
                    $query->orderBy('created_at', 'desc');
                }, true)
                ->addColumn('utm_source', fn (Utm $utm) => '<span class="span_styles">' . e($utm->utm_source ?? '—') . '</span>')
                ->addColumn('utm_medium', fn (Utm $utm) => '<span class="span_styles">' . e($utm->utm_medium ?? '—') . '</span>')
                ->addColumn('utm_campaign', fn (Utm $utm) => '<span class="span_styles">' . e($utm->utm_campaign ?? '—') . '</span>')
                ->addColumn('utm_id', fn (Utm $utm) => '<span class="span_styles">' . e($utm->utm_id ?? '—') . '</span>')
                ->addColumn('utm_ads_set_id', fn (Utm $utm) => '<span class="span_styles">' . e($utm->utm_ads_set_id ?? '—') . '</span>')
                ->addColumn('utm_ads_set_name', fn (Utm $utm) => '<span class="span_styles">' . e($utm->utm_ads_set_name ?? '—') . '</span>')
                ->addColumn('ad_name', fn (Utm $utm) => '<span class="span_styles">' . e($utm->ad_name ?? '—') . '</span>')
                ->addColumn('ad_id', fn (Utm $utm) => '<span class="span_styles">' . e($utm->ad_id ?? '—') . '</span>')
                ->addColumn('created_at', fn (Utm $utm) => '<span class="span_styles">' . optional($utm->created_at)->format('Y-m-d H:i') . '</span>')
                ->addColumn('actions', function (Utm $utm) {
                    $buttons = '<div class="btns-table">';

                    $buttons .= '<a href="#" class="btn_styles delete_row" data-url="' . e(route('dashboard.utms.destroy', $utm)) . '" data-utm-id="' . e($utm->id) . '">
                                    <i class="fa fa-trash"></i>
                                    حذف
                                </a>';

                    $buttons .= '</div>';

                    return $buttons;
                })
                ->rawColumns(['utm_source', 'utm_medium', 'utm_campaign', 'utm_id', 'utm_ads_set_id', 'utm_ads_set_name', 'ad_name', 'ad_id', 'created_at', 'actions'])
                ->make(true);
        }

        // Get unique utm_source values for filter dropdown
        $utmSources = Utm::whereNotNull('utm_source')
            ->where('utm_source', '!=', '')
            ->distinct()
            ->orderBy('utm_source')
            ->pluck('utm_source')
            ->toArray();

        return view('dashboard.pages.utms.index', compact('utmSources'));
    }

    public function destroy(Request $request, $utm)
    {
        try {
            // Check if the model exists
            $utmModel = Utm::find($utm);

            // If model doesn't exist at all, it was already deleted
            if (!$utmModel) {
                if ($request->ajax() || $request->wantsJson()) {
                    return $this->deletedResponse('تم حذف بيانات UTM بنجاح');
                }
                return redirect()->route('dashboard.utms.index')->with('success', 'تم حذف بيانات UTM بنجاح');
            }

            // Delete the model
            $utmModel->delete();

            $this->logActivity('utm_deleted', [
                'utm_id' => $utmModel->id,
                'utm_source' => $utmModel->utm_source,
                'utm_medium' => $utmModel->utm_medium,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return $this->deletedResponse('تم حذف بيانات UTM بنجاح');
            }

            return redirect()->route('dashboard.utms.index')->with('success', 'تم حذف بيانات UTM بنجاح');
        } catch (ModelNotFoundException $e) {
            // Model was already deleted, return success
            if ($request->ajax() || $request->wantsJson()) {
                return $this->deletedResponse('تم حذف بيانات UTM بنجاح');
            }
            return redirect()->route('dashboard.utms.index')->with('success', 'تم حذف بيانات UTM بنجاح');
        } catch (Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return $this->errorResponse('حدث خطأ أثناء حذف بيانات UTM', 500);
            }

            return redirect()->route('dashboard.utms.index')
                ->with('error', 'حدث خطأ أثناء حذف بيانات UTM');
        }
    }
}

