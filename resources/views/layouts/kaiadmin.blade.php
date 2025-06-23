<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Dashboard</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="assets/img/kaiadmin/favicon.ico"
      type="image/x-icon"
    />

    <!-- Fonts and icons -->
    <script src="{{asset('assets/js/plugin/webfont/webfont.min.js')}}"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["{{asset('assets/css/fonts.min.css')}}"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/plugins.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/kaiadmin.min.css')}}" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="{{asset('assets/css/demo.css')}}" />
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar sidebar-style-2" data-background-color="dark">
        <div class="sidebar-logo">

        
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="/" class="logo">
              <img
                src="{{ asset('assets/img/logohorizontal.png') }}"
                alt="navbar brand"
                class="navbar-brand"
                height="50"
              />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
            <li class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
                <a
                  href="{{ route('dashboard') }}"
                  class="collapsed"
                  aria-expanded="false"
                >
                  <i class="fas fa-home"></i>
                  <p>Dashboard</p>
                </a>
              </li>
              <li class="nav-section">
          <span class="sidebar-mini-icon">
            <i class="fa fa-ellipsis-h"></i>
          </span>
          <h4 class="text-section">Menu</h4>
              </li>
              <li class="nav-item {{ Route::is('tenants.*') ? 'active' : '' }}">
            <a href="{{ route('tenants.index') }}">
            <i class="fas fa-users"></i>
            <p>Data Penghuni</p>
          </a>
              </li>
              <li class="nav-item {{ Route::is('kamar.*') ? 'active' : '' }}">
          <a href="{{ route('kamar.index') }}">
            <i class="fas fa-door-closed"></i>
            <p>Kamar</p>
          </a>
              </li>
              <li class="nav-item {{ Route::is('inventaris.index') ? 'active' : '' }}">
                <a href="{{ route('inventaris.index') }}">
                  <i class="fas fa-server"></i>
                  <p>Inventaris</p>
                </a>
              </li>
              <li class="nav-item {{ Route::is('pembayaran.index') ? 'active' : '' }}">
                <a href="{{ route('pembayaran.index') }}">
                  <i class="fas fa-donate"></i>
                  <p>Pembayaran</p>
                </a>
              </li>
              <li class="nav-item {{ Route::is('laporan.index') ? 'active' : '' }}">
                <a href="{{ route('laporan.index') }}">
                  <i class="fas fa-chart-area"></i>
                  <p>Laporan & Statistik</p>
                </a>
              </li>
              <li class="nav-item {{ Route::is('keluhan.index') ? 'active' : '' }}">
                <a href="{{ route('keluhan.index') }}">
                  <i class="fas fa-comment-alt"></i>
                  <p>Keluhan</p>
                </a>
              </li>
              <li class="nav-item {{ Route::is('pengeluaran.index') ? 'active' : '' }}">
                <a href="{{ route('pengeluaran.index') }}">
                  <i class="fas fa-hand-holding-usd"></i>
                  <p>Pengeluaran</p>
                </a>
              </li>
              <li class="nav-item {{ Route::is('pegawai.index') ? 'active' : '' }}">
                <a  href="{{ route('pegawai.index') }}">
                  <i class="fas fa-user-cog"></i>
                  <p>Pegawai</p>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="index.html" class="logo">
                <img
                  src="assets/img/kaiadmin/logo_light.svg"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="20"
                />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
          
          <!-- Navbar Header -->
          <nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
          >
            <div class="container-fluid">
            

              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li
                  class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none"
                >
                  <a
                    class="nav-link dropdown-toggle"
                    data-toggle="dropdown"
                    href="#"
                    role="button"
                    aria-expanded="false"
                    aria-haspopup="true"
                  >
                    <i class="fa fa-search"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-search animated fadeIn">
                    <form class="navbar-left navbar-form nav-search">
                      <div class="input-group">
                        <input
                          type="text"
                          placeholder="Search ..."
                          class="form-control"
                        />
                      </div>
                    </form>
                  </ul>
                </li>


                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <div class="avatar-sm">
                      <img
                        src="{{ asset('assets/img/profil1.jpg') }}"
                        alt="..."
                        class="avatar-img rounded-circle"
                      />
                    </div>
                    <span class="profile-username">
                      <span class="op-7">Hi,</span>
                      <span class="fw-bold">{{ Auth::user()->name }}</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            <img
                              src="{{ asset('assets/img/profil1.jpg') }}"
                              alt="image profile"
                              class="avatar-img rounded"
                            />
                          </div>
                          <div class="u-text">
                            <h4>{{ Auth::user()->name }}</h4>
                            <p class="text-muted">{{ Auth::user()->email}}</p>
                          </div>
                        </div>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                      </li>
                    </div>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
          <!-- End Navbar -->
        </div>

        <div class="container">
          <div class="page-inner">
            <div class="row card-row justify-content-between">
              <!-- Card Penghuni di kiri -->
              <div class="col-sm-6 col-md-3 mb-4">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                          <i class="fas fa-users"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Penghuni</p>
                          <h4 class="card-title">{{ \App\Models\Tenant::count() }}</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Card Kamar Terisi di tengah -->
              <div class="col-sm-6 col-md-3 mb-4">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div class="icon-big text-center icon-success bubble-shadow-small">
                          <i class="fas fa-door-closed"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Kamar Terisi</p>
                          <h4 class="card-title">{{ \App\Models\Kamar::where('status', 'dihuni')->count() }}</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Card Kamar Kosong -->
              <div class="col-sm-6 col-md-3 mb-4">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div class="icon-big text-center icon-warning bubble-shadow-small">
                          <i class="fas fa-door-open"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Kamar Kosong</p>
                          <h4 class="card-title">{{ \App\Models\Kamar::where('status', 'kosong')->count() }}</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Card Pegawai di kanan -->
              <div class="col-sm-6 col-md-3 mb-4">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div class="icon-big text-center icon-secondary bubble-shadow-small">
                          <i class="fas fa-user-cog"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Pegawai</p>
                          <h4 class="card-title">{{ \App\Models\Pegawai::count() }}</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-6 mb-4">
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">Keluhan</div>
                  </div>
                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <!-- Projects table -->
                      <table class="table align-items-center mb-0">
                        <thead class="thead-light">
                          <tr>
                            <th scope="col">Nama Penghuni</th>
                            <th scope="col">Status Keluhan</th>
                          </tr>
                        </thead>
                        <tbody>
                          @php
                            $recentKeluhans = \App\Models\Keluhan::with('tenant')->latest()->take(5)->get();
                          @endphp
                          
                          @forelse($recentKeluhans as $keluhan)
                          <tr>
                            <th scope="row">
                              <div class="d-flex flex-column">
                                <span class="font-weight-bold">{{ $keluhan->nama_penghuni }}</span>
                                <small class="text-muted">Kamar {{ $keluhan->no_kamar }}</small>
                              </div>
                            </th>
                            <td>
                              @if($keluhan->status_keluhan == 'selesai')
                                <span class="badge badge-success">Selesai</span>
                              @elseif($keluhan->status_keluhan == 'diproses')
                                <span class="badge badge-info">Diproses</span>
                              @else
                                <span class="badge badge-warning">Pending</span>
                              @endif
                            </td>
                          </tr>
                          @empty
                          <tr>
                            <td colspan="2" class="text-center text-muted">
                              <small>Tidak ada keluhan terbaru</small>
                            </td>
                          </tr>
                          @endforelse
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-4">
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">Riwayat Pembayaran</div>
                  </div>
                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <!-- Projects table -->
                      <table class="table align-items-center mb-0">
                        <thead class="thead-light">
                          <tr>
                            <th scope="col">Nama Penghuni</th>
                            <th scope="col">Jumlah Pembayaran</th>
                            <th scope="col">Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          @php
                            $recentPembayarans = \App\Models\Pembayaran::with('tenant')->latest()->take(5)->get();
                          @endphp
                          
                          @forelse($recentPembayarans as $pembayaran)
                          <tr>
                            <th scope="row">
                              <div class="d-flex flex-column">
                                <span class="font-weight-bold">{{ $pembayaran->tenant ? $pembayaran->tenant->nama_penyewa : $pembayaran->nama_penghuni ?? 'N/A' }}</span>
                                <small class="text-muted">Kamar {{ $pembayaran->no_kamar }}</small>
                              </div>
                            </th>
                            <td>
                              <span class="font-weight-bold text-success">Rp {{ number_format($pembayaran->jumlah_pembayaran, 0, ',', '.') }}</span>
                            </td>
                            <td>
                              @if($pembayaran->status_pembayaran == 'lunas')
                                <span class="badge badge-success">Lunas</span>
                              @elseif($pembayaran->status_pembayaran == 'belum_bayar')
                                <span class="badge badge-warning">Belum Bayar</span>
                              @elseif($pembayaran->status_pembayaran == 'terlambat')
                                <span class="badge badge-danger">Terlambat</span>
                              @else
                                <span class="badge badge-secondary">{{ ucfirst($pembayaran->status_pembayaran) }}</span>
                              @endif
                            </td>
                          </tr>
                          @empty
                          <tr>
                            <td colspan="3" class="text-center text-muted">
                              <small>Tidak ada pembayaran terbaru</small>
                            </td>
                          </tr>
                          @endforelse
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Bar Chart Row -->
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">Grafik Pengeluaran Bulanan</div>
                  </div>
                  <div class="card-body">
                    <div class="chart-container">
                      <canvas id="barChart"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
              <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="http://www.themekita.com">
                    ThemeKita
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Simkos </a>
                </li>
              </ul>
            </nav>
            <div class="copyright">
              Sistem Manajemen Kost
            </div>
            <div>
              Distributed by
              <a target="_blank" href="https://themewagon.com/">ThemeWagon</a>.
            </div>
          </div>
        </footer>

      </div>

    </div>
    <!--   Core JS Files   -->
    <script src="{{asset('assets/js/core/jquery-3.7.1.min.js')}}"></script>
    <script src="{{asset('assets/js/core/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/core/bootstrap.min.js')}}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script>

    <!-- Chart JS -->
    <script src="{{asset('assets/js/plugin/chart.js/chart.min.js')}}"></script>

    <!-- jQuery Sparkline -->
    <script src="{{asset('assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js')}}"></script>

    <!-- Chart Circle -->
    <script src="{{asset('assets/js/plugin/chart-circle/circles.min.js')}}"></script>

    <!-- Datatables -->
    <script src="{{asset('assets/js/plugin/datatables/datatables.min.js')}}"></script>

    <!-- Bootstrap Notify -->
    <script src="{{asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js')}}"></script>

    <!-- jQuery Vector Maps -->
    <script src="{{asset('assets/js/plugin/jsvectormap/jsvectormap.min.js')}}"></script>
    <script src="{{asset('assets/js/plugin/jsvectormap/world.js')}}"></script>

    <!-- Sweet Alert -->
    <script src="{{asset('assets/js/plugin/sweetalert/sweetalert.min.js')}}"></script>

    <!-- Kaiadmin JS -->
    <script src="{{asset('assets/js/kaiadmin.min.js')}}"></script>

    <!-- Custom Chart Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap dropdowns
        if (typeof $ !== 'undefined') {
            $('[data-toggle="dropdown"]').dropdown();
        }
        
        // Bar Chart untuk Pengeluaran
        const barCtx = document.getElementById('barChart').getContext('2d');
        
        @php
            $monthlyData = [];
            for ($i = 1; $i <= 12; $i++) {
                $total = (int) \App\Models\Pengeluaran::whereMonth('tanggal_pengeluaran', $i)
                    ->whereYear('tanggal_pengeluaran', now()->year)
                    ->sum('nominal');
                $monthlyData[] = $total;
            }
        @endphp
        
        const barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pengeluaran Bulanan',
                    data: @json($monthlyData),
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Pengeluaran: Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            }
                        }
                    }
                }
            }
        });
    });
    </script>
    
    @yield('scripts')
  </body>
</html>
