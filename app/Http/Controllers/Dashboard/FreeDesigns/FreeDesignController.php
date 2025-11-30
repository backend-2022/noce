<?php

namespace App\Http\Controllers\Dashboard\FreeDesigns;

use App\Http\Controllers\Controller;
use App\Models\FreeDesign;
use App\Repositories\Interfaces\FreeDesignRepositoryInterface;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FreeDesignController extends Controller
{
    use ApiResponse;

    protected FreeDesignRepositoryInterface $freeDesignRepository;

    public function __construct(FreeDesignRepositoryInterface $freeDesignRepository)
    {
        $this->freeDesignRepository = $freeDesignRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->freeDesignRepository->buildQueryWithRelations()
                ->orderBy('updated_at', 'desc');

            return DataTables::of($query)
                ->filter(function ($query) use ($request) {
                    $searchValue = $request->input('search.value');
                    if (!empty($searchValue)) {
                        $query->where(function ($builder) use ($searchValue) {
                            $builder->where('name', 'like', '%' . $searchValue . '%')
                                ->orWhere('email', 'like', '%' . $searchValue . '%')
                                ->orWhere('phone', 'like', '%' . $searchValue . '%')
                                ->orWhere('id', 'like', '%' . $searchValue . '%');
                        });
                    }
                }, true)
                ->addColumn('name', fn (FreeDesign $freeDesign) => '<span class="span_styles">' . e($freeDesign->name) . '</span>')
                ->addColumn('email', fn (FreeDesign $freeDesign) => '<span class="span_styles">' . e($freeDesign->email) . '</span>')
                ->addColumn('phone', fn (FreeDesign $freeDesign) => '<span class="span_styles">' . e($freeDesign->phone) . '</span>')
                ->addColumn('city', function (FreeDesign $freeDesign) {
                    return '<span class="span_styles">' . ($freeDesign->city ? e($freeDesign->city->name) : '—') . '</span>';
                })
                ->addColumn('service', function (FreeDesign $freeDesign) {
                    return '<span class="span_styles">' . ($freeDesign->service ? e($freeDesign->service->name) : '—') . '</span>';
                })
                ->addColumn('created_at', fn (FreeDesign $freeDesign) => '<span class="span_styles">' . optional($freeDesign->created_at)->format('Y-m-d H:i') . '</span>')
                ->addColumn('actions', function (FreeDesign $freeDesign) {
                    $buttons = '<div class="btns-table">
                                    <a href="#" class="btn_styles delete_row" data-url="' . e(route('dashboard.free-designs.destroy', $freeDesign)) . '" data-design-name="' . e($freeDesign->name) . '">
                                        <i class="fa fa-trash"></i>
                                        حذف
                                    </a>
                                </div>';

                    return $buttons;
                })
                ->rawColumns(['name', 'email', 'phone', 'city', 'service', 'created_at', 'actions'])
                ->make(true);
        }

        return view('dashboard.pages.free-designs.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, FreeDesign $freeDesign)
    {
        try {
            $this->freeDesignRepository->delete($freeDesign->id);

            if ($request->ajax() || $request->wantsJson()) {
                return $this->deletedResponse('تم حذف طلب التصميم المجاني بنجاح');
            }

            return redirect()->route('dashboard.free-designs.index')->with('success', 'تم حذف طلب التصميم المجاني بنجاح');
        } catch (Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return $this->errorResponse('حدث خطأ أثناء حذف طلب التصميم المجاني', 500);
            }

            return redirect()->route('dashboard.free-designs.index')
                ->with('error', 'حدث خطأ أثناء حذف طلب التصميم المجاني');
        }
    }
}
