<template>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <h1 class="mt-4">DANH SÁCH SẢN PHẨM</h1>
        <ol class="breadcrumb mb-4">
          <button type="button" class="btn btn-dark">
            <router-link
              style="text-decoration: none; color: #fff"
              :to="{ name: 'create-product' }"
              >Thêm (+)</router-link
            >
          </button>
        </ol>
        <!-- Button trigger modal -->

        <!-- Modal -->

        <div class="card mb-4">
          <div class="card-header">
            <i class="fas fa-table me-1"></i>
          </div>
          <div class="card-body">
            <div class="form-group pull-right contain-search">
              <input
                type="text"
                class="search form-control form-design"
                placeholder="Nhập từ khóa tìm kiếm"
                @change="handleSearch"
                v-model="search"
              />
            </div>
            <span class="counter pull-right"></span>
            <table class="table table-hover table-bordered results">
              <thead>
                <tr>
                  <th class="col text-center">STT</th>
                  <th class="col text-center">Tên sản phẩm</th>
                  <th class="col text-center">Hình ảnh</th>
                  <th class="col text-center">Giá</th>
                  <th class="col text-center">Số lượng</th>
                  <th class="col text-center">Ngày tạo</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(product, index) in datasearch"
                  :key="product.product_id"
                >
                  <th scope="row" class="text-center">{{ index + 1 }}</th>
                  <td class="text-center">{{ product.product_name }}</td>
                  <td class="text-center">
                    <img
                      :src="
                        'http://127.0.0.1:8000/storage/' +
                        JSON.parse(product.product_img)[0]
                      "
                      alt="Hình ảnh"
                      width="50px"
                    />
                  </td>

                  <td class="text-center">
                    {{ formatCurrency(product.product_price) }}
                  </td>
                  <td class="text-center">
                    {{ product.product_quantity }}
                  </td>
                  <td class="text-center">{{ product.created_at }}</td>
                  <td class="text-center">
                    <button
                      type="button"
                      class="btn btn-sm btn-secondary design-button"
                    >
                      <router-link
                        style="text-decoration: none; color: #fff"
                        :to="{
                          name: 'update-product',
                          params: { id: product.product_id },
                        }"
                        >Edit</router-link
                      >
                    </button>
                  </td>
                  <td class="text-center">
                    <el-popconfirm
                      confirm-button-text="Yes"
                      cancel-button-text="No"
                      width="200"
                      confirm-button-type="danger"
                      title="Bạn có muốn xóa?"
                      @confirm="handleDelete(product.product_id)"
                      @cancel="cancelEvent"
                    >
                      <template #reference>
                        <el-button v-show="index !== editingIndex" type="danger"
                          >Delete</el-button
                        >
                      </template>
                    </el-popconfirm>
                  </td>
                </tr>
              </tbody>
            </table>
            <div v-show="datasearch.length === 0">
              <p class="text-center">Không có sản phẩm nào</p>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
<script setup>
import { computed, onMounted, ref, reactive } from "vue";
import categoryService from "@/services/category.service";
import productService from "@/services/product.service";
import * as Yup from "yup";
import { ElLoading, ElNotification } from "element-plus";
import { InfoFilled } from "@element-plus/icons-vue";

const confirmEvent = () => {
  console.log("confirm!");
};
const cancelEvent = () => {
  console.log("cancel!");
};
const showSuccess = (message) => {
  ElNotification({
    title: "Success",
    message: message,
    type: "success",
  });
};

const showWarning = (message) => {
  ElNotification({
    title: "Warning",
    message: message,
    type: "warning",
  });
};

const schema = Yup.object().shape({
  category_name: Yup.string().required("Tên danh mục không được để trống"),
});
const listCategory = ref([]);
const listProduct = ref([]);
const search = ref("");

const fetchListCategory = async () => {
  try {
    const response = await categoryService.getAll();
    listCategory.value = response.listCategory;
    console.log(listCategory.value.listCategory);
  } catch (error) {
    console.log(error);
  }
};

const fetchListProduct = async () => {
  try {
    const response = await productService.getAll();
    listProduct.value = response.listProduct;
    console.log(response);
  } catch (error) {
    console.log(error);
  }
};

const deleteProduct = async (id) => {
  try {
    const response = await productService.delete(id);
    console.log(response);
  } catch (error) {
    console.log(error.response);
  }
};
onMounted(() => {
  fetchListCategory();
  fetchListProduct();
});

const handleSearch = () => {
  console.log(search.value);
  console.log("cc :", datasearch.value);
};

const datasearch = computed(() => {
  const dataSearch = String(search.value).trim();

  if (!dataSearch) {
    return listProduct.value;
  }

  return listProduct.value.filter((data) => {
    return String(data.product_name)
      .toLowerCase()
      .includes(dataSearch.toLowerCase());
  });
});

const handleDelete = (category_id) => {
  const loading = ElLoading.service({
    lock: true,
    text: "Đang xử lý...",
    background: "rbga(0,0,0, 0.7)",
  });

  setTimeout(() => {
    deleteProduct(category_id);
    loading.close();
    showSuccess("Xóa sản phẩm thành công");
    fetchListProduct();
  }, 2000);
};

function formatCurrency(amount) {
  // Sử dụng hàm toLocaleString để định dạng số theo ngôn ngữ và quốc gia
  return amount.toLocaleString("vi-VN", {
    style: "currency",
    currency: "VND",
  });
}
</script>

<style scoped>
.contain-search {
  display: flex;
}
.form-design {
  width: 220px;
  margin-left: 993px;
}

.design-input {
  border: none;
}
.design-button {
  padding: 4px 16px;
}
</style>
