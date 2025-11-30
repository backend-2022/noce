<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض تقييم التاجر - جدولها</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            direction: rtl;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 1.5rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.8rem;
            color: #333;
        }

        .back-btn {
            background: #6c757d;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .back-btn:hover {
            background: #5a6268;
        }

        .review-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .review-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #e9ecef;
        }

        .merchant-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e9ecef;
        }

        .no-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 2rem;
            border: 3px solid #e9ecef;
        }

        .merchant-info h2 {
            color: #333;
            margin-bottom: 0.5rem;
        }

        .business-title {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .rating-display {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .rating-stars {
            display: flex;
            gap: 2px;
        }

        .star {
            color: #ffc107;
            font-size: 1.5rem;
        }

        .star.empty {
            color: #e9ecef;
        }

        .rating-text {
            font-weight: bold;
            color: #333;
        }

        .review-content {
            margin-bottom: 2rem;
        }

        .review-text {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            border-right: 4px solid #667eea;
            font-size: 1.1rem;
            line-height: 1.6;
            color: #333;
        }

        .review-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .meta-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
        }

        .meta-label {
            font-size: 0.875rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .meta-value {
            font-weight: bold;
            color: #333;
            font-size: 1.1rem;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #e9ecef;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-edit {
            background: #ffc107;
            color: #212529;
        }

        .btn-edit:hover {
            background: #e0a800;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .btn-toggle {
            background: #6c757d;
            color: white;
        }

        .btn-toggle:hover {
            background: #5a6268;
        }

        .btn-back {
            background: #17a2b8;
            color: white;
        }

        .btn-back:hover {
            background: #138496;
        }

        .created-date {
            color: #6c757d;
            font-size: 0.875rem;
            margin-top: 1rem;
            text-align: center;
        }

        @media (max-width: 768px) {
            .container {
                margin: 1rem auto;
                padding: 0 0.5rem;
            }

            .review-container {
                padding: 1rem;
            }

            .review-header {
                flex-direction: column;
                text-align: center;
            }

            .review-meta {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>جدولها - لوحة التحكم</h1>
        <div class="user-info">
            <span>مرحباً، {{ auth()->user()->name ?? 'المدير' }}</span>
            <a href="#" class="logout-btn">تسجيل الخروج</a>
        </div>
    </div>

    <div class="container">
        <div class="page-header">
            <h2 class="page-title">عرض تقييم التاجر</h2>
            <a href="{{ route('dashboard.merchant-reviews.index') }}" class="back-btn">العودة للقائمة</a>
        </div>

        <div class="review-container">
            <div class="review-header">
                @if($merchantReview->image)
                    <img src="{{ $merchantReview->image_url }}" alt="صورة التاجر" class="merchant-image">
                @else
                    <div class="no-image">👤</div>
                @endif

                <div class="merchant-info">
                    <h2>{{ $merchantReview->merchant_name }}</h2>
                    @if($merchantReview->business_title)
                        <div class="business-title">{{ $merchantReview->business_title }}</div>
                    @endif
                    <div class="rating-display">
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $merchantReview->rating ? '' : 'empty' }}">★</span>
                            @endfor
                        </div>
                        <span class="rating-text">{{ $merchantReview->rating }}/5</span>
                    </div>
                </div>
            </div>

            <div class="review-content">
                <h3 style="margin-bottom: 1rem; color: #333;">نص التقييم:</h3>
                <div class="review-text">
                    {{ $merchantReview->review_text }}
                </div>
            </div>

            <div class="review-meta">
                <div class="meta-item">
                    <div class="meta-label">الحالة</div>
                    <div class="meta-value">
                        <span class="status-badge {{ $merchantReview->is_active ? 'status-active' : 'status-inactive' }}">
                            {{ $merchantReview->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </div>
                </div>

                <div class="meta-item">
                    <div class="meta-label">تاريخ الإنشاء</div>
                    <div class="meta-value">{{ $merchantReview->created_at->format('Y/m/d') }}</div>
                </div>

                <div class="meta-item">
                    <div class="meta-label">آخر تحديث</div>
                    <div class="meta-value">{{ $merchantReview->updated_at->format('Y/m/d') }}</div>
                </div>
            </div>

            <div class="actions">
                <a href="{{ route('dashboard.merchant-reviews.edit', $merchantReview) }}" class="btn btn-edit">تعديل</a>

                <form action="{{ route('dashboard.merchant-reviews.toggle-status', $merchantReview) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-toggle">
                        {{ $merchantReview->is_active ? 'إلغاء تفعيل' : 'تفعيل' }}
                    </button>
                </form>

                <form action="{{ route('dashboard.merchant-reviews.destroy', $merchantReview) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا التقييم؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-delete">حذف</button>
                </form>
            </div>

            <div class="created-date">
                تم إنشاء هذا التقييم في {{ $merchantReview->created_at->format('Y/m/d H:i') }}
            </div>
        </div>
    </div>
</body>
</html>
