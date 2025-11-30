@extends('dashboard.layouts.app')

@section('pageTitle', 'الأسئلة الشائعة')

@section('content')
    <div class="inner-body">
        <div class="card">
            <div class="line-body"></div>
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                    <div>
                        <h4 class="h1_style mb-1">قائمة الأسئلة الشائعة</h4>
                        <p class="text-muted mb-0">تحكم كامل في الأسئلة وإجاباتها داخل لوحة التحكم</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('dashboard.faqs.create') }}" class="btn btn-primary" style="color: white;">
                            <i class="fa fa-plus"></i>
                            إضافة سؤال
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered datatable tabel_style">
                        <thead>
                            <tr>
                                <th>
                                    <div class="checkbox checkbox-primary">
                                        <input id="selectAllFaqs" type="checkbox">
                                        <label for="selectAllFaqs">تحديد الكل</label>
                                    </div>
                                </th>
                                <th>السؤال</th>
                                <th>الإجابة المختصرة</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الحالة</th>
                                <th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($faqs as $faq)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input faq-checkbox" type="checkbox"
                                                id="faqSelect{{ $faq->id }}">
                                            <label for="faqSelect{{ $faq->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="span_styles d-block fw-semibold">{{ $faq->question }}</span>
                                    </td>
                                    <td>
                                        <span class="span_styles">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($faq->answer), 120) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="span_styles">
                                            {{ $faq->created_at?->format('Y-m-d H:i') ?? '—' }}
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('dashboard.faqs.toggle-status', $faq) }}"
                                            style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <div class="form-check form-switch">
                                                <input class="form-check-input check_styles" type="checkbox" role="switch"
                                                    id="faqStatus{{ $faq->id }}" {{ $faq->is_active ? 'checked' : '' }}
                                                    onchange="this.form.submit()">
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="btns-table">
                                            <a href="{{ route('dashboard.faqs.edit', $faq) }}"
                                                class="btn_styles amendment">
                                                <i class="fa fa-edit"></i>
                                                تعديل
                                            </a>
                                            <a href="#" class="btn_styles delete_row delete-faq"
                                                data-url="{{ route('dashboard.faqs.destroy', $faq) }}"
                                                data-faq-question="{{ $faq->question }}">
                                                <i class="fa fa-trash"></i>
                                                حذف
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="mb-2">لا توجد أسئلة شائعة حالياً</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if (method_exists($faqs, 'links'))
                    <div class="mt-3">
                        {{ $faqs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('js')
    @if (session('success'))
        <script>
            toastr.success('{{ session('success') }}');
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.error('{{ session('error') }}');
        </script>
    @endif

    <script>
        $(document).ready(function() {
            $('#selectAllFaqs').on('change', function() {
                $('.faq-checkbox').prop('checked', $(this).is(':checked'));
            });

            $('.delete-faq').on('click', function(e) {
                e.preventDefault();

                if (typeof handleDelete !== 'function') {
                    console.error('ajax-handler.js لم يتم تحميله');
                    return;
                }

                const deleteUrl = $(this).data('url');
                const faqQuestion = $(this).data('faq-question');
                const row = $(this).closest('tr');

                handleDelete(
                    deleteUrl,
                    'تم حذف السؤال بنجاح',
                    'حدث خطأ أثناء حذف السؤال',
                    row,
                    null,
                    'هل أنت متأكد؟',
                    'هل تريد حذف السؤال: ' + faqQuestion + '؟\nلا يمكن التراجع عن هذا الإجراء!'
                );
            });
        });
    </script>
@endpush
