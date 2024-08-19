 <!-- Sidebar -->
 <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

     <!-- Sidebar - Brand -->
     <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
         <div>
             <img width="30px" height="30px" src="<?= base_url() ?>/img/siap.png" alt="">
         </div>
         <div class="sidebar-brand-text mx-3 text-start">SIAB
             <p style="font-size: 5px;" class="h6">Sistem Informasi Absensi</p>
         </div>

     </a>

     <!-- Divider -->
     <hr class="sidebar-divider my-0">

     <!-- Nav Item - Dashboard -->
     <li class="nav-item">
         <a class="nav-link" href="<?= base_url() ?>admin/dashboard">
             <i class="fas fa-fw fa-tachometer-alt"></i>
             <span>Dashboard</span></a>
     </li>
     <!-- Nav Item - Pages Collapse Menu -->
     <!-- Heading -->
     <li class="nav-item">
         <a class="nav-link" href="<?= base_url() ?>admin/laporanabsensi">
             <i class="fas fa-fw fa-tachometer-alt"></i>
             <span>Laporan Absensi</span></a>
     </li>
     <!-- Nav Item - Tables -->
     <li class="nav-item">
         <a class="nav-link" href="<?= base_url() ?>admin/manajementlokasi">
             <i class="fas fa-fw fa-tachometer-alt"></i>
             <span>Manajement Lokasi</span></a>
     </li>

     <li class="nav-item">
         <a class="nav-link" href="<?= base_url() ?>admin/manajementuser">
             <i class="fas fa-fw fa-tachometer-alt"></i>
             <span>User Management</span></a>
     </li>

     <li class="nav-item">
         <a class="nav-link" href="<?= base_url() ?>admin/penempatan">
             <i class="fas fa-fw fa-tachometer-alt"></i>
             <span>Penempatan</span></a>
     </li>

     <!-- Divider -->
     <hr class="sidebar-divider d-none d-md-block">

     <!-- Sidebar Toggler (Sidebar) -->
     <div class="text-center d-none d-md-inline">
         <button class="rounded-circle border-0" id="sidebarToggle"></button>
     </div>

 </ul>
 <!-- End of Sidebar -->