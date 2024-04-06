<template>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <h1 class="mt-4">QUẢN LÝ KHO</h1>
        <!-- Modal -->

        <div class="card mb-4">
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

            <table class="table table-hover table-bordered results">
              <thead>
                <tr>
                  <th class="col text-center">STT</th>
                  <th class="col text-center">Tên sản phẩm</th>
                  <th class="col text-center">Hình ảnh</th>
                  <th class="col text-center">Số lượng còn trong kho</th>
                  <th class="col text-center"></th>
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
                    {{ product.product_quantity }}
                  </td>
                  <td class="text-center">
                    <button
                      class="btn border-none"
                      @click="
                        updateNumberHandle(
                          product.product_id,
                          product.product_quantity
                        )
                      "
                    >
                      <i class="fa-regular fa-pen-to-square"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="text-end">
              <el-pagination
                v-model:current-page="currentPage"
                @current-change="handleCurrentChange"
                small
                background
                layout="prev, pager, next"
                :total="Math.ceil(productsLength / pageSize) * 10"
                class="mt-4"
              />
            </div>
            <div v-show="datasearch.length === 0">
              <p class="text-center">Không có sản phẩm nào</p>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
  <el-dialog
    v-model="dialogFormVisible"
    title="ĐIỀU CHỈNH SỐ LƯỢNG"
    width="500"
  >
    <input class="form-control" type="text" v-model="quantityUpdate" />
    <div class="modal-footer mt-1">
      <button
        @click="dialogFormVisible = false"
        type="button"
        class="btn btn-secondary"
        data-bs-dismiss="modal"
      >
        Đóng
      </button>
      <button
        @click="updateQuantity(quantityUpdate, productIdUpdate)"
        type="button"
        class="btn btn-primary"
      >
        Cập nhật
      </button>
    </div>
  </el-dialog>
</template>
<script setup>
import { computed, onMounted, ref, reactive } from "vue";
import categoryService from "@/services/category.service";
import productService from "@/services/product.service";
import * as Yup from "yup";
import { ElLoading, ElNotification } from "element-plus";

const dialogFormVisible = ref(false);
const updateNumberBox = ref(false);
const currentPage = ref(1);
const pageSize = 8;
const productsLength = ref(0);
const formLabelWidth = "140px";
// const confirmEvent = () => {
//   console.log("confirm!");
// };
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

const productIdUpdate = ref(0);
const quantityUpdate = ref(0);
const updateNumberHandle = (productId, productQuantity) => {
  dialogFormVisible.value = true;
  productIdUpdate.value = productId;
  quantityUpdate.value = productQuantity;
};

const updateQuantity = async (quantity, productId) => {
  try {
    const response = await productService.updateQuantity(
      { product_quantity: quantity },
      productId
    );
    showSuccess("Cập nhật số lượng thành công");
    fetchListProduct();
    setTimeout(() => {
      dialogFormVisible.value = false;
    }, 500);
  } catch (error) {
    console.log(error.response);
  }
};
const listProduct = ref([]);
const search = ref("");

const fetchListProduct = async () => {
  try {
    const response = await productService.getAll();
    listProduct.value = response.listProduct;
    productsLength.value = response.length;
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
  fetchListProduct();
});

const handleSearch = () => {
  console.log(search.value);
  console.log("cc :", datasearch.value);
};

const datasearch = computed(() => {
  const dataSearch = String(search.value).trim();
  const startIndex = (currentPage.value - 1) * pageSize;
  if (!dataSearch) {
    return listProduct.value.slice(startIndex, startIndex + pageSize);
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

const convertTime = (dateTimeString) => {
  var dateTime = moment(dateTimeString);
  dateTime.utcOffset(7);

  var formattedDateTime = dateTime.format("DD/MM/YYYY HH:mm:ss");
  return formattedDateTime;
};

// observing current page
const handleCurrentChange = (val) => {
  currentPage.value = val;
  console.log(`current page: ${val}`);
};
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

.border-none {
  border: none;
}
</style>
