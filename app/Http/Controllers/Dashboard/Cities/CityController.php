<?php

namespace App\Http\Controllers\Dashboard\Cities;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\City\CreateCityRequest;
use App\Http\Requests\Dashboard\City\UpdateCityRequest;
use App\Models\City;
use App\Repositories\Interfaces\CityRepositoryInterface;
use App\Traits\ApiResponse;
use App\Traits\AdminActivityLogger;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CityController extends Controller
{
    use ApiResponse, AdminActivityLogger;

    protected CityRepositoryInterface $cityRepository;

    public function __construct(CityRepositoryInterface $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = City::query()->orderBy('created_at', 'desc');

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
                ->addColumn('name', fn (City $city) => '<span class="span_styles">' . e($city->name) . '</span>')
                ->addColumn('created_at', fn (City $city) => '<span class="span_styles">' . optional($city->created_at)->format('Y-m-d H:i') . '</span>')
                ->addColumn('status', function (City $city) {
                    $user = auth('admin')->user();

                    // Only show toggle if user has update permission
                    if (!$user->can('cities.update')) {
                        return '<span class="span_styles">' . ($city->is_active ? 'مفعل' : 'غير مفعل') . '</span>';
                    }

                    $inputId = 'flexSwitchCheckChecked' . $city->id;

                    $form = '<form method="POST" action="' . e(route('dashboard.cities.toggle-status', $city)) . '" style="display:inline;">'
                        . csrf_field()
                        . method_field('PATCH')
                        . '<div class="form-check form-switch">
                                <input class="form-check-input check_styles" type="checkbox" role="switch" id="' . e($inputId) . '" ' . ($city->is_active ? 'checked' : '') . ' onchange="this.form.submit()">
                            </div>
                        </form>';

                    return $form;
                })
                ->addColumn('actions', function (City $city) {
                    $user = auth('admin')->user();
                    $buttons = '<div class="btns-table">';

                    if ($user->can('cities.update')) {
                        $buttons .= '<a href="' . e(route('dashboard.cities.edit', $city)) . '" class="btn_styles amendment">
                                        <i class="fa fa-edit"></i>
                                        تعديل
                                    </a>';
                    }

                    if ($user->can('cities.delete')) {
                        $buttons .= '<a href="#" class="btn_styles delete_row" data-url="' . e(route('dashboard.cities.destroy', $city)) . '" data-city-name="' . e($city->name) . '">
                                        <i class="fa fa-trash"></i>
                                        حذف
                                    </a>';
                    }

                    $buttons .= '</div>';

                    return $buttons;
                })
                ->rawColumns(['name', 'created_at', 'status', 'actions'])
                ->make(true);
        }

        return view('dashboard.pages.cities.index');
    }

    public function create()
    {

        return view('dashboard.pages.cities.create');
    }

    public function store(CreateCityRequest $request)
    {

        try {
            $data = $request->validated();
            $data['is_active'] = $request->has('is_active') ? true : false;

            $city = $this->cityRepository->create($data);
            $this->logActivity('city_created', [
                'city_id' => $city->id,
                'city_name' => $city->name,
            ]);
            return redirect()->route('dashboard.cities.index')->with('success', 'تم إضافة المدينة بنجاح');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة المدينة');
        }
    }

    public function show(City $city)
    {
        return abort(404);
    }

    public function edit(City $city)
    {

        return view('dashboard.pages.cities.edit', compact('city'));
    }

    public function update(UpdateCityRequest $request, City $city)
    {

        try {
            $data = $request->validated();
            $data['is_active'] = $request->has('is_active') ? true : false;

            $this->cityRepository->update($city->id, $data);
            $city->refresh();
            $this->logActivity('city_updated', [
                'city_id' => $city->id,
                'city_name' => $city->name,
            ]);
            return redirect()->route('dashboard.cities.index')->with('success', 'تم تحديث المدينة بنجاح');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث المدينة');
        }
    }

    public function destroy(Request $request, $city)
    {

        try {
            // Check if the model exists (including soft-deleted ones)
            // Handle case where user clicks delete multiple times quickly
            // $city parameter is the ID from route (not using route model binding to avoid errors on already-deleted records)
            $cityModel = City::withTrashed()->find($city);

            // If model doesn't exist at all, it was already deleted
            if (!$cityModel) {
                if ($request->ajax() || $request->wantsJson()) {
                    return $this->deletedResponse('تم حذف المدينة بنجاح');
                }
                return redirect()->route('dashboard.cities.index')->with('success', 'تم حذف المدينة بنجاح');
            }

            // If already soft-deleted, return success
            if ($cityModel->trashed()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return $this->deletedResponse('تم حذف المدينة بنجاح');
                }
                return redirect()->route('dashboard.cities.index')->with('success', 'تم حذف المدينة بنجاح');
            }

            // Delete the model
            $this->cityRepository->delete($cityModel->id);

            $this->logActivity('city_deleted', [
                'city_id' => $cityModel->id,
                'city_name' => $cityModel->name,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return $this->deletedResponse('تم حذف المدينة بنجاح');
            }

            return redirect()->route('dashboard.cities.index')->with('success', 'تم حذف المدينة بنجاح');
        } catch (ModelNotFoundException $e) {
            // Model was already deleted, return success
            if ($request->ajax() || $request->wantsJson()) {
                return $this->deletedResponse('تم حذف المدينة بنجاح');
            }
            return redirect()->route('dashboard.cities.index')->with('success', 'تم حذف المدينة بنجاح');
        } catch (Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return $this->errorResponse('حدث خطأ أثناء حذف المدينة', 500);
            }

            return redirect()->route('dashboard.cities.index')
                ->with('error', 'حدث خطأ أثناء حذف المدينة');
        }
    }

    public function toggleStatus(City $city)
    {
        try {
            $oldStatus = $city->is_active;
            $this->cityRepository->update($city->id, ['is_active' => ! $city->is_active]);
            $city->refresh();

            $this->logActivity('city_status_toggled', [
                'city_id' => $city->id,
                'city_name' => $city->name,
                'old_status' => $oldStatus,
                'new_status' => $city->is_active,
            ]);

            $status = $city->is_active ? 'تم إلغاء تفعيل المدينة' : 'تم تفعيل المدينة';

            return redirect()->route('dashboard.cities.index')->with('success', $status);
        } catch (Exception $e) {
            return redirect()->route('dashboard.cities.index')
                ->with('error', 'حدث خطأ أثناء تغيير حالة المدينة');
        }
    }
}
