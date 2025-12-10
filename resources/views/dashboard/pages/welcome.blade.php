@extends('dashboard.layouts.app')

@section('pageTitle', 'مرحباً بك في لوحة التحكم')

@section('content')
    <div class="inner-body">
        <div class="card">
            <div class="card-body">
                <div class="welcome-content">
                    <div class="text-center">
                        <div class="welcome-icon">
                            <i class="fa fa-home" style="font-size: 4rem; color: #10539C;"></i>
                        </div>
                        <h4 class="mt-3">نورت لوحة التحكم</h4>
                        <p class="mt-3">يمكنك الوصول إلى الأقسام المختلفة من القائمة الجانبية</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
<style>
.welcome-content {
    padding: 3rem 1rem;
}

.welcome-icon {
    margin-bottom: 2rem;
}
</style>
@endpush
