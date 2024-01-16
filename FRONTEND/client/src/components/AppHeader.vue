<template>
  <!--Main Navigation-->
  <header class="mb-2">
    <!-- Jumbotron -->
    <div class="p-3 text-center bg-white border-bottom top-header">
      <div class="container super-container">
        <div class="row contain-main-header">
          <div
            class="col-md-4 d-flex justify-content-center justify-content-md-start mb-3 mb-md-0 contain-big-logo"
          >
            <router-link :to="{ name: 'home' }" class="ms-md-2">
              <img class="logo" src="../assets/logo.jpg" />
            </router-link>

            <div class="after-logo">
              <a class="text-reset me-5" href="#">
                <span><i class="fas fa-shopping-cart cart-design"></i></span>
                <span
                  class="badge rounded-pill badge-notification bg-danger sub-cart-design"
                  >1</span
                >
              </a>
              <div class="dropdown">
                <i
                  class="fas fa-user dropdown-toggle user-design"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                ></i>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Action</a></li>
                  <li><a class="dropdown-item" href="#">Another action</a></li>
                  <li>
                    <a class="dropdown-item" href="#">Something else here</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <div class="col-md-4 contain-form">
            <form class="d-flex input-group w-auto my-auto mb-3 mb-md-0">
              <input
                autocomplete="off"
                type="search"
                class="form-control rounded"
                placeholder="Search"
              />
              <span
                class="input-group-text d-none d-lg-flex border border-2 icon-search"
                ><i class="fas fa-search"></i
              ></span>
            </form>
          </div>

          <div
            class="col-md-4 d-flex justify-content-center justify-content-md-end align-items-center contain-big-left"
          >
            <div class="d-flex">
              <!-- Cart -->
              <a class="text-reset me-5" href="#">
                <span><i class="fas fa-shopping-cart cart-design"></i></span>
                <span
                  class="badge rounded-pill badge-notification bg-danger sub-cart-design"
                  >1</span
                >
              </a>

              <!-- Notification -->
              <div class="dropdown">
                <i
                  class="fas fa-user dropdown-toggle user-design"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                ></i>
                <ul class="dropdown-menu">
                  <li>
                    <router-link :to="{ name: 'login' }" class="dropdown-item"
                      >Đăng nhập</router-link
                    >
                  </li>
                  <li>
                    <router-link
                      :to="{ name: 'register' }"
                      class="dropdown-item"
                      href="#"
                      >Đăng ký</router-link
                    >
                  </li>
                </ul>
              </div>
              <!-- User -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Jumbotron -->

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white nav-design">
      <!-- Container wrapper -->
      <div class="container justify-content-center justify-content-md-between">
        <!-- Left links -->
        <ul class="navbar-nav flex-row text-center list-menu">
          <li class="nav-item me-2 me-lg-0 d-none d-md-inline-block">
            <a class="nav-link" href="#"
              ><i class="fab fa-product-hunt"></i> Đặc sản Cà Mau</a
            >
          </li>
          <li class="nav-item me-2 me-lg-0 d-none d-md-inline-block">
            <a class="nav-link" href="#"
              ><i class="fas fa-utensils"></i> Ẩm thực Cà Mau</a
            >
          </li>
          <li class="nav-item me-2 me-lg-0 d-none d-md-inline-block">
            <a class="nav-link" href="#"
              ><i class="fas fa-briefcase"></i> Du lịch Cà Mau</a
            >
          </li>
          <li class="nav-item me-2 me-lg-0 d-none d-md-inline-block">
            <a class="nav-link" href="#"
              ><i class="fas fa-file-alt"></i> Mẹo hay</a
            >
          </li>
          <li class="nav-item me-2 me-lg-0 d-none d-md-inline-block">
            <a class="nav-link" href="#"
              ><i class="fas fa-info"></i> Hướng dẫn mua hàng</a
            >
          </li>
          <li class="nav-item me-2 me-lg-0 d-none d-md-inline-block">
            <a class="nav-link" href="#"
              ><i class="fas fa-credit-card"></i> Thanh toán</a
            >
          </li>
          <li class="nav-item me-2 me-lg-0 d-none d-md-inline-block">
            <a class="nav-link" href="#"
              ><i class="fas fa-map-marker-alt"></i> Liên hệ</a
            >
          </li>
        </ul>
        <!-- Left links -->
      </div>
      <!-- Container wrapper -->
      <div class="moblie-menu" @click="toggleNavigatorOne">
        <i class="fas fa-bars"></i>
      </div>
    </nav>
    <!-- Navbar -->
  </header>
  <div class="list-group list-group-light sub-menu-mobile mb-1" v-if="isClick">
    <a
      href="#"
      class="list-group-item list-group-item-action px-3 active-click"
    >
      Đặc sản Cà Mau</a
    >
    <hr />
    <a href="#" class="list-group-item list-group-item-action px-3 border-0"
      >Ẩm thực Cà Mau</a
    >
    <hr />
    <a href="#" class="list-group-item list-group-item-action px-3 border-0"
      >Du Lịch Cà Mau</a
    >
    <hr />
    <a href="#" class="list-group-item list-group-item-action px-3 border-0"
      >Mẹo hay</a
    >
    <hr />
    <a class="list-group-item list-group-item-action px-3 border-0"
      >Hướng dẫn mua hàng</a
    >
    <hr />
    <a class="list-group-item list-group-item-action px-3 border-0"
      >Thanh toán</a
    >
    <hr />
    <a class="list-group-item list-group-item-action px-3 border-0">Liên hệ</a>
    <hr />
  </div>
  <!--Main Navigation-->
</template>

<script setup>
import { ref } from "vue";
const isClick = ref(false);

const toggleNavigatorOne = () => {
  isClick.value = !isClick.value;
};
</script>

<style scoped>
.logo {
  width: 180px;
  height: 80px;
  object-fit: contain;
}
.after-logo {
  display: none;
}
.top-header {
  padding: 0px !important;
}

.top-header .contain-form {
  padding-top: 8px !important;
}
.top-header .contain-form .icon-search:hover {
  background-color: #0075b1;
  color: #fff;
}

.cart-design {
  font-size: 30px;
  color: #0075b1;
}

.user-design {
  font-size: 30px;
  color: #0075b1;
}

.sub-cart-design {
  font-size: 16px;
}

.nav-design {
  background-color: #0075b1 !important;
}
.list-menu a {
  font-weight: 600;
  color: #fff;
}
.list-menu a:hover i,
.list-menu a:hover {
  color: #000;
}
.list-menu a i {
  font-size: 24px;
  padding: 0 8px;
}

.moblie-menu {
  display: none;
}
.sub-menu-mobile {
  display: none;
}
@media (max-width: 739px) {
  .sub-menu-mobile {
    display: block;
    border-radius: 0 !important;
  }
  hr {
    margin: 0;
  }
  .active-click {
    background-color: #f5f5dc;
    font-weight: 600;
  }
  header {
    margin-bottom: 0px !important;
  }
  .top-header {
    padding: 0 !important;
  }
  .super-container {
    padding: 0px;
  }
  .nav-design {
    height: 48px;
  }
  .moblie-menu {
    display: block;
    margin-left: 18px;
    font-size: 20px;
    color: #fff;
  }

  .contain-big-logo {
    justify-content: space-between !important;
    margin: 0 !important;
  }

  .after-logo {
    margin-top: 30px;
    display: flex;
    justify-content: space-evenly;
  }

  .after-logo a {
    margin: 0px !important;
    margin-right: 16px !important;
  }

  .contain-big-left {
    display: none !important;
  }
  .contain-form {
    display: none;
  }
}

@media (min-width: 740px) and (max-width: 1023px) {
  .nav-design {
    height: 48px;
  }
}
</style>
