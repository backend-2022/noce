
<title>
    @yield('title', setting('site_name') ?? 'NOCE - نوتش' . ' | لوحة التحكم')
</title>

@include('dashboard.inc.metas')

<base href="../"/>

@include('dashboard.inc.links')
