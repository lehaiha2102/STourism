<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
      <div class="brand-logo d-flex align-items-center justify-content-between">
        <a href="{{ route('dashboard') }}" class="text-nowrap logo-img">
          <img src="../../assets/images/logos/favicon.png" width="180" alt="" />
        </a>
        <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
          <i class="ti ti-x fs-8"></i>
        </div>
      </div>
      <!-- Sidebar navigation-->
      <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
        <ul id="sidebarnav">
          {{-- <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Home</span>
          </li> --}}
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('dashboard') }}" aria-expanded="false">
              <span>
                <i class="ti ti-layout-dashboard"></i>
              </span>
              <span class="hide-menu">Trang chủ</span>
            </a>
          </li>
          {{-- <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">UI COMPONENTS</span>
          </li> --}}
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('category') }}" aria-expanded="false">
              <span>
                <i class="ti ti-article"></i>
              </span>
              <span class="hide-menu">Danh mục</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('product') }}" aria-expanded="false">
              <span>
                <i class="ti ti-alert-circle"></i>
              </span>
              <span class="hide-menu">Sản phẩm</span>
            </a>
          </li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('room') }}" aria-expanded="false">
              <span>
                <i class="ti ti-alert-circle"></i>
              </span>
                    <span class="hide-menu">Phòng</span>
                </a>
            </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('business') }}" aria-expanded="false">
              <span>
                <i class="ti ti-cards"></i>
              </span>
              <span class="hide-menu">Doanh nghiệp</span>
            </a>
          </li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('booking') }}" aria-expanded="false">
              <span>
                <i class="ti ti-file-description"></i>
              </span>
                    <span class="hide-menu">Đặt chỗ</span>
                </a>
            </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="#" aria-expanded="false">
              <span>
                <i class="ti ti-file-description"></i>
              </span>
              <span class="hide-menu">Đánh giá</span>
            </a>
          </li>
        </ul>
      </nav>
      <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
  </aside>
