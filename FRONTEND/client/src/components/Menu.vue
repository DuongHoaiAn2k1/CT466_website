<template>
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
</template>

<script setup>
import categoryService from "@/services/category.service";
import { onMounted, reactive, ref } from "vue";

const listCategory = reactive({});
const fetchListCategory = async () => {
  try {
    const response = await categoryService.getAll();
    listCategory.value = response.listCategory;
  } catch (error) {
    console.log(error.response);
  }
};

onMounted(() => {
  fetchListCategory();
});
</script>
