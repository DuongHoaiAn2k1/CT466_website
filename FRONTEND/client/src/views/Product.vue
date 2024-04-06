<template>
  <MenuCategory />
  <div class="container bg-trasparent mt-2" style="position: relative">
    <p style="margin-bottom: 0px; font-weight: 600">
      {{ CategoryData.category_name }}
    </p>
    <div
      v-show="!loading"
      class="row row-cols-1 row-cols-xs-2 row-cols-sm-2 row-cols-lg-5 g-3"
    >
      <div
        class="col hp"
        v-for="product in listProduct[categoryID]"
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
import MenuCategory from "@/components/Menu.vue";
import { computed, onMounted, reactive, watch, ref, watchEffect } from "vue";
import favoriteService from "@/services/favorite.service";
import productService from "@/services/product.service";
import { useAuthStore } from "@/stores/auth";
import cartService from "@/services/cart.service";
import { useRoute } from "vue-router";
import categoryService from "@/services/category.service";
import { useSearchStore } from "@/stores/search";
import { useCartStore } from "@/stores/cart";
import { ElNotification, ElMessage } from "element-plus";
const CategoryData = reactive({
  category_name: "",
});

const { loading, spinnerStyle, spinnerDelay1, spinnerDelay2, spinnerDelay3 } =
  usePulseLoader();
const authStore = useAuthStore();
const cartStore = useCartStore();
const route = useRoute();
const searchStore = useSearchStore();
const categoryID = computed(() => route.params.id);
const listProduct = ref([]);
const listFavorite = ref([]);
const fetchListFavorite = async () => {
  try {
    const response = await favoriteService.getByUser();
    listFavorite.value = response.data;
    console.log("List favorite: ", response);
  } catch (error) {
    console.log(error.response);
  }
};
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
const fetchListProduct = async () => {
  try {
    const response = await productService.getGroupByCategory();
    listProduct.value = response.groupedProducts;
  } catch (error) {
    console.log(error.response);
  }
};

const fetchCategory = async () => {
  try {
    const response = await categoryService.get(categoryID.value);
    CategoryData.category_name = response.category.category_name;
    console.log("hi: ", response.category);
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
      updateListProductWithLikes();
    }, 1000);
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
      updateListProductWithLikes();
    }, 1000);
  } catch (error) {
    console.log(error.response);
  }
};

const updateListProductWithLikes = () => {
  listProduct.value[categoryID.value].forEach((product) => {
    const isLiked = listFavorite.value.some(
      (favorite) => favorite.product_id === product.product_id
    );
    product.liked = isLiked;
  });
};
onMounted(() => {
  fetchListProduct();
  fetchListFavorite();
  fetchCategory();
  cartStore.fetchCartCount();
  console.log(CategoryData);
  searchStore.setDataSearch("");
  setTimeout(() => {
    updateListProductWithLikes();
    loading.value = false;
  }, 2000);
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
watchEffect(() => {
  fetchCategory();
  fetchListFavorite();
});

watch(categoryID, () => {
  updateListProductWithLikes();
});

function formatCurrency(amount) {
  // Sử dụng hàm toLocaleString để định dạng số theo ngôn ngữ và quốc gia
  return amount.toLocaleString("vi-VN", {
    style: "currency",
    currency: "VND",
  });
}
</script>

<style>
.out-of-stock {
  font-size: 24px;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: red;
  font-weight: bold;
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
.card-title {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>
