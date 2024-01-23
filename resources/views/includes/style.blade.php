
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
<link rel="stylesheet" href="{{ asset('assets/css/cs-skin-elastic.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->
<link href="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/jqvmap@1.5.1/dist/jqvmap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/weathericons@2.1.0/css/weather-icons.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.9.0/dist/sweetalert2.min.css
" rel="stylesheet">

<link href="
https://cdn.jsdelivr.net/npm/lightbox2@2.11.4/dist/css/lightbox.min.css
" rel="stylesheet">

<style>
    
.line-through{
    text-decoration: line-through;
}

.text-main{
    color: #f78104;
}
.btn-main{
            background-color: #f78104;
            border: none;
            color: white;
        }
        .btn-main:hover{
            background-color:#ffa13d;
            color: white;
        }
.btn-border{
    border:1px solid #f78104;
    color: #ffa13d;
}
.btn-border:hover{
    border:1px solid#ffa13d;
    color: #ffa13d;
}
#weatherWidget .currentDesc {
    color: #ffffff!important;
}
    .traffic-chart {
        min-height: 335px;
    }
    #flotPie1  {
        height: 150px;
    }
    #flotPie1 td {
        padding:3px;
    }
    #flotPie1 table {
        top: 20px!important;
        right: -10px!important;
    }
    .chart-container {
        display: table;
        min-width: 270px ;
        text-align: left;
        padding-top: 10px;
        padding-bottom: 10px;
    }
    #flotLine5  {
         height: 105px;
    }

    #flotBarChart {
        height: 150px;
    }
    #cellPaiChart{
        height: 160px;
    }
    .lb-nav{
    display: none !important;
}
@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0; }
}

.badge {
    display: inline-block !important;
    width: 20px;
    height: 20px;
    margin-top: 3px !important;
    border-radius: 20px !important;
    background-color: #fb3a3a; /* Warna background badge */
    color: #fff; /* Warna teks badge */
    font-size: 14px; /* Ukuran teks */
    z-index: 999;
    font-weight: bold; /* Ketebalan teks */
    position: relative;
    animation: blink 2s infinite; /* Menambahkan animasi kedip kedip (2 detik per kedipan) */
    animation-delay: 2s; /* Menambahkan delay 2s sebelum animasi dimulai */
}

        /* CSS untuk angka di dalam badge */
        .badge span {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

</style>