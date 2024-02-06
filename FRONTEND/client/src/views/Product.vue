<template>
  <MenuCategory />
  <div class="container bg-trasparent mt-2" style="position: relative">
    <p style="margin-bottom: 0px; font-weight: 600">
      {{ CategoryData.category_name }}
    </p>
    <div class="row row-cols-1 row-cols-xs-2 row-cols-sm-2 row-cols-lg-5 g-3">
      <div
        class="col hp"
        v-for="product in listProduct.value"
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
import MenuCategory from "@/components/Menu.vue";
import { computed, onMounted, reactive, watch, ref, watchEffect } from "vue";
import productService from "@/services/product.service";
import { useRoute } from "vue-router";
import categoryService from "@/services/category.service";

const CategoryData = reactive({
  category_name: "",
});
const route = useRoute();

const categoryID = computed(() => route.params.id);
const listProduct = reactive({});

const fetchListProduct = async () => {
  try {
    const response = await productService.getProductFromCategoryId(
      categoryID.value
    );
    // console.log(response);
    listProduct.value = response.data;
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

onMounted(() => {
  fetchListProduct();
  fetchCategory();
  console.log(CategoryData);
});

watchEffect(() => {
  fetchListProduct();
  fetchCategory();
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
</style>
