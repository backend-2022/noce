<?php

namespace App\Http\Controllers\Dashboard\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Profile\UpdateProfileRequest;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Models\Admin;
use App\Traits\FileUploads;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Exception;

class ProfileController extends Controller
{
    use FileUploads;

    protected AdminRepositoryInterface $adminRepository;

    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function index(): View|RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        return view('dashboard.pages.profile.index', compact('admin'));
    }

    public function update(UpdateProfileRequest $request): JsonResponse|RedirectResponse
    {
        try {
            $admin = Auth::guard('admin')->user();
            $data = $request->validated();

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($admin->image) {
                    $this->deleteFile($admin->image, $admin->id, 'public');
                }

                $uploadedFile = $request->file('image');
                $path = $this->uploadFile($uploadedFile, $admin->id, Admin::STORAGE_DIR, 'public');
                $data['image'] = $path;
            }

            // Handle password update - if empty, remove it from data
            if (empty($data['password'])) {
                unset($data['password']);
            }

            // Remove password_confirmation from data
            unset($data['password_confirmation']);

            // Update admin
            $this->adminRepository->update($admin->id, $data);

            // Refresh admin data
            $admin->refresh();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم تحديث الملف الشخصي بنجاح',
                    'data' => [
                        'admin' => [
                            'id' => $admin->id,
                            'name' => $admin->name,
                            'email' => $admin->email,
                            'image' => $admin->image ? $this->getFileUrl($admin->image, $admin->id, 'public') : null,
                        ]
                    ]
                ]);
            }

            return redirect()->route('dashboard.profile.index')
                ->with('success', 'تم تحديث الملف الشخصي بنجاح');
        } catch (Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء تحديث الملف الشخصي',
                ], 500);
            }

            return redirect()->route('dashboard.profile.index')
                ->with('error', 'حدث خطأ أثناء تحديث الملف الشخصي');
        }
    }
}
