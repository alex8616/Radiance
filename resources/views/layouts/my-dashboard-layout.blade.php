<!doctype html>
<html lang="en">
  <style>
    .wrapper {
      width: 90%;
      margin: 0 auto; /* esto centra horizontalmente */
    }
  </style>
  <head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>Tiny Dashboard - A Bootstrap Dashboard Template</title>
    <link href={{ asset('dashboard/css/simplebar.css') }} rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href={{ asset('dashboard/css/feather.css') }} rel="stylesheet"/>
    <link href={{ asset('dashboard/css/select2.css') }} rel="stylesheet"/>
    <link href={{ asset('dashboard/css/dropzone.css') }} rel="stylesheet"/>
    <link href={{ asset('dashboard/css/uppy.min.css') }} rel="stylesheet"/>
    <link href={{ asset('dashboard/css/jquery.steps.css') }} rel="stylesheet"/>
    <link href={{ asset('dashboard/css/jquery.timepicker.css') }} rel="stylesheet"/>
    <link href={{ asset('dashboard/css/quill.snow.css') }} rel="stylesheet"/>
    <link href={{ asset('dashboard/css/daterangepicker.css') }} rel="stylesheet"/>
    <link href={{ asset('dashboard/css/app-light.css') }} rel="stylesheet"/>
    <link href={{ asset('dashboard/css/daterangepicker.css') }} rel="stylesheet" id="lightTheme" disabled/>
    <link href={{ asset('dashboard/css/app-dark.css') }} rel="stylesheet" id="darkTheme"/>
  </head>
  <body class="horizontal dark  ">
    <div class="modal fade modal-notif modal-slide" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="defaultModalLabel">Notifications</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="list-group list-group-flush my-n3">
              <div class="list-group-item bg-transparent">
                <div class="row align-items-center">
                  <div class="col-auto">
                    <span class="fe fe-box fe-24"></span>
                  </div>
                  <div class="col">
                    <small><strong>Package has uploaded successfull</strong></small>
                    <div class="my-0 text-muted small">Package is zipped and uploaded</div>
                    <small class="badge badge-pill badge-light text-muted">1m ago</small>
                  </div>
                </div>
              </div>
              <div class="list-group-item bg-transparent">
                <div class="row align-items-center">
                  <div class="col-auto">
                    <span class="fe fe-download fe-24"></span>
                  </div>
                  <div class="col">
                    <small><strong>Widgets are updated successfull</strong></small>
                    <div class="my-0 text-muted small">Just create new layout Index, form, table</div>
                    <small class="badge badge-pill badge-light text-muted">2m ago</small>
                  </div>
                </div>
              </div>
              <div class="list-group-item bg-transparent">
                <div class="row align-items-center">
                  <div class="col-auto">
                    <span class="fe fe-inbox fe-24"></span>
                  </div>
                  <div class="col">
                    <small><strong>Notifications have been sent</strong></small>
                    <div class="my-0 text-muted small">Fusce dapibus, tellus ac cursus commodo</div>
                    <small class="badge badge-pill badge-light text-muted">30m ago</small>
                  </div>
                </div> <!-- / .row -->
              </div>
              <div class="list-group-item bg-transparent">
                <div class="row align-items-center">
                  <div class="col-auto">
                    <span class="fe fe-link fe-24"></span>
                  </div>
                  <div class="col">
                    <small><strong>Link was attached to menu</strong></small>
                    <div class="my-0 text-muted small">New layout has been attached to the menu</div>
                    <small class="badge badge-pill badge-light text-muted">1h ago</small>
                  </div>
                </div>
              </div> <!-- / .row -->
            </div> <!-- / .list-group -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Clear All</button>
          </div>
        </div>
      </div>
    </div>
    <div class="wrapper">
      <nav class="navbar navbar-expand-lg navbar-light bg-white flex-row border-bottom shadow">
        <div class="container-fluid">
          <a class="navbar-brand mx-lg-1 mr-0" href="./index.html">
            <svg version="1.1" id="logo" class="navbar-brand-img brand-sm" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 120 120" xml:space="preserve">
              <g>
                <polygon class="st0" points="78,105 15,105 24,87 87,87 	" />
                <polygon class="st0" points="96,69 33,69 42,51 105,51 	" />
                <polygon class="st0" points="78,33 15,33 24,15 87,15 	" />
              </g>
            </svg>
          </a>
          <button class="navbar-toggler mt-2 mr-auto toggle-sidebar text-muted">
            <i class="fe fe-menu navbar-toggler-icon"></i>
          </button>
          <div class="navbar-slide bg-white ml-lg-4" id="navbarSupportedContent">
            <a href="#" class="btn toggle-sidebar d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
              <i class="fe fe-x"><span class="sr-only"></span></i>
            </a>
            <ul class="navbar-nav mr-auto">
              <li class="nav-item dropdown">
                  <a class="nav-link pl-lg-2" href="{{ route('doctor.lista') }}"><span class="ml-1">Inicio</span></a>
              </li>
              @if(auth()->user()->role === 'admin')
              <li class="nav-item dropdown">
                  <a class="dropdown-toggle nav-link pl-lg-3" href="{{ route('sucursales.index') }}">
                      Sucursales
                  </a>
              </li>
              <li class="nav-item dropdown">
                  <a class="dropdown-toggle nav-link pl-lg-3" href="{{ route('usuarios.index') }}">
                      Usuarios
                  </a>
              </li>
              <li class="nav-item dropdown">
                  <a class="dropdown-toggle nav-link pl-lg-3" href="{{ route('admin.materiales') }}">
                      Materiales
                  </a>
              </li>
              @endif
              <li class="nav-item dropdown">
                <a href="#" id="ui-elementsDropdown" class="dropdown-toggle nav-link" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="ml-lg-2">Calendario</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="ui-elementsDropdown">
                  <a class="nav-link pl-lg-2" href="./ui-color.html"><span class="ml-1">Colors</span></a>
                  <a class="nav-link pl-lg-2" href="./ui-typograpy.html"><span class="ml-1">Typograpy</span></a>
                  <a class="nav-link pl-lg-2" href="./ui-icons.html"><span class="ml-1">Icons</span></a>
                  <a class="nav-link pl-lg-2" href="./ui-buttons.html"><span class="ml-1">Buttons</span></a>
                  <a class="nav-link pl-lg-2" href="./ui-notification.html"><span class="ml-1">Notifications</span></a>
                  <a class="nav-link pl-lg-2" href="./ui-modals.html"><span class="ml-1">Modals</span></a>
                  <a class="nav-link pl-lg-2" href="./ui-tabs-accordion.html"><span class="ml-1">Tabs & Accordion</span></a>
                  <a class="nav-link pl-lg-2" href="./ui-progress.html"><span class="ml-1">Progress</span></a>
                </div>
              </li>
              <li class="nav-item dropdown">
                <a href="#" id="formsDropdown" class="dropdown-toggle nav-link" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="ml-lg-2">Pacientes</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="formsDropdown">
                  <a class="nav-link pl-lg-2" href="./form_elements.html"><span class="ml-1">Basic Elements</span></a>
                  <a class="nav-link pl-lg-2" href="./form_advanced.html"><span class="ml-1">Advanced Elements</span></a>
                  <a class="nav-link pl-lg-2" href="./form_validation.html"><span class="ml-1">Validation</span></a>
                  <a class="nav-link pl-lg-2" href="./form_layouts.html"><span class="ml-1">Layouts</span></a>
                  <a class="nav-link pl-lg-2" href="./form_upload.html"><span class="ml-1">File upload</span></a>
                </div>
              </li>
              <li class="nav-item dropdown">
                <a href="#" id="tablesDropdown" class="dropdown-toggle nav-link" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="ml-lg-2">Doctores</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="tablesDropdown">
                  <a class="nav-link pl-lg-2" href="./table_basic.html"><span class="ml-1">Basic Tables</span></a>
                  <a class="nav-link pl-lg-2" href="./table_advanced.html"><span class="ml-1">Advanced Tables</span></a>
                  <a class="nav-link pl-lg-2" href="./table_datatables.html"><span class="ml-1">Data Tables</span></a>
                </div>
              </li>
              @if(auth()->user()->role === 'admin')
              <li class="nav-item dropdown">
                  <a class="dropdown-toggle nav-link pl-lg-3" href="#" id="chartsDropdown" role="button" data-toggle="dropdown">
                      Reportes
                  </a>
                  <ul class="dropdown-menu">
                      <a class="nav-link pl-lg-2" href="/reportes/inline"><span class="ml-1">Inline Chart</span></a>
                      <a class="nav-link pl-lg-2" href="/reportes/chartjs"><span class="ml-1">Chartjs</span></a>
                      <a class="nav-link pl-lg-2" href="/reportes/apex"><span class="ml-1">ApexCharts</span></a>
                      <a class="nav-link pl-lg-2" href="/reportes/mapas"><span class="ml-1">Datamaps</span></a>
                  </ul>
              </li>
              @endif
            </ul>
          </div>
          @if(auth()->user()->role === 'doctor')
            <form class="form-inline ml-md-auto d-none d-lg-flex">
              <select class="form-control form-control-sm rounded-pill px-3" id="selectSucursal">
                  <option value="">Seleccionar sucursal</option>
              </select>
            </form>
          @endif
          <ul class="navbar-nav d-flex flex-row">
            <li class="nav-item">
              <a class="nav-link text-muted my-2" href="./#" id="modeSwitcher" data-mode="dark">
                <i class="fe fe-sun fe-16"></i>
              </a>
            </li>
            <li class="nav-item nav-notif">
              <a class="nav-link text-muted my-2" href="./#" data-toggle="modal" data-target=".modal-notif">
                <i class="fe fe-bell fe-16"></i>
                <span class="dot dot-md bg-success"></span>
              </a>
            </li>
            <li class="nav-item dropdown ml-lg-0">
              <a class="nav-link d-flex align-items-center" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="avatar avatar-sm d-flex align-items-center justify-content-center rounded-circle bg-primary text-white" style="width:32px; height:32px;">
                  {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </span>
                <i class="ml-2 fas fa-chevron-down"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                <li class="nav-item"><a class="nav-link pl-3" href="#">Settings</a></li>
                <li class="nav-item"><a class="nav-link pl-3" href="#">Profile</a></li>
                <li class="nav-item"><a class="nav-link pl-3" href="#">Activities</a></li>
                <li class="nav-item">
                  <a class="nav-link pl-3" href="#"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Cerrar sesión
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                  </form>
                </li>
              </ul>
            </li>

          </ul>
        </div>
      </nav>
      <main role="main" class="main-content">
        @yield('content')
      </main>
    </div>
    <footer class="footer footer-transparent d-print-none">
        <div class="container-xl">
            <div class="row text-center align-items-center flex-row-reverse">
            <div class="col-lg-auto ms-lg-auto">
                <ul class="list-inline list-inline-dots mb-0">
                <li class="list-inline-item"><a  target="_blank" class="link-secondary" rel="noopener">Contacto</a></li>
                </ul>
            </div>
            <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <ul class="list-inline list-inline-dots mb-0">
                <li class="list-inline-item">
                    Copyright &copy; 2026
                    <a href="." class="link-secondary">Alejandro</a>.
                    All rights reserved.
                </li>
                </ul>
            </div>
            </div>
        </div>
    </footer>
    <script src="{{ asset('dashboard/js/jquery.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/popper.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/moment.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/daterangepicker.js') }}"></script>
    <script src="{{ asset('dashboard/js/jquery.stickOnScroll.js') }}"></script>
    <script src="{{ asset('dashboard/js/tinycolor-min.js') }}"></script>
    <script src="{{ asset('dashboard/js/config.js') }}"></script>
    <script src="{{ asset('dashboard/js/d3.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/topojson.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/datamaps.all.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/datamaps-zoomto.js') }}"></script>
    <script src="{{ asset('dashboard/js/datamaps.custom.js') }}"></script>
    <script src="{{ asset('dashboard/js/Chart.min.js') }}"></script>
    <script>
      /* defind global options */
      Chart.defaults.global.defaultFontFamily = base.defaultFontFamily;
      Chart.defaults.global.defaultFontColor = colors.mutedColor;
    </script>
    <script src="{{ asset('dashboard/js/gauge.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/apexcharts.custom.js') }}"></script>
    <script src="{{ asset('dashboard/js/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/select2.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/jquery.timepicker.js') }}"></script>
    <script src="{{ asset('dashboard/js/dropzone.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/uppy.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/quill.min.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
      $('.select2').select2(
      {
        theme: 'bootstrap4',
      });
      $('.select2-multi').select2(
      {
        multiple: true,
        theme: 'bootstrap4',
      });
      $('.drgpicker').daterangepicker(
      {
        singleDatePicker: true,
        timePicker: false,
        showDropdowns: true,
        locale:
        {
          format: 'MM/DD/YYYY'
        }
      });
      $('.time-input').timepicker(
      {
        'scrollDefault': 'now',
        'zindex': '9999' /* fix modal open */
      });
      /** date range picker */
      if ($('.datetimes').length)
      {
        $('.datetimes').daterangepicker(
        {
          timePicker: true,
          startDate: moment().startOf('hour'),
          endDate: moment().startOf('hour').add(32, 'hour'),
          locale:
          {
            format: 'M/DD hh:mm A'
          }
        });
      }
      var start = moment().subtract(29, 'days');
      var end = moment();

      function cb(start, end)
      {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
      }
      $('#reportrange').daterangepicker(
      {
        startDate: start,
        endDate: end,
        ranges:
        {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
      }, cb);
      cb(start, end);
      $('.input-placeholder').mask("00/00/0000",
      {
        placeholder: "__/__/____"
      });
      $('.input-zip').mask('00000-000',
      {
        placeholder: "____-___"
      });
      $('.input-money').mask("#.##0,00",
      {
        reverse: true
      });
      $('.input-phoneus').mask('(000) 000-0000');
      $('.input-mixed').mask('AAA 000-S0S');
      $('.input-ip').mask('0ZZ.0ZZ.0ZZ.0ZZ',
      {
        translation:
        {
          'Z':
          {
            pattern: /[0-9]/,
            optional: true
          }
        },
        placeholder: "___.___.___.___"
      });
      // editor
      var editor = document.getElementById('editor');
      if (editor)
      {
        var toolbarOptions = [
          [
          {
            'font': []
          }],
          [
          {
            'header': [1, 2, 3, 4, 5, 6, false]
          }],
          ['bold', 'italic', 'underline', 'strike'],
          ['blockquote', 'code-block'],
          [
          {
            'header': 1
          },
          {
            'header': 2
          }],
          [
          {
            'list': 'ordered'
          },
          {
            'list': 'bullet'
          }],
          [
          {
            'script': 'sub'
          },
          {
            'script': 'super'
          }],
          [
          {
            'indent': '-1'
          },
          {
            'indent': '+1'
          }], // outdent/indent
          [
          {
            'direction': 'rtl'
          }], // text direction
          [
          {
            'color': []
          },
          {
            'background': []
          }], // dropdown with defaults from theme
          [
          {
            'align': []
          }],
          ['clean'] // remove formatting button
        ];
        var quill = new Quill(editor,
        {
          modules:
          {
            toolbar: toolbarOptions
          },
          theme: 'snow'
        });
      }
      // Example starter JavaScript for disabling form submissions if there are invalid fields
      (function()
      {
        'use strict';
        window.addEventListener('load', function()
        {
          // Fetch all the forms we want to apply custom Bootstrap validation styles to
          var forms = document.getElementsByClassName('needs-validation');
          // Loop over them and prevent submission
          var validation = Array.prototype.filter.call(forms, function(form)
          {
            form.addEventListener('submit', function(event)
            {
              if (form.checkValidity() === false)
              {
                event.preventDefault();
                event.stopPropagation();
              }
              form.classList.add('was-validated');
            }, false);
          });
        }, false);
      })();
    </script>
    <script>
      var uptarg = document.getElementById('drag-drop-area');
      if (uptarg)
      {
        var uppy = Uppy.Core().use(Uppy.Dashboard,
        {
          inline: true,
          target: uptarg,
          proudlyDisplayPoweredByUppy: false,
          theme: 'dark',
          width: 770,
          height: 210,
          plugins: ['Webcam']
        }).use(Uppy.Tus,
        {
          endpoint: 'https://master.tus.io/files/'
        });
        uppy.on('complete', (result) =>
        {
          console.log('Upload complete! We’ve uploaded these files:', result.successful)
        });
      }
    </script>
    <script src="{{ asset('dashboard/js/apps.js') }}"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-56159088-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];

      function gtag()
      {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());
      gtag('config', 'UA-56159088-1');
    </script>
    <script>
    $(document).ready(function() {

        cargarSucursalesDoctor();

        function cargarSucursalesDoctor() {
            $.ajax({
                url: "/doctor-sucursales",
                type: "GET",
                success: function(res) {

                    let options = '<option value="">Seleccionar sucursal</option>';

                    res.data.forEach(function(item) {
                        options += `<option value="${item.id}">${item.nombre}</option>`;
                    });

                    $("#selectSucursal").html(options);

                    // 🔥 seleccionar la actual si existe en sesión
                    let actual = "{{ session('sucursal_id') }}";
                    if (actual) {
                        $("#selectSucursal").val(actual);
                    }
                }
            });
        }

        // 🔥 cuando cambia sucursal
        $(document).on("change", "#selectSucursal", function() {

            let sucursal_id = $(this).val();

            if (!sucursal_id) return;

            $.ajax({
                url: "/cambiar-sucursal",
                type: "POST",
                data: {
                    sucursal_id: sucursal_id,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    location.reload(); // 🔥 recargar sistema
                }
            });
        });

    });
    </script>
  </body>
</html>