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
      <div class="row">
        <div
          v-for="category in listCategory.value"
          :key="category.category_id"
          class="col text-center"
        >
          <router-link
            :to="{ name: 'product', params: { id: category.category_id } }"
            ><img
              class="border border-2 rounded-circle"
              :src="
                'http://127.0.0.1:8000/storage/' +
                JSON.parse(category.category_img)[0]
              "
              alt=""
              height="65"
              width="65"
            />
            <p>{{ category.category_name }}</p></router-link
          >
        </div>
      </div>
    </nav>
  </div>
  <div class="container bg-trasparent mt-2" style="position: relative">
    <p style="margin-bottom: 0px; font-weight: 600">KHÔ CÁ CÀ MAU</p>
    <div class="row row-cols-1 row-cols-xs-2 row-cols-sm-2 row-cols-lg-5 g-3">
      <div
        class="col hp"
        v-for="product in fishList.value"
        :key="product.product_id"
      >
        <div class="card h-100 shadow-sm">
          <router-link
            :to="{ name: 'product-detail', params: { id: product.product_id } }"
          >
            <img
              :src="
                'http://127.0.0.1:8000/storage/' +
                JSON.parse(product.product_img)[0]
              "
              class="card-img-top"
              alt="product.title"
            />
          </router-link>

          <!-- <div class="label-top shadow-sm">
            <a class="text-white" href="#">asus</a>
          </div> -->
          <div class="card-body">
            <h5 style="margin-bottom: 0px" class="card-title">
              <p class="name-product">{{ product.product_name }}</p>
              <p
                style="margin-bottom: 0px !important; font-weight: 600"
                class="mt-2 text-danger text-end"
              >
                {{ formatCurrency(product.product_price) }}/kg
              </p>
            </h5>

            <div class="d-grid gap-2">
              <a
                @click="addToCart(product.product_id)"
                class="btn btn-warning bold-btn"
                >Thêm</a
              >
            </div>
            <div class="clearfix mt-1">
              <span class="float-end">
                <i
                  class="fa-regular fa-heart"
                  style="cursor: pointer; font-size: 24px"
                ></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container bg-trasparent mt-2" style="position: relative">
    <p style="margin-bottom: 0px; font-weight: 600">TÔM KHÔ</p>
    <div class="row row-cols-1 row-cols-xs-2 row-cols-sm-2 row-cols-lg-5 g-3">
      <div
        class="col hp"
        v-for="product in shrimpList.value"
        :key="product.product_id"
      >
        <div class="card h-100 shadow-sm">
          <a href="#">
            <img
              :src="
                'http://127.0.0.1:8000/storage/' +
                JSON.parse(product.product_img)[0]
              "
              class="card-img-top"
              alt="product.title"
            />
          </a>

          <!-- <div class="label-top shadow-sm">
            <a class="text-white" href="#">asus</a>
          </div> -->
          <div class="card-body">
            <h5 style="margin-bottom: 0px" class="card-title">
              <p class="name-product">{{ product.product_name }}</p>
              <p
                style="margin-bottom: 0px !important; font-weight: 600"
                class="mt-2 text-danger text-end"
              >
                {{ formatCurrency(product.product_price) }}/kg
              </p>
            </h5>

            <div class="d-grid gap-2">
              <a href="#" class="btn btn-warning bold-btn">Thêm</a>
            </div>
            <div class="clearfix mt-1">
              <span class="float-end">
                <i
                  class="fa-regular fa-heart"
                  style="cursor: pointer; font-size: 24px"
                ></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import "../assets/card.css";
import categoryService from "@/services/category.service";
import productService from "@/services/product.service";
import { onMounted, reactive, ref } from "vue";
import { useAuthStore } from "@/stores/auth";
import cartService from "@/services/cart.service";
import { useCartStore } from "@/stores/cart";
import { ElNotification, ElMessage } from "element-plus";
import { useRouter } from "vue-router";
const router = useRouter();
const cartStore = useCartStore();
const authStore = useAuthStore();
const listCategory = reactive({});

const listProduct = reactive({});
const fishList = reactive({});
const shrimpList = reactive({});
const addToCartSuccess = () => {
  ElMessage({
    message: "Thêm vào giỏ hàng thành công",
    type: "success",
  });
};
const addToCartWarning = () => {
  ElMessage({
    message: "Đã quá số lượng cho phép",
    type: "warning",
  });
};

const warning = () => {
  ElNotification({
    title: "Warning",
    message: "Vui lòng đăng nhập để tiến hành mua sản phẩm",
    type: "warning",
  });
};
const fetchList = async () => {
  try {
    const response = await productService.getProductFromCategoryName({
      category_name: "Khô cá Cà Mau",
    });
    const response2 = await productService.getProductFromCategoryName({
      category_name: "Tôm khô Cà Mau",
    });
    console.log("Cá: ", response);
    fishList.value = response.data;
    shrimpList.value = response2.data;
  } catch (error) {
    console.log(error.response);
  }
};

const fetchListCategory = async () => {
  try {
    const response = await categoryService.getAll();
    listCategory.value = response.listCategory;
    console.log(response);
  } catch (error) {
    console.log(error.response);
  }
};

const fetchListProduct = async () => {
  try {
    const response = await productService.getAll();
    listProduct.value = response.listProduct;
    // console.log(response);
    // console.log(listProduct);
  } catch (error) {
    console.log(error.response);
  }
};

onMounted(() => {
  fetchListCategory();
  fetchListProduct();
  fetchList();
});
const addToCart = async (product_id) => {
  if (authStore.isUserLoggedIn) {
    try {
      const user_id = authStore.user_id;
      const response = await cartService.create({
        user_id: user_id,
        product_id: product_id,
        quantity: 1,
      });
      await cartStore.fetchCartCount();
      console.log("Ket qua them: ", response);
      addToCartSuccess();
    } catch (error) {
      console.log(error.response);
      if (error.response.data.message === "Qúa số lượng cho phép") {
        addToCartWarning();
      }
    }
  } else {
    warning();
    setTimeout(() => {
      router.push({ name: "login" });
    }, 500);
  }
};

const formatCurrency = (amount) => {
  return (
    amount?.toLocaleString("vi-VN", {
      style: "currency",
      currency: "VND",
    }) || ""
  );
};
</script>

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
