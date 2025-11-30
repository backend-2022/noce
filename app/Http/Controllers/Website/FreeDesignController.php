<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\Website\StoreFreeDesignRequest;
use App\Repositories\Interfaces\FreeDesignRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Exception;

class FreeDesignController extends Controller
{
    use ApiResponse;

    protected FreeDesignRepositoryInterface $freeDesignRepository;

    public function __construct(FreeDesignRepositoryInterface $freeDesignRepository)
    {
        $this->freeDesignRepository = $freeDesignRepository;
    }

    public function store(StoreFreeDesignRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            
            // Normalize phone number (remove leading 0 if present)
            if (isset($data['phone']) && str_starts_with($data['phone'], '0')) {
                $data['phone'] = substr($data['phone'], 1);
            }
            
            // Set default status to pending
            $data['status'] = 'pending';

            // Create the free design request using repository
            $freeDesign = $this->freeDesignRepository->create($data);

            return $this->successResponse(
                ['id' => $freeDesign->id],
                'تم إرسال طلبك بنجاح! سنتواصل معك قريباً',
                201
            );
        } catch (Exception $e) {
            return $this->serverErrorResponse('حدث خطأ أثناء إرسال الطلب. يرجى المحاولة مرة أخرى.');
        }
    }
}

