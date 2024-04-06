<template>
  <footer class="bg-22222222 text-center text-lg-start">
    <!-- Grid container -->
    <div class="container p-4">
      <div class="row mb-3 py-2">
        <div class="col">
          Giao hàng và thu tiền tận nơi toàn quốc Hỗ trợ 24/7: 0943 808 107
        </div>
        <div class="col">
          Tìm chúng tôi &nbsp;
          <a href="https://www.facebook.com/profile.php?id=100031101393923"
            ><i class="fa-brands fa-facebook"></i
          ></a>
          &nbsp;
          <a href="https://www.instagram.com/an.assembly.12/"
            ><i class="fa-brands fa-instagram"></i
          ></a>
          &nbsp;
          <a><i class="fa-brands fa-telegram"></i></a>
          &nbsp;
          <a><i class="fa-brands fa-twitter"></i></a>
        </div>
      </div>
      <!--Grid row-->
      <div class="row">
        <hr />

        <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
          <h5 class="text-uppercase mb-2">Liên hệ</h5>
          <ul>
            <li class="address">
              94 3/2 Xuân Khánh, Ninh Kiều, Cần THơ <br />(Địa chỉ cũ ấp 10 xã
              Khánh Tiến Huyện U Minh Tỉnh Cà Mau)
            </li>
            <li class="tel"><a href="tel:0943 808 107">0943 808 107</a></li>
            <li class="phone"><a href="tel:0976 146 523">0976 146 523</a></li>
            <li class="time">Thứ 2-6: 8h - 20h; Thứ 7-CN: 9h - 17h</li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
          <h5 class="text-uppercase mb-2">Hỗ trợ</h5>

          <ul>
            <li class="nav-item me-2 me-lg-0 d-none d-md-inline-block">
              <router-link class="nav-link" :to="{ name: 'shipping' }"
                ><i class="fas fa-credit-card"></i> Chính sách giao
                hàng</router-link
              >
            </li>
            <li class="nav-item me-2 me-lg-0">
              <router-link class="nav-link" :to="{ name: 'guide' }"
                ><i class="fas fa-info"></i> Hướng dẫn mua hàng</router-link
              >
            </li>
            <li class="nav-item me-2 me-lg-0">
              <router-link class="nav-link" :to="{ name: 'contact' }"
                ><i class="fas fa-map-marker-alt"></i>Thông tin liên
                hệ</router-link
              >
            </li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
          <h5 class="text-uppercase mb-2">Danh mục</h5>

          <ul>
            <li><a>Khô cá Cà Mau</a></li>
            <li><a>Tôm khô Cà Mau</a></li>
            <li><a>Bánh phồng tôm</a></li>
            <li><a>Khô 1 nắng</a></li>
            <li><a>Mực khô Cà Mau</a></li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
          <h5 class="text-uppercase mb-2">Đối tác</h5>

          <ul>
            <li>
              <a href="https://dacsan4u.com" target="_blank">Đặc sản 4K</a>
            </li>
            <li>
              <a href="https://tomkhocamau.net" target="_blank"
                >Tôm khô Cà Mau</a
              >
            </li>
            <li>
              <a href="https://banhphongtom.net" target="_blank"
                >Bánh phồng tôm miền Tây</a
              >
            </li>
          </ul>
        </div>
      </div>
      <!--Grid row-->
    </div>
    <!-- Grid container -->

    <!-- Copyright -->
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05)">
      © 2024 B2014552 - Dương Hoài Ân
    </div>
    <!-- Copyright -->
  </footer>
</template>

<script setup></script>

<style scoped>
.bg-22222222 {
  background-color: #22222222 !important;
}
ul {
  padding: 0px;
}
ul li {
  list-style: none;
}
a {
  text-decoration: none;
  color: black;
}
</style>
