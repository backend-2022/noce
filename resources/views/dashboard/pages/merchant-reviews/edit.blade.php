<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل تقييم التاجر - جدولها</title>
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

        .form-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #333;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e9ecef;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e9ecef;
            border-radius: 5px;
            font-size: 1rem;
            min-height: 120px;
            resize: vertical;
            transition: border-color 0.3s ease;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .rating-group {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .rating-stars {
            display: flex;
            gap: 5px;
        }

        .star {
            font-size: 2rem;
            color: #e9ecef;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .star.active {
            color: #ffc107;
        }

        .star:hover {
            color: #ffc107;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-checkbox {
            width: 20px;
            height: 20px;
            accent-color: #667eea;
        }

        .file-input-group {
            position: relative;
        }

        .file-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-input-label {
            display: block;
            padding: 0.75rem;
            border: 2px dashed #e9ecef;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }

        .file-input-label:hover {
            border-color: #667eea;
        }

        .file-preview {
            margin-top: 1rem;
            text-align: center;
        }

        .file-preview img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 5px;
        }

        .current-image {
            margin-top: 1rem;
            text-align: center;
        }

        .current-image img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 5px;
            border: 2px solid #e9ecef;
        }

        .current-image-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            color: #6c757d;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit {
            background: #007bff;
            color: white;
        }

        .btn-submit:hover {
            background: #0056b3;
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
        }

        .btn-cancel:hover {
            background: #5a6268;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .required {
            color: #dc3545;
        }

        @media (max-width: 768px) {
            .container {
                margin: 1rem auto;
                padding: 0 0.5rem;
            }

            .form-container {
                padding: 1rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .rating-group {
                flex-direction: column;
                align-items: flex-start;
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
            <h2 class="page-title">تعديل تقييم التاجر</h2>
            <a href="{{ route('dashboard.merchant-reviews.index') }}" class="back-btn">العودة للقائمة</a>
        </div>

        <div class="form-container">
            <form action="{{ route('dashboard.merchant-reviews.update', $merchantReview) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="merchant_name" class="form-label">
                        اسم التاجر <span class="required">*</span>
                    </label>
                    <input type="text" id="merchant_name" name="merchant_name" class="form-input"
                           value="{{ old('merchant_name', $merchantReview->merchant_name) }}" required>
                    @error('merchant_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="business_title" class="form-label">عنوان العمل</label>
                    <input type="text" id="business_title" name="business_title" class="form-input"
                           value="{{ old('business_title', $merchantReview->business_title) }}">
                    @error('business_title')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="review_text" class="form-label">
                        نص التقييم <span class="required">*</span>
                    </label>
                    <textarea id="review_text" name="review_text" class="form-textarea" required>{{ old('review_text', $merchantReview->review_text) }}</textarea>
                    @error('review_text')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        التقييم <span class="required">*</span>
                    </label>
                    <div class="rating-group">
                        <div class="rating-stars" id="rating-stars">
                            <span class="star" data-rating="1">★</span>
                            <span class="star" data-rating="2">★</span>
                            <span class="star" data-rating="3">★</span>
                            <span class="star" data-rating="4">★</span>
                            <span class="star" data-rating="5">★</span>
                        </div>
                        <span id="rating-text">{{ $merchantReview->rating }}/5</span>
                    </div>
                    <input type="hidden" id="rating" name="rating" value="{{ old('rating', $merchantReview->rating) }}" required>
                    @error('rating')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image" class="form-label">صورة التاجر</label>

                    @if($merchantReview->image)
                        <div class="current-image">
                            <span class="current-image-label">الصورة الحالية:</span>
                            <img src="{{ $merchantReview->image_url }}" alt="صورة التاجر الحالية">
                        </div>
                    @endif

                    <div class="file-input-group">
                        <input type="file" id="image" name="image" class="file-input" accept="image/*">
                        <label for="image" class="file-input-label">
                            <div>📁 {{ $merchantReview->image ? 'تغيير الصورة' : 'اختر صورة أو اسحبها هنا' }}</div>
                            <div style="font-size: 0.875rem; color: #6c757d; margin-top: 0.5rem;">
                                PNG, JPG, JPEG, GIF (حد أقصى 2 ميجابايت)
                            </div>
                        </label>
                    </div>
                    <div class="file-preview" id="file-preview"></div>
                    @error('image')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="is_active" name="is_active" value="1" class="form-checkbox"
                               {{ old('is_active', $merchantReview->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="form-label" style="margin-bottom: 0;">تفعيل التقييم</label>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" onclick="window.history.back()">إلغاء</button>
                    <button type="submit" class="btn btn-submit">تحديث التقييم</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Rating stars functionality
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('rating');
        const ratingText = document.getElementById('rating-text');

        stars.forEach((star, index) => {
            star.addEventListener('click', () => {
                const rating = index + 1;
                ratingInput.value = rating;

                stars.forEach((s, i) => {
                    s.classList.toggle('active', i < rating);
                });

                ratingText.textContent = `${rating}/5`;
            });

            star.addEventListener('mouseenter', () => {
                const rating = index + 1;
                stars.forEach((s, i) => {
                    s.classList.toggle('active', i < rating);
                });
            });
        });

        document.getElementById('rating-stars').addEventListener('mouseleave', () => {
            const currentRating = parseInt(ratingInput.value);
            stars.forEach((s, i) => {
                s.classList.toggle('active', i < currentRating);
            });
        });

        // File preview functionality
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('file-preview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="معاينة الصورة الجديدة">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        });

        // Initialize rating display
        const initialRating = parseInt(ratingInput.value);
        stars.forEach((s, i) => {
            s.classList.toggle('active', i < initialRating);
        });
    </script>
</body>
</html>
