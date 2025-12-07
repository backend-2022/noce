<?php

namespace App\Http\Controllers\Dashboard\Services;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Service\CreateServiceRequest;
use App\Http\Requests\Dashboard\Service\UpdateServiceRequest;
use App\Models\Service;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ServiceController extends Controller
{
    use ApiResponse;

    protected ServiceRepositoryInterface $serviceRepository;

    public function __construct(ServiceRepositoryInterface $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Service::query()->orderBy('created_at', 'desc');

            return DataTables::of($query)
                ->filter(function ($query) use ($request) {
                    $searchValue = $request->input('search.value');
                    if (!empty($searchValue)) {
                        $query->where(function ($builder) use ($searchValue) {
                            $builder->where('name', 'like', '%' . $searchValue . '%')
                                ->orWhere('description', 'like', '%' . $searchValue . '%')
                                ->orWhere('id', 'like', '%' . $searchValue . '%');
                        });
                    }

                    // Ensure order by created_at desc (newest first) is always applied
                    $query->orderBy('created_at', 'desc');
                }, true)
                ->addColumn('name', fn (Service $service) => '<span class="span_styles">' . e($service->name) . '</span>')
                ->addColumn('description', fn (Service $service) => '<span class="span_styles">' . e($service->description ?? '-') . '</span>')
                ->addColumn('status', function (Service $service) {
                    $inputId = 'flexSwitchCheckChecked' . $service->id;

                    $form = '<form method="POST" action="' . e(route('dashboard.services.toggle-status', $service)) . '" style="display:inline;">'
                        . csrf_field()
                        . method_field('PATCH')
                        . '<div class="form-check form-switch">
                                <input class="form-check-input check_styles" type="checkbox" role="switch" id="' . e($inputId) . '" ' . ($service->is_active ? 'checked' : '') . ' onchange="this.form.submit()">
                            </div>
                        </form>';

                    return $form;
                })
                ->addColumn('actions', function (Service $service) {
                    $buttons = '<div class="btns-table">
                                    <a href="' . e(route('dashboard.services.edit', $service)) . '" class="btn_styles amendment">
                                        <i class="fa fa-edit"></i>
                                        تعديل
                                    </a>
                                    <a href="#" class="btn_styles delete_row" data-url="' . e(route('dashboard.services.destroy', $service)) . '" data-service-name="' . e($service->name) . '">
                                        <i class="fa fa-trash"></i>
                                        حذف
                                    </a>
                                </div>';

                    return $buttons;
                })
                ->rawColumns(['name', 'description', 'status', 'actions'])
                ->make(true);
        }

        return view('dashboard.pages.services.index');
    }

    public function create()
    {
        return view('dashboard.pages.services.create');
    }

    public function store(CreateServiceRequest $request)
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->has('is_active') ? true : false;

            $this->serviceRepository->create($data);

            return redirect()->route('dashboard.services.index')->with('success', 'تم إضافة الخدمة بنجاح');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة الخدمة');
        }
    }

    public function show(Service $service)
    {
        return abort(404);
    }

    public function edit(Service $service)
    {
        return view('dashboard.pages.services.edit', compact('service'));
    }

    public function update(UpdateServiceRequest $request, Service $service)
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->has('is_active') ? true : false;

            $this->serviceRepository->update($service->id, $data);

            return redirect()->route('dashboard.services.index')->with('success', 'تم تحديث الخدمة بنجاح');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث الخدمة');
        }
    }

    public function destroy(Request $request, Service $service)
    {
        try {
            $this->serviceRepository->delete($service->id);

            if ($request->ajax() || $request->wantsJson()) {
                return $this->deletedResponse('تم حذف الخدمة بنجاح');
            }

            return redirect()->route('dashboard.services.index')->with('success', 'تم حذف الخدمة بنجاح');
        } catch (Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return $this->errorResponse('حدث خطأ أثناء حذف الخدمة', 500);
            }

            return redirect()->route('dashboard.services.index')
                ->with('error', 'حدث خطأ أثناء حذف الخدمة');
        }
    }

    public function toggleStatus(Service $service)
    {
        try {
            $this->serviceRepository->update($service->id, ['is_active' => ! $service->is_active]);

            $status = $service->is_active ? 'تم إلغاء تفعيل الخدمة' : 'تم تفعيل الخدمة';

            return redirect()->route('dashboard.services.index')->with('success', $status);
        } catch (Exception $e) {
            return redirect()->route('dashboard.services.index')
                ->with('error', 'حدث خطأ أثناء تغيير حالة الخدمة');
        }
    }
}
