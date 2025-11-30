<?php

namespace App\Http\Controllers\Dashboard\Admins;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Http\Requests\Dashboard\Admin\CreateAdminRequest;
use App\Http\Requests\Dashboard\Admin\UpdateAdminRequest;
use App\Traits\FileUploads;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Admin;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    use FileUploads, ApiResponse;

    protected AdminRepositoryInterface $adminRepository;

    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Admin::query();

            return DataTables::of($query)
                ->filter(function ($query) use ($request) {
                    $searchValue = $request->input('search.value');
                    if (!empty($searchValue)) {
                        $query->where(function ($builder) use ($searchValue) {
                            $builder->where('name', 'like', '%' . $searchValue . '%')
                                ->orWhere('email', 'like', '%' . $searchValue . '%')
                                ->orWhere('id', 'like', '%' . $searchValue . '%');
                        });
                    }
                }, true)
                ->addColumn('select', function (Admin $admin) {
                    $inputId = 'flexCheckDefault' . $admin->id;
                    return '<div class="form-check">
                                <input class="form-check-input row-select" type="checkbox" value="' . e($admin->id) . '" id="' . e($inputId) . '">
                                <label for="' . e($inputId) . '"></label>
                            </div>';
                })
                ->addColumn('image', function (Admin $admin) {
                    $imageUrl = getFileFullUrl($admin->image, null, 'public', 'user.png');
                    return '<div class="product_details">
                                <img src="' . e($imageUrl) . '" alt="' . e($admin->name) . '" style="width:50px;height:50px;border-radius:50%;object-fit:cover;">
                            </div>';
                })
                ->addColumn('name', fn (Admin $admin) => '<span class="span_styles">' . e($admin->name) . '</span>')
                ->addColumn('email', fn (Admin $admin) => '<span class="span_styles">' . e($admin->email) . '</span>')
                ->addColumn('created_at', fn (Admin $admin) => '<span class="span_styles">' . optional($admin->created_at)->format('Y-m-d H:i') . '</span>')
                ->addColumn('status', function (Admin $admin) {
                    $isSelf = auth('admin')->id() === $admin->id;
                    $inputId = 'flexSwitchCheckChecked' . $admin->id;

                    if ($isSelf) {
                        return '<div class="form-check form-switch">
                                    <input class="form-check-input check_styles" type="checkbox" role="switch" id="' . e($inputId) . '" checked disabled>
                                </div>';
                    }

                    $form = '<form method="POST" action="' . e(route('dashboard.admins.toggle-status', $admin)) . '" style="display:inline;">'
                        . csrf_field()
                        . method_field('PATCH')
                        . '<div class="form-check form-switch">
                                <input class="form-check-input check_styles" type="checkbox" role="switch" id="' . e($inputId) . '" ' . ($admin->is_active ? 'checked' : '') . ' onchange="this.form.submit()">
                            </div>
                        </form>';

                    return $form;
                })
                ->addColumn('actions', function (Admin $admin) {
                    $buttons = '<div class="btns-table">
                                    <a href="' . e(route('dashboard.admins.edit', $admin)) . '" class="btn_styles amendment">
                                        <i class="fa fa-edit"></i>
                                        تعديل
                                    </a>';

                    if (auth('admin')->id() !== $admin->id) {
                        $buttons .= '<a href="#" class="btn_styles delete_row" data-url="' . e(route('dashboard.admins.destroy', $admin)) . '" data-admin-name="' . e($admin->name) . '">
                                        <i class="fa fa-trash"></i>
                                        حذف
                                    </a>';
                    } else {
                        $buttons .= '<span class="btn_styles delete_row" style="cursor:not-allowed;opacity:0.5;">
                                        <i class="fa fa-trash"></i>
                                        حذف
                                    </span>';
                    }

                    return $buttons . '</div>';
                })
                ->rawColumns(['select', 'image', 'name', 'email', 'created_at', 'status', 'actions'])
                ->make(true);
        }

        return view('dashboard.pages.admins.index');
    }

    public function create()
    {
        return view('dashboard.pages.admins.create');
    }

    public function store(CreateAdminRequest $request)
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->has('is_active') ? true : false;

            // Handle image upload
            if ($request->hasFile('image')) {
                $uploadedFile = $request->file('image');
                $path = $this->uploadFile($uploadedFile, null, Admin::STORAGE_DIR, 'public');
                $data['image'] = $path;
            }

            $this->adminRepository->create($data);
            return redirect()->route('dashboard.admins.index')->with('success', 'تم إضافة المشرف بنجاح');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة المشرف');
        }
    }

    public function show(Admin $admin)
    {
        return abort(404);
    }

    public function edit(Admin $admin)
    {
        $imageUrl = $this->getFileUrl($admin->image, null, 'public', 'white_img.png');
        return view('dashboard.pages.admins.edit', compact('admin', 'imageUrl'));
    }

    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->has('is_active') ? true : false;

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($admin->image) {
                    $this->deleteFile($admin->image, null, 'public');
                }

                $uploadedFile = $request->file('image');
                $path = $this->uploadFile($uploadedFile, null, Admin::STORAGE_DIR, 'public');
                $data['image'] = $path;
            }

            // Remove password fields if not provided
            if (empty($data['password'])) {
                unset($data['password']);
                unset($data['password_confirmation']);
            }

            $this->adminRepository->update($admin->id, $data);
            return redirect()->route('dashboard.admins.index')->with('success', 'تم تحديث المشرف بنجاح');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث المشرف');
        }
    }

    public function destroy(Request $request, Admin $admin)
    {
        try {
            // Prevent deleting self
            if ($admin->id === auth('admin')->id()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return $this->errorResponse('لا يمكنك حذف حسابك الخاص', 403);
                }
                return redirect()->route('dashboard.admins.index')
                    ->with('error', 'لا يمكنك حذف حسابك الخاص');
            }

            // Delete image if exists
            if ($admin->image) {
                $this->deleteFile($admin->image, null, 'public');
            }

            $this->adminRepository->delete($admin->id);

            if ($request->ajax() || $request->wantsJson()) {
                return $this->deletedResponse('تم حذف المشرف بنجاح');
            }

            return redirect()->route('dashboard.admins.index')->with('success', 'تم حذف المشرف بنجاح');
        } catch (Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return $this->errorResponse('حدث خطأ أثناء حذف المشرف', 500);
            }
            return redirect()->route('dashboard.admins.index')
                ->with('error', 'حدث خطأ أثناء حذف المشرف');
        }
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return $request->ajax() || $request->wantsJson()
                ? $this->errorResponse('يرجى اختيار مشرف واحد على الأقل', 422)
                : redirect()->route('dashboard.admins.index')->with('error', 'يرجى اختيار مشرف واحد على الأقل');
        }

        $ids = collect($ids)
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->toArray();

        if (empty($ids)) {
            return $request->ajax() || $request->wantsJson()
                ? $this->errorResponse('يرجى اختيار مشرفين صالحين للحذف', 422)
                : redirect()->route('dashboard.admins.index')->with('error', 'يرجى اختيار مشرفين صالحين للحذف');
        }

        $currentAdminId = auth('admin')->id();
        if (in_array($currentAdminId, $ids, true)) {
            return $request->ajax() || $request->wantsJson()
                ? $this->errorResponse('لا يمكنك حذف حسابك الخاص', 403)
                : redirect()->route('dashboard.admins.index')->with('error', 'لا يمكنك حذف حسابك الخاص');
        }

        $admins = Admin::whereIn('id', $ids)->get();

        if ($admins->isEmpty()) {
            return $request->ajax() || $request->wantsJson()
                ? $this->errorResponse('لم يتم العثور على المشرفين المحددين', 404)
                : redirect()->route('dashboard.admins.index')->with('error', 'لم يتم العثور على المشرفين المحددين');
        }

        foreach ($admins as $admin) {
            if ($admin->image) {
                $this->deleteFile($admin->image, null, 'public');
            }

            $this->adminRepository->delete($admin->id);
        }

        $message = 'تم حذف ' . $admins->count() . ' من المشرفين بنجاح';

        if ($request->ajax() || $request->wantsJson()) {
            return $this->deletedResponse($message);
        }

        return redirect()->route('dashboard.admins.index')->with('success', $message);
    }

    public function toggleStatus(Admin $admin)
    {
        try {
            // Prevent deactivating self
            if ($admin->id === auth('admin')->id()) {
                return redirect()->route('dashboard.admins.index')
                    ->with('error', 'لا يمكنك تعطيل حسابك الخاص');
            }

            $this->adminRepository->update($admin->id, ['is_active' => !$admin->is_active]);

            $status = $admin->is_active ? 'تم إلغاء تفعيل المشرف' : 'تم تفعيل المشرف';
            return redirect()->route('dashboard.admins.index')->with('success', $status);
        } catch (Exception $e) {
            return redirect()->route('dashboard.admins.index')
                ->with('error', 'حدث خطأ أثناء تغيير حالة المشرف');
        }
    }
}
