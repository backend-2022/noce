@extends('dashboard.layouts.app')

@section('pageTitle', 'تفاصيل الطلب')
@section('pageSubTitle', 'إدارة الطلبات')
@section('searchPlaceholder', 'ابحث عن الطلب')

@section('content')

<div class="page-wrapper compact-wrapper" id="pageWrapper">
    <div class="detail_card">
        <div class="flex_stuation">
            <h1>تفاصيل الطلب</h1>
        </div>

        <div class="grid_content">
            <div class="flex">
                <span class="span_color">رقم الطلب :</span>
                <span class="span_detail text-break">{{ $orderData['reference'] }}</span>
            </div>
            <div class="flex">
                <span class="span_color">كود الطلب :</span>
                <span class="span_detail text-break">{{ $orderData['code'] ?? 'غير متوفر' }}</span>
            </div>
            <div class="flex">
                <span class="span_color">اسم المتجر :</span>
                <span class="span_detail text-break">{{ $orderData['store_name'] ?? 'غير متوفر' }}</span>
            </div>
            <div class="flex">
                <span class="span_color">تاريخ إنشاء الطلب :</span>
                <span class="span_detail text-break">{{ $orderData['created_at'] ?? '---' }}</span>
            </div>
            <div class="flex">
                <span class="span_color">تاريخ آخر تحديث :</span>
                <span class="span_detail text-break">{{ $orderData['updated_at'] ?? '---' }}</span>
            </div>
            <div class="flex">
                <span class="span_color">حالة الطلب :</span>
                <span class="span_detail text-break bg_span">
                    {{ $orderData['status']['name'] ?? 'غير معروف' }}
                </span>
            </div>
            <div class="flex">
                <span class="span_color">حالة الدفع :</span>
                <span class="span_detail text-break">{{ $orderData['payment_status'] ?? 'غير متوفر' }}</span>
            </div>
            <div class="flex">
                <span class="span_color">إجمالي الطلب :</span>
                <span class="span_detail text-break">{{ $orderData['order_total'] }}</span>
            </div>
        </div>
    </div>

    <div class="detail_card">
        <div class="flex_stuation">
            <h1>المنتجات</h1>
        </div>

        <div class="grid_content product_grid">
            @forelse ($orderData['products'] as $product)
                <div class="flex">
                    <span class="span_color">اسم المنتج :</span>
                    <span class="span_detail text-break">{{ $product['name'] }}</span>
                </div>
                <div class="flex">
                    <span class="span_color">رمز المنتج :</span>
                    <span class="span_detail text-break">{{ $product['sku'] }}</span>
                </div>
                <div class="flex">
                    <span class="span_color">صورة المنتج :</span>
                    <img class="img_style" src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
                </div>
                <div class="flex">
                    <span class="span_color">الكمية :</span>
                    <span class="span_detail text-break">{{ $product['quantity'] }}x</span>
                </div>
                <div class="flex">
                    <span class="span_color">السعر :</span>
                    <span class="span_detail text-break">{{ $product['price'] }}</span>
                </div>
                <div class="flex">
                    <span class="span_color">الإجمالي :</span>
                    <span class="span_detail text-break">{{ $product['total'] }}</span>
                </div>
                <hr>
            @empty
                <p class="text-center w-100">لا توجد منتجات مسجلة في هذا الطلب.</p>
            @endforelse
        </div>
    </div>

    <div class="detail_card">
        <div class="flex_stuation">
            <h1>الفاتورة</h1>
        </div>

        <div class="grid_content">
            @forelse ($orderData['invoice_lines'] as $line)
                <div class="flex">
                    <span class="span_color">{{ $line['title'] }} :</span>
                    <span class="span_detail text-break">{{ $line['value'] }}</span>
                </div>
            @empty
                <p class="text-center w-100">لا توجد بيانات فاتورة.</p>
            @endforelse
        </div>
    </div>

    <div class="detail_card">
        <div class="flex_stuation">
            <h1>معلومات الشحن</h1>
        </div>

        <div class="grid_content">
            <div class="flex">
                <span class="span_color">طريقة الشحن :</span>
                <span class="span_detail text-break">{{ $orderData['shipping']['method'] ?? 'غير متوفر' }}</span>
            </div>
            <div class="flex">
                <span class="span_color">عنوان الشحن :</span>
                <span class="span_detail text-break">{{ $orderData['shipping']['address'] ?? 'لا يوجد عنوان' }}</span>
            </div>
        </div>
    </div>

    <div class="detail_card">
        <div class="flex_stuation">
            <h1>بيانات العميل</h1>
        </div>

        <div class="grid_content customer_grid">
            <div class="flex">
                <span class="span_color">اسم العميل :</span>
                <span class="span_detail text-break">{{ $orderData['customer']['name'] }}</span>
            </div>
            <div class="flex">
                <span class="span_color">البريد الإلكتروني :</span>
                <span class="span_detail text-break">{{ $orderData['customer']['email'] }}</span>
            </div>
            <div class="flex">
                <span class="span_color">رقم الجوال :</span>
                <span class="span_detail text-break">{{ $orderData['customer']['mobile'] }}</span>
            </div>
            <div class="flex">
                <span class="span_color">الدولة :</span>
                <span class="span_detail text-break">{{ $orderData['customer']['country'] }}</span>
            </div>
            <div class="flex">
                <span class="span_color">المدينة :</span>
                <span class="span_detail text-break">{{ $orderData['customer']['city'] }}</span>
            </div>
            <div class="flex">
                <span class="span_color">الحي :</span>
                <span class="span_detail text-break">{{ $orderData['customer']['district'] }}</span>
            </div>
            <div class="flex">
                <span class="span_color">الشارع :</span>
                <span class="span_detail text-break">{{ $orderData['customer']['street'] }}</span>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.text-break').forEach(function(el) {
                el.style.wordBreak = 'break-word';
            });
        });
    </script>
@endpush
