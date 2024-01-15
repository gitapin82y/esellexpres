 <!-- Scripts -->
 {{-- <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script> --}}
 <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
 <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>

 <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
 <script src="{{ asset('assets/js/main.js') }}"></script>

 <!--  Chart js -->
 <script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.bundle.min.js"></script>

 <!--Chartist Chart-->
 <script src="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/chartist-plugin-legend@0.6.2/chartist-plugin-legend.min.js"></script>

 <script src="https://cdn.jsdelivr.net/npm/jquery.flot@0.8.3/jquery.flot.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/flot-pie@1.0.0/src/jquery.flot.pie.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/flot-spline@0.0.1/js/jquery.flot.spline.min.js"></script>

 <script src="https://cdn.jsdelivr.net/npm/simpleweather@3.1.0/jquery.simpleWeather.min.js"></script>
 <script src="{{ asset('assets/js/init/weather-init.js')}}"></script>

 <script src="https://cdn.jsdelivr.net/npm/moment@2.22.2/moment.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.js"></script>
 <script src="{{ asset('assets/js/init/fullcalendar-init.js')}}"></script>

 
 <script src="
 https://cdn.jsdelivr.net/npm/sweetalert2@11.9.0/dist/sweetalert2.all.min.js
 "></script>
 

 <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script> 
 <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>

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

  <script src="
https://cdn.jsdelivr.net/npm/lightbox2@2.11.4/dist/js/lightbox.min.js
"></script>

<script>
  lightbox.option({
    'albumLabel': false
  })
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
