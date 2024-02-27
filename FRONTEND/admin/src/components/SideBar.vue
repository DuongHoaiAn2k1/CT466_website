<template>
  <div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
      <div class="sb-sidenav-menu">
        <div class="nav">
          <router-link :to="{ name: 'home' }" class="nav-link collapsed">
            <div class="sb-nav-link-icon">
              <i class="fa-solid fa-list"></i>
            </div>
            DANH MỤC
          </router-link>
          <a
            class="nav-link collapsed"
            href="#"
            data-bs-toggle="collapse"
            data-bs-target="#collapsePages"
            aria-expanded="false"
            aria-controls="collapsePages"
          >
            <div class="sb-nav-link-icon">
              <i class="fa-brands fa-product-hunt"></i>
            </div>
            SẢN PHẨM
            <div class="sb-sidenav-collapse-arrow">
              <i class="fas fa-angle-down"></i>
            </div>
          </a>
          <div
            class="collapse"
            id="collapsePages"
            aria-labelledby="headingTwo"
            data-bs-parent="#sidenavAccordion"
          >
            <nav
              class="sb-sidenav-menu-nested nav accordion"
              id="sidenavAccordionPages"
            >
              <router-link
                class="nav-link collapsed"
                :to="{ name: 'create-product' }"
              >
                THÊM SẢN PHẨM
              </router-link>

              <router-link class="nav-link collapsed" :to="{ name: 'product' }">
                DANH SÁCH SẢN PHẨM
                <div class="sb-sidenav-collapse-arrow"></div>
              </router-link>
            </nav>
          </div>
          <router-link class="nav-link collapsed" :to="{ name: 'customer' }">
            <div class="sb-nav-link-icon">
              <i class="fa-solid fa-users"></i>
            </div>
            KHÁCH HÀNG
          </router-link>
          <a class="nav-link" href="tables.html">
            <div class="sb-nav-link-icon">
              <i class="fa-solid fa-clipboard"></i>
            </div>
            ĐƠN HÀNG
          </a>
          <a class="nav-link" href="tables.html">
            <div class="sb-nav-link-icon">
              <i class="fa-solid fa-chart-simple"></i>
            </div>
            THỐNG KÊ
          </a>
          <a class="nav-link" href="tables.html">
            <div class="sb-nav-link-icon">
              <i class="fa-solid fa-user-tie"></i>
            </div>
            NHÂN VIÊN
          </a>
          <a class="nav-link" href="tables.html">
            <div class="sb-nav-link-icon">
              <i class="fa-solid fa-message"></i>
            </div>
            TIN NHẮN
          </a>
        </div>
      </div>
      <div class="sb-sidenav-footer"></div>
    </nav>
  </div>
</template>
