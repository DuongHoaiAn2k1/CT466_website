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
  <div
    v-show="listProductByName.length == 0"
    class="container bg-trasparent mt-2"
    style="position: relative"
  >
    <p style="margin-bottom: 0px; font-weight: 600">KHÔ CÁ CÀ MAU</p>
    <div
      v-show="!loading"
      class="row row-cols-1 row-cols-xs-2 row-cols-sm-2 row-cols-lg-5 g-3"
    >
      <div class="col hp" v-for="product in fishList" :key="product.product_id">
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
          <p v-show="product.product_quantity == 0" class="out-of-stock">
            Hết Hàng
          </p>
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
                v-show="product.product_quantity != 0"
                @click="addToCart(product.product_id)"
                class="btn btn-warning bold-btn"
                >Thêm</a
              >
              <a
                v-show="product.product_quantity == 0"
                class="btn btn-warning bold-btn"
                >Thêm</a
              >
            </div>
            <div class="clearfix mt-1">
              <span
                class="float-end"
                v-show="!product.liked"
                @click="createFavorite(product.product_id)"
              >
                <i
                  class="fa-regular fa-heart"
                  style="cursor: pointer; font-size: 24px"
                ></i>
              </span>
              <span
                class="float-end"
                v-show="product.liked"
                @click="deleteFavorite(product.product_id)"
              >
                <i
                  class="fa-solid fa-heart"
                  style="cursor: pointer; font-size: 24px"
                ></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="text-center my-5">
      <div class="v-spinner" v-show="loading">
        <div
          class="v-pulse v-pulse1"
          :style="[spinnerStyle, spinnerDelay1]"
        ></div>
        <div
          class="v-pulse v-pulse2"
          :style="[spinnerStyle, spinnerDelay2]"
        ></div>
        <div
          class="v-pulse v-pulse3"
          :style="[spinnerStyle, spinnerDelay3]"
        ></div>
      </div>
    </div>
  </div>

  <div
    v-show="listProductByName.length == 0"
    class="container bg-trasparent mt-2"
    style="position: relative"
  >
    <p style="margin-bottom: 0px; font-weight: 600">TÔM KHÔ</p>
    <div
      v-show="!loading"
      class="row row-cols-1 row-cols-xs-2 row-cols-sm-2 row-cols-lg-5 g-3"
    >
      <div
        class="col hp"
        v-for="product in shrimpList"
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
          <p v-show="product.product_quantity == 0" class="out-of-stock">
            Hết Hàng
          </p>
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
                v-show="product.product_quantity != 0"
                @click="addToCart(product.product_id)"
                class="btn btn-warning bold-btn"
                >Thêm</a
              >
              <a
                v-show="product.product_quantity == 0"
                class="btn btn-warning bold-btn"
                >Thêm</a
              >
            </div>
            <div class="clearfix mt-1">
              <span
                class="float-end"
                v-show="!product.liked"
                @click="createFavorite(product.product_id)"
              >
                <i
                  class="fa-regular fa-heart"
                  style="cursor: pointer; font-size: 24px"
                ></i>
              </span>
              <span
                class="float-end"
                v-show="product.liked"
                @click="deleteFavorite(product.product_id)"
              >
                <i
                  class="fa-solid fa-heart"
                  style="cursor: pointer; font-size: 24px"
                ></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="text-center my-5">
      <div class="v-spinner" v-show="loading">
        <div
          class="v-pulse v-pulse1"
          :style="[spinnerStyle, spinnerDelay1]"
        ></div>
        <div
          class="v-pulse v-pulse2"
          :style="[spinnerStyle, spinnerDelay2]"
        ></div>
        <div
          class="v-pulse v-pulse3"
          :style="[spinnerStyle, spinnerDelay3]"
        ></div>
      </div>
    </div>
  </div>

  <div
    v-show="listProductByName.length != 0"
    class="container bg-trasparent mt-2"
    style="position: relative"
  >
    <p style="margin-bottom: 0px; font-weight: 600">KẾT QUẢ TÌM KIẾM</p>
    <div
      v-show="!loading"
      class="row row-cols-1 row-cols-xs-2 row-cols-sm-2 row-cols-lg-5 g-3"
    >
      <div
        class="col hp"
        v-for="product in listProductByName"
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
          <p v-show="product.product_quantity == 0" class="out-of-stock">
            Hết Hàng
          </p>
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
                v-show="product.product_quantity != 0"
                @click="addToCart(product.product_id)"
                class="btn btn-warning bold-btn"
                >Thêm</a
              >
              <a
                v-show="product.product_quantity == 0"
                class="btn btn-warning bold-btn"
                >Thêm</a
              >
            </div>
            <div class="clearfix mt-1">
              <span
                class="float-end"
                v-show="!product.liked"
                @click="createFavorite(product.product_id)"
              >
                <i
                  class="fa-regular fa-heart"
                  style="cursor: pointer; font-size: 24px"
                ></i>
              </span>
              <span
                class="float-end"
                v-show="product.liked"
                @click="deleteFavorite(product.product_id)"
              >
                <i
                  class="fa-solid fa-heart"
                  style="cursor: pointer; font-size: 24px"
                ></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="text-center my-5">
      <div class="v-spinner" v-show="loading">
        <div
          class="v-pulse v-pulse1"
          :style="[spinnerStyle, spinnerDelay1]"
        ></div>
        <div
          class="v-pulse v-pulse2"
          :style="[spinnerStyle, spinnerDelay2]"
        ></div>
        <div
          class="v-pulse v-pulse3"
          :style="[spinnerStyle, spinnerDelay3]"
        ></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import "../assets/card.css";
import "../assets/css/PulseLoader.css";
import usePulseLoader from "../assets/js/PulseLoader.js";
import categoryService from "@/services/category.service";
import favoriteService from "@/services/favorite.service";
import productService from "@/services/product.service";
import { useSearchStore } from "@/stores/search";
import { onMounted, reactive, ref, watch } from "vue";
import { useAuthStore } from "@/stores/auth";
import cartService from "@/services/cart.service";
import { useFavoriteStore } from "@/stores/favorite";
import { useCartStore } from "@/stores/cart";
import { ElNotification, ElMessage } from "element-plus";
import { useRouter } from "vue-router";
const { loading, spinnerStyle, spinnerDelay1, spinnerDelay2, spinnerDelay3 } =
  usePulseLoader();
const router = useRouter();
const favoriteStore = useFavoriteStore();
const cartStore = useCartStore();
const authStore = useAuthStore();
const searchStore = useSearchStore();
const listCategory = reactive({});
const listFavorite = ref([]);
const listProduct = reactive({});
const fishList = ref([]);
const shrimpList = ref([]);
const listProductByName = ref([]);

const showSuccess = (message) => {
  ElMessage({
    message: message,
    type: "success",
  });
};
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
    fishList.value = response.data.slice(0, 5);
    shrimpList.value = response2.data.slice(0, 5);
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

const fetchListFavorite = async () => {
  try {
    const response = await favoriteService.getByUser();
    listFavorite.value = response.data;
    favoriteStore.setFavorite(response.data);
    console.log("List favorite: ", response);
  } catch (error) {
    console.log(error.response);
  }
};

const fetchProductFromName = async (data) => {
  try {
    const response = await productService.getProductByName({
      product_name: data,
    });
    listProductByName.value = response.data;
    console.log("Product By Name: ", listProductByName);
  } catch (error) {
    console.log(error.response);
  }
};
const deleteFavorite = async (productId) => {
  // console.log(productId);
  try {
    const response = await favoriteService.delete(productId);
    fetchListFavorite();
    showSuccess("Đã loại bỏ khỏi danh sách yêu thích");
    setTimeout(() => {
      updateFishListWithLikes();
      updateShrimpListWithLikes();
    }, 1000);
  } catch (error) {
    console.log(error.response);
  }
};

const createFavorite = async (productId) => {
  try {
    const response = await favoriteService.create({ product_id: productId });
    fetchListFavorite();
    showSuccess("Thêm vào danh sách yêu thích thành công");
    setTimeout(() => {
      updateFishListWithLikes();
      updateShrimpListWithLikes();
    }, 1000);
  } catch (error) {
    console.log(error.response);
  }
};
const updateFishListWithLikes = () => {
  fishList.value.forEach((product) => {
    const isLiked = listFavorite.value.some(
      (favorite) => favorite.product_id === product.product_id
    );
    product.liked = isLiked;
  });
};

const updateShrimpListWithLikes = () => {
  shrimpList.value.forEach((product) => {
    const isLiked = listFavorite.value.some(
      (favorite) => favorite.product_id === product.product_id
    );
    product.liked = isLiked;
  });
};

watch(
  () => searchStore.dataSearch,
  (newValue, oldValue) => {
    if (newValue !== oldValue) {
      // alert("Thay đổi");
      fetchProductFromName(searchStore.dataSearch);
    }
  }
);

onMounted(() => {
  fetchListCategory();
  fetchListProduct();
  fetchList();
  fetchListFavorite();
  if (searchStore.dataSearch) {
    fetchProductFromName(searchStore.dataSearch);
  }
  cartStore.fetchCartCount();
  setTimeout(() => {
    updateFishListWithLikes();
    updateShrimpListWithLikes();
    loading.value = false;
    console.log(fishList);
  }, 3000);
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
.out-of-stock {
  font-size: 24px;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: red;
  font-weight: bold;
}
.design-heart-red {
  background-color: red;
}
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
