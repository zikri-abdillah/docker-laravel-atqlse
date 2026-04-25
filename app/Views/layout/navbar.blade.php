
<aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="index.html" class="text-nowrap logo-img" align="center"> 
            <img src="<?= base_url() ?>assets/images/logos/atq-logo.png" width="150" height="60" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">

            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Menu</span>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= base_url() ?>beranda/internal" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            @if (session()->get('sess_role') == 6 || session()->get('sess_role') == 7)
              <li class="nav-small-cap mt-4">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">LSE BATUBARA</span>
              </li>

              <li class="sidebar-item">
                <a class="sidebar-link" href="<?= base_url() ?>ekspor/coal/ls/pengajuan" aria-expanded="false">
                  <span>
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-bubble-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12.4 19a4.2 4.2 0 0 1 -1.57 -.298l-3.83 2.298v-3.134a2.668 2.668 0 0 1 -1.795 -3.773a4.8 4.8 0 0 1 2.908 -8.933a5.335 5.335 0 0 1 9.194 1.078a5.333 5.333 0 0 1 4.45 6.89" /><path d="M16 19h6" /><path d="M19 16v6" /></svg>
                  </span>
                  <span class="hide-menu">Pengajuan Online</span>
                </a>
              </li>

              <li class="sidebar-item">
                <a class="sidebar-link" href="<?= base_url() ?>ekspor/coal/ls/konsep" aria-expanded="false">
                  <span>
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                  </span>
                  <span class="hide-menu">Konsep</span>
                </a>
              </li>

              <li class="sidebar-item">
                <a class="sidebar-link" href="<?= base_url() ?>ekspor/coal/ls/terbit" aria-expanded="false">
                  <span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-certificate" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                      <path d="M5 8v-3a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-5"></path>
                      <path d="M6 14m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                      <path d="M4.5 17l-1.5 5l3 -1.5l3 1.5l-1.5 -5"></path>
                    </svg>
                  </span>
                  <span class="hide-menu">Selesai</span>
                </a>
              </li>
            @endif

            <!--
            <li class="sidebar-item">
              <a class="sidebar-link has-arrow" data-bs-toggle="slide">
                <span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mountain" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M3 20h18l-6.921 -14.612a2.3 2.3 0 0 0 -4.158 0l-6.921 14.612z"></path>
                    <path d="M7.5 11l2 2.5l2.5 -2.5l2 3l2.5 -2"></path>
                  </svg>
                </span>
                <span class="hide-menu">LSE Batubara</span>
              </a>

              <ul class="slide-menu collapse">
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url() ?>ekspor/coal/ls/pengajuan" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-loader-3" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M3 12a9 9 0 0 0 9 9a9 9 0 0 0 9 -9a9 9 0 0 0 -9 -9"></path>
                      <path d="M17 12a5 5 0 1 0 -5 5"></path>
                    </svg>
                    <span class="hide-menu">Pengajuan Online</span>
                  </a>
                </li> 
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url() ?>ekspor/coal/ls/konsep" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-loader-3" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M3 12a9 9 0 0 0 9 9a9 9 0 0 0 9 -9a9 9 0 0 0 -9 -9"></path>
                      <path d="M17 12a5 5 0 1 0 -5 5"></path>
                    </svg>
                    <span class="hide-menu">LSE Konsep</span>
                  </a>
                </li> 
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url() ?>ekspor/coal/ls/proses" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-loader-3" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M3 12a9 9 0 0 0 9 9a9 9 0 0 0 9 -9a9 9 0 0 0 -9 -9"></path>
                      <path d="M17 12a5 5 0 1 0 -5 5"></path>
                    </svg>
                    <span class="hide-menu">LSE Dalam Proses</span>
                  </a>
                </li> 
                <li class="sidebar-item">
                  <a class="sidebar-link"  href="<?= base_url() ?>ekspor/coal/ls/terbit" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-loader-3" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M3 12a9 9 0 0 0 9 9a9 9 0 0 0 9 -9a9 9 0 0 0 -9 -9"></path>
                      <path d="M17 12a5 5 0 1 0 -5 5"></path>
                    </svg>
                    <span class="hide-menu">LSE Terbit</span>
                  </a>
                </li> 
              </ul>
            </li>
            -->

            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= base_url() ?>ekspor/rekapitulasi/lse" aria-expanded="false">
                <span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-report" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697"></path>
                    <path d="M18 14v4h4"></path>
                    <path d="M18 11v-4a2 2 0 0 0 -2 -2h-2"></path>
                    <path d="M8 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z"></path>
                    <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                    <path d="M8 11h4"></path>
                    <path d="M8 15h3"></path>
                  </svg>
                </span>
                <span class="hide-menu">Rekapitulasi</span>
              </a>
            </li>

            {{-- @if (session()->get('sess_role') == 1) --}}
              <li class="sidebar-item d-none">
                <a class="sidebar-link" href="<?= base_url() ?>ekspor/rekapitulasi/laporan-bulanan" aria-expanded="false">
                  <span>
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z"/>
                    </svg>
                  </span>
                  <span class="hide-menu">Laporan Penerbitan</span>
                </a>
              </li>
            {{-- @endif --}}


            <li class="nav-small-cap mt-4">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">COA / COW</span>
            </li> 

            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= base_url() ?>ekspor/coa" aria-expanded="false">
                <span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-certificate" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                    <path d="M5 8v-3a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-5"></path>
                    <path d="M6 14m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                    <path d="M4.5 17l-1.5 5l3 -1.5l3 1.5l-1.5 -5"></path>
                  </svg>
                </span>
                <span class="hide-menu">COA</span>
              </a>
            </li>  

            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= base_url() ?>ekspor/cow" aria-expanded="false">
                <span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-wallet" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"></path>
                    <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"></path>
                  </svg>
                </span>
                <span class="hide-menu">COW</span>
              </a>
            </li> 


            <li class="nav-small-cap mt-4">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Web Service</span>
            </li> 

            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= base_url() ?>services/checkizin" aria-expanded="false">
                <span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-certificate" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                    <path d="M5 8v-3a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-5"></path>
                    <path d="M6 14m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                    <path d="M4.5 17l-1.5 5l3 -1.5l3 1.5l-1.5 -5"></path>
                  </svg>
                </span>
                <span class="hide-menu">Cek Izin</span>
              </a>
            </li>  

            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= base_url() ?>services/checkntpn" aria-expanded="false">
                <span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-wallet" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"></path>
                    <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"></path>
                  </svg>
                </span>
                <span class="hide-menu">Cek NTPN</span>
              </a>
            </li> 

            
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= base_url() ?>/services/asuransi/find" aria-expanded="false">  
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-list-search" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M15 15m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                  <path d="M18.5 18.5l2.5 2.5"></path>
                  <path d="M4 6h16"></path>
                  <path d="M4 12h4"></path>
                  <path d="M4 18h4"></path>
                </svg>  
                <span class="hide-menu">Asuransi Find</span>
              </a>
            </li>
            
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= base_url() ?>/services/asuransi/survey" aria-expanded="false"> 
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-notes" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M5 3m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z"></path>
                  <path d="M9 7l6 0"></path>
                  <path d="M9 11l6 0"></path>
                  <path d="M9 15l4 0"></path>
                </svg>
                <span class="hide-menu">Asuransi Survey</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= base_url() ?>ekspor/rekapitulasi/laporan-bulanan" aria-expanded="false">
                <span>
                  <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z"/>
                  </svg>
                </span>
                <span class="hide-menu">Laporan Penerbitan</span>
              </a>
            </li>
                
            <li class="sidebar-item">
              <a class="sidebar-link" data-bs-toggle="slide">
                <span>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-table" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14z"></path>
                  <path d="M3 10h18"></path>
                  <path d="M10 3v18"></path>
                </svg>
                </span>
                <span class="hide-menu">Master Data</span> 
                <i class="fe me-2 fe-chevrons-down"></i>
              </a>
              
              <ul class="slide-menu collapse"> 
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url() ?>management/npwp" aria-expanded="false">
                    <span>
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-dollar" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                      <path d="M6 21v-2a4 4 0 0 1 4 -4h3"></path>
                      <path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5"></path>
                      <path d="M19 21v1m0 -8v1"></path>
                      </svg>
                    </span>
                    <span class="hide-menu">NPWP</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url() ?>management/client" aria-expanded="false">
                    <span>
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-dollar" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                      <path d="M6 21v-2a4 4 0 0 1 4 -4h3"></path>
                      <path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5"></path>
                      <path d="M19 21v1m0 -8v1"></path>
                      </svg>
                    </span>
                    <span class="hide-menu">Data Client</span>
                  </a>
                </li> 
				
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url() ?>management/cabang" aria-expanded="false">
                    <span>
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home-search" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M21 12l-9 -9l-9 9h2v7a2 2 0 0 0 2 2h4.7"></path>
                      <path d="M9 21v-6a2 2 0 0 1 2 -2h2"></path>
                      <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                      <path d="M20.2 20.2l1.8 1.8"></path>
                      </svg>
                    </span>
                    <span class="hide-menu">Data Cabang</span>
                  </a>
                </li> 

                @if (session()->get('sess_role') == 1)
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url() ?>management/user" aria-expanded="false">
                    <span>
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-cog" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                      <path d="M6 21v-2a4 4 0 0 1 4 -4h2.5"></path>
                      <path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                      <path d="M19.001 15.5v1.5"></path>
                      <path d="M19.001 21v1.5"></path>
                      <path d="M22.032 17.25l-1.299 .75"></path>
                      <path d="M17.27 20l-1.3 .75"></path>
                      <path d="M15.97 17.25l1.3 .75"></path>
                      <path d="M20.733 20l1.3 .75"></path>
                      </svg>
                    </span>
                    <span class="hide-menu">Data User</span>
                  </a>
                </li> 
                @endif
				
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url() ?>management/penandatangan" aria-expanded="false">
                    <span>
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                      <path d="M6 21v-2a4 4 0 0 1 4 -4h3.5"></path>
                      <path d="M18.42 15.61a2.1 2.1 0 0 1 2.97 2.97l-3.39 3.42h-3v-3l3.42 -3.39z"></path>
                      </svg>
                    </span>
                    <span class="hide-menu">Data Penandatangan</span>
                  </a>
                </li> 
				
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url() ?>management/kota" aria-expanded="false">
                    <span>
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="1.5"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-building-community">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                      <path d="M8 9l5 5v7h-5v-4m0 4h-5v-7l5 -5m1 1v-6a1 1 0 0 1 1 -1h10a1 1 0 0 1 1 1v17h-8" />
                      <path d="M13 7l0 .01" />
                      <path d="M17 7l0 .01" />
                      <path d="M17 11l0 .01" />
                      <path d="M17 15l0 .01" />
                    </svg>
                    </span>
                    <span class="hide-menu">Data Kota</span>
                  </a>
                </li> 
              </ul>
            </li> 
          </ul> 
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>