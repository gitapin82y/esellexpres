  <!-- Js Plugins -->
  <script src="{{asset('penjual/js/jquery-3.3.1.min.js')}}"></script>
  <script src="{{asset('penjual/js/bootstrap.min.js')}}"></script>
  <script src="{{asset('penjual/js/jquery-ui.min.js')}}"></script>
  <script src="{{asset('penjual/js/jquery.countdown.min.js')}}"></script>
  <script src="{{asset('penjual/js/jquery.nice-select.min.js')}}"></script>
  <script src="{{asset('penjual/js/jquery.zoom.min.js')}}"></script>
  <script src="{{asset('penjual/js/jquery.dd.min.js')}}"></script>
  <script src="{{asset('penjual/js/jquery.slicknav.js')}}"></script>
  <script src="{{asset('penjual/js/owl.carousel.min.js')}}"></script>
  <script src="{{asset('penjual/node_modules/@fortawesome/fontawesome-free/js/all.js')}}"></script>
  <script src="{{asset('penjual/js/main.js')}}"></script>
  <!-- Include Clipboard.js library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.10/clipboard.min.js"></script>

<!-- Initialize Clipboard.js -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var clipboard = new ClipboardJS('.copy-button');

        clipboard.on('success', function(e) {
            alert('Account number copied! ' + e.text);
            e.clearSelection();
        });

        clipboard.on('error', function(e) {
            alert('Unable to copy. Please copy the account number manually.');
        });
    });
</script>
  <script>
     function handleInput(inputElement) {
          let value = inputElement.value;
  
          // Menghapus karakter selain angka dan titik
          value = value.replace(/[^\d.]/g, '');
  
          // Mencegah lebih dari satu titik desimal
          let parts = value.split('.');
          if (parts.length > 2) {
              value = parts[0] + '.' + parts.slice(1).join('');
          }
  
          // Memastikan value bukan NaN
          if (!isNaN(value)) {
              // Memberikan format dolar dengan dua digit desimal
              inputElement.value = value;
          } else {
              // Jika input tidak valid, set nilai ke kosong
              inputElement.value = '';
          }
      }
  </script>
