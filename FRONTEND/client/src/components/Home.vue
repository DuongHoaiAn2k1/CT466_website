<template>
  <div class="container">
    <el-carousel indicator-position="outside" height="250px">
      <el-carousel-item
        v-for="item in 3"
        :key="item"
        :class="'carousel-item-' + item"
      >
        <!-- <h3 text="2xl" justify="center">{{ item }}</h3> -->
      </el-carousel-item>
    </el-carousel>
  </div>

  <div class="container">
    <span style="font-weight: 600; font-size: 20px">Danh mục sản phẩm</span>
    <nav class="shadow bg-body border border-1 rounded border-secondary">
      <div class="row px-2">
        <div class="col text-center">
          <a href="#khocaca"
            ><img
              class="border border-2 rounded-circle"
              src="../assets/icon/ca.jpg"
              alt=""
              height="65"
              width="65"
            />
            <p>Khô cá Cà (Mau 199)</p></a
          >
        </div>
        <div class="col text-center">
          <a href="#tomkho"
            ><img
              class="border border-2 rounded-circle"
              src="../assets/icon/tom.jpg"
              alt=""
              height="64"
              width="64"
            />
            <p>Tôm khô Cà (Mau)</p></a
          >
        </div>
        <div class="col text-center">
          <a href="#banhphongtom"
            ><img
              class="border border-2 rounded-circle"
              src="../assets/icon/banhphong.jpg"
              alt=""
              height="64"
              width="64"
            />
            <p>Bánh phồng tôm</p>
          </a>
        </div>
        <div class="col text-center">
          <a href="#khong1nang"
            ><img
              class="border border-2 rounded-circle"
              src="../assets/icon/kho1nang.jpg"
              alt=""
              height="64"
              width="64"
            />
            <p>Khô 1 nắng</p>
          </a>
        </div>
        <div class="col text-center">
          <a href="#muckho"
            ><img
              class="border border-2 rounded-circle"
              src="../assets/icon/muckho.jpg"
              alt=""
              height="64"
              width="64"
            />
            <p>Mực khô Cà (Mau)</p>
          </a>
        </div>
        <div class="col text-center">
          <a href="#matongca"
            ><img
              class="border border-2 rounded-circle"
              src="../assets/icon/matong.webp"
              alt=""
              height="64"
              width="64"
            />
            <p>Mật ong Cà (Mau)</p>
          </a>
        </div>
        <div class="col text-center">
          <a href="#banhkemut"
            ><img
              class="border border-2 rounded-circle"
              src="../assets/icon/banhkeo.jpg"
              alt=""
              height="64"
              width="64"
            />
            <p>Bánh - Kẹo - Mút</p>
          </a>
        </div>
        <div class="col text-center">
          <a href="#mamngonca"
            ><img
              class="border border-2 rounded-circle"
              src="../assets/icon/mam.jpg"
              alt=""
              height="64"
              width="64"
            />
            <p>Mắm ngon Cà (Mau)</p>
          </a>
        </div>
        <div class="col text-center">
          <a href="#nemcha"
            ><img
              class="border border-2 rounded-circle"
              src="../assets/icon/nemtra.jpg"
              alt=""
              height="64"
              width="64"
            />
            <p>Nem - Chá</p>
          </a>
        </div>
      </div>
    </nav>
  </div>
</template>

<script lang="ts" setup></script>

<style scoped>
nav {
  background-color: #fff;
  color: #fff;
  display: flex;
  justify-content: center;
  padding: 10px 0;
}

nav ul {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
}

nav li {
  margin: 0 10px;
}

nav a {
  color: #000;
  text-decoration: none;
}

nav a:hover {
  color: #ccc;
}
.el-carousel__item h3 {
  color: #475669;
  opacity: 0.75;
  line-height: 200px;
  margin: 0;
  text-align: center;
}

.carousel-item-1 {
  /* background-color: #000; */
  background-image: url("https://i.ytimg.com/vi/-KEJ_aJXeWE/maxresdefault.jpg");
  background-repeat: no-repeat, repeat;
  background-size: cover;
}
.carousel-item-2 {
  /* background-color: #000; */
  background-image: url("https://motogo.vn/wp-content/uploads/2020/02/mui-ca-mau.jpg");
  background-repeat: no-repeat, repeat;
  background-size: cover;
}
.carousel-item-3 {
  /* background-color: #000; */
  background-image: url("https://nhn.1cdn.vn/2023/11/14/111-1-.jpg");
  background-repeat: no-repeat, repeat;
  background-size: cover;
}

/* .el-carousel__item:nth-child(2n) {
  background-color: #99a9bf;
}

.el-carousel__item:nth-child(2n + 1) {
  background-color: #d3dce6;
} */

@media (max-width: 739px) {
}

@media (min-width: 740px) and (max-width: 1023px) {
}
</style>
