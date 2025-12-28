<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-17615978724"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-17615978724');
</script>

<!-- Event snippet for Purchase conversion page -->
<script>
  gtag('event', 'conversion', {
      'send_to': 'AW-17615978724/SfsTCKqYuMMbEOSB-s9B',
      'value': 1.0,
      'currency': 'SAR',
      'transaction_id': ''
  });
</script>

<!-- jQuery -->
<script src="{{ asset('assets/dashboard/js/jquery-3.5.1.min.js') }}?v={{ file_exists(public_path('assets/dashboard/js/jquery-3.5.1.min.js')) ? filemtime(public_path('assets/dashboard/js/jquery-3.5.1.min.js')) : time() }}"></script>
<!-- Toastr JS -->
<script src="{{ asset('assets/dashboard/js/toastr.min.js') }}?v={{ file_exists(public_path('assets/dashboard/js/toastr.min.js')) ? filemtime(public_path('assets/dashboard/js/toastr.min.js')) : time() }}"></script>
<!-- ajax-handler.js -->
<script src="{{ asset('assets/dashboard/js/ajax-handler.js') }}?v={{ file_exists(public_path('assets/dashboard/js/ajax-handler.js')) ? filemtime(public_path('assets/dashboard/js/ajax-handler.js')) : time() }}" data-cfasync="false"></script>
<!-- Input Maxlength Handler -->
<script src="{{ asset('assets/dashboard/js/input-maxlength.js') }}?v={{ file_exists(public_path('assets/dashboard/js/input-maxlength.js')) ? filemtime(public_path('assets/dashboard/js/input-maxlength.js')) : time() }}"></script>
@stack('js')
