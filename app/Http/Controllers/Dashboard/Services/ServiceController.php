<?php

namespace App\Http\Controllers\Dashboard\Services;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Service\CreateServiceRequest;
use App\Http\Requests\Dashboard\Service\UpdateServiceRequest;
use App\Models\Service;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use App\Traits\ApiResponse;
use App\Traits\AdminActivityLogger;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ServiceController extends Controller
{
    use ApiResponse, AdminActivityLogger;

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
                    $buttons = '<div class="btns-table">';

                    $buttons .= '<a href="' . e(route('dashboard.services.edit', $service)) . '" class="btn_styles amendment">
                                    <i class="fa fa-edit"></i>
                                    تعديل
                                </a>';

                    $buttons .= '<a href="#" class="btn_styles delete_row" data-url="' . e(route('dashboard.services.destroy', $service)) . '" data-service-name="' . e($service->name) . '">
                                    <i class="fa fa-trash"></i>
                                    حذف
                                </a>';

                    $buttons .= '</div>';

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

            $service = $this->serviceRepository->create($data);
            $this->logActivity('service_created', [
                'service_id' => $service->id,
                'service_name' => $service->name,
            ]);
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
            $service->refresh();
            $this->logActivity('service_updated', [
                'service_id' => $service->id,
                'service_name' => $service->name,
            ]);
            return redirect()->route('dashboard.services.index')->with('success', 'تم تحديث الخدمة بنجاح');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث الخدمة');
        }
    }

    public function destroy(Request $request, $service)
    {

        try {
            // Check if the model exists (including soft-deleted ones)
            // Handle case where user clicks delete multiple times quickly
            // $service parameter is the ID from route (not using route model binding to avoid errors on already-deleted records)
            $serviceModel = Service::withTrashed()->find($service);

            // If model doesn't exist at all, it was already deleted
            if (!$serviceModel) {
                if ($request->ajax() || $request->wantsJson()) {
                    return $this->deletedResponse('تم حذف الخدمة بنجاح');
                }
                return redirect()->route('dashboard.services.index')->with('success', 'تم حذف الخدمة بنجاح');
            }

            // If already soft-deleted, return success
            if ($serviceModel->trashed()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return $this->deletedResponse('تم حذف الخدمة بنجاح');
                }
                return redirect()->route('dashboard.services.index')->with('success', 'تم حذف الخدمة بنجاح');
            }

            // Delete the model
            $this->serviceRepository->delete($serviceModel->id);

            $this->logActivity('service_deleted', [
                'service_id' => $serviceModel->id,
                'service_name' => $serviceModel->name,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return $this->deletedResponse('تم حذف الخدمة بنجاح');
            }

            return redirect()->route('dashboard.services.index')->with('success', 'تم حذف الخدمة بنجاح');
        } catch (ModelNotFoundException $e) {
            // Model was already deleted, return success
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
            $oldStatus = $service->is_active;
            $this->serviceRepository->update($service->id, ['is_active' => ! $service->is_active]);
            $service->refresh();

            $this->logActivity('service_status_toggled', [
                'service_id' => $service->id,
                'service_name' => $service->name,
                'old_status' => $oldStatus,
                'new_status' => $service->is_active,
            ]);

            $status = $service->is_active ? 'تم إلغاء تفعيل الخدمة' : 'تم تفعيل الخدمة';

            return redirect()->route('dashboard.services.index')->with('success', $status);
        } catch (Exception $e) {
            return redirect()->route('dashboard.services.index')
                ->with('error', 'حدث خطأ أثناء تغيير حالة الخدمة');
        }
    }
}
