
<title>
    @yield('title', setting('meta_title') ?? 'صمم مساحتك معانا' . ' | ' . setting('site_name') ?? 'NOCE - نوتش')
</title>

@include('website.inc.metas')

@include('website.inc.links')
