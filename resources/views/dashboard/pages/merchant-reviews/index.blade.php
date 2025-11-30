<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة تقييمات التجار - جدولها</title>
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
            max-width: 1200px;
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

        .add-btn {
            background: #28a745;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .add-btn:hover {
            background: #218838;
        }

        .reviews-table {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: #f8f9fa;
            padding: 1rem;
            text-align: right;
            font-weight: bold;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }

        .table tr:hover {
            background: #f8f9fa;
        }

        .rating {
            display: flex;
            gap: 2px;
        }

        .star {
            color: #ffc107;
            font-size: 1.2rem;
        }

        .star.empty {
            color: #e9ecef;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.875rem;
            font-weight: bold;
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
            gap: 0.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-view {
            background: #17a2b8;
            color: white;
        }

        .btn-view:hover {
            background: #138496;
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

        .review-text {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .merchant-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .no-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 1.2rem;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }

        .empty-state h3 {
            margin-bottom: 1rem;
            color: #495057;
        }

        @media (max-width: 768px) {
            .container {
                margin: 1rem auto;
                padding: 0 0.5rem;
            }

            .page-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .table {
                font-size: 0.875rem;
            }

            .table th,
            .table td {
                padding: 0.5rem;
            }

            .actions {
                flex-direction: column;
            }

            .review-text {
                max-width: 200px;
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
            <h2 class="page-title">إدارة تقييمات التجار</h2>
            <a href="{{ route('dashboard.merchant-reviews.create') }}" class="add-btn">إضافة تقييم جديد</a>
        </div>

        <div class="reviews-table">
            @if($merchantReviews->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>الصورة</th>
                            <th>اسم التاجر</th>
                            <th>عنوان العمل</th>
                            <th>التقييم</th>
                            <th>نص التقييم</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($merchantReviews as $review)
                            <tr>
                                <td>
                                    @if($review->image)
                                        <img src="{{ $review->image_url }}" alt="صورة التاجر" class="merchant-image">
                                    @else
                                        <div class="no-image">👤</div>
                                    @endif
                                </td>
                                <td>{{ $review->merchant_name }}</td>
                                <td>{{ $review->business_title ?? 'غير محدد' }}</td>
                                <td>
                                    <div class="rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="star {{ $i <= $review->rating ? '' : 'empty' }}">★</span>
                                        @endfor
                                        <span style="margin-right: 5px;">({{ $review->rating }}/5)</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="review-text" title="{{ $review->review_text }}">
                                        {{ Str::limit($review->review_text, 50) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge {{ $review->is_active ? 'status-active' : 'status-inactive' }}">
                                        {{ $review->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('dashboard.merchant-reviews.show', $review) }}" class="btn btn-view">عرض</a>
                                        <a href="{{ route('dashboard.merchant-reviews.edit', $review) }}" class="btn btn-edit">تعديل</a>
                                        <form action="{{ route('dashboard.merchant-reviews.toggle-status', $review) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-toggle">
                                                {{ $review->is_active ? 'إلغاء تفعيل' : 'تفعيل' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('dashboard.merchant-reviews.destroy', $review) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا التقييم؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-delete">حذف</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <h3>لا توجد تقييمات</h3>
                    <p>لم يتم إضافة أي تقييمات للتجار بعد.</p>
                    <a href="{{ route('dashboard.merchant-reviews.create') }}" class="add-btn" style="margin-top: 1rem; display: inline-block;">إضافة أول تقييم</a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
