<template>
  <div id="layoutSidenav_content">
    <div class="container-fluid px-4">
      <form
        @submit="handleSubmit"
        class="row g-3 mt-2"
        enctype="multipart/form-data"
      >
        <fieldset>
          <!-- Form Name -->
          <legend class="fs-4"><h1>CẬP NHẬT SẢN PHẨM</h1></legend>

          <!-- Text input-->
          <div class="mb-3 row">
            <label for="product_name" class="col-sm-4 col-form-label"
              >TÊN SẢN PHẨM</label
            >
            <div class="col-sm-8">
              <input
                type="text"
                class="form-control"
                id="product_name"
                name="product_name"
                v-model="productData.product_name"
                placeholder="Tên sản phẩm"
              />
              <div class="ms-1 text-danger">
                {{ productNameError }}
              </div>
            </div>
          </div>

          <!-- Text input-->
          <div class="mb-3 row">
            <label for="product_img" class="col-sm-4 col-form-label"
              >HÌNH ẢNH SẢN PHẨM</label
            >
            <div class="col-sm-8">
              <input
                type="file"
                class="form-control"
                id="product_img"
                multiple
                @change="handleImageUpload"
                name="product_img"
                placeholder=""
              />
              <div class="ms-1 text-danger">
                {{ productImgError }}
              </div>
            </div>
          </div>

          <!-- Select Basic -->
          <div class="mb-3 row">
            <label for="product_des" class="col-sm-4 col-form-label"
              >MÔ TẢ SẢN PHẨM</label
            >
            <div class="col-sm-8">
              <textarea
                name="product_des"
                class="form-control"
                v-model="productData.product_des"
                id="product_des"
                cols="30"
                rows="10"
              ></textarea>
              <div class="ms-1 text-danger">
                {{ productDesError }}
              </div>
            </div>
          </div>

          <!-- Text input-->
          <div class="mb-3 row">
            <label for="category_id" class="col-sm-4 col-form-label"
              >DANH MỤC SẢN PHẨM</label
            >
            <div class="col-sm-8">
              <select
                class="form-control"
                id="category_id"
                name="category_id"
                v-model="productData.category_id"
                placeholder="AVAILABLE QUANTITY"
              >
                <option disabled value="">--Chọn--</option>
                <option
                  v-for="category in listCategory"
                  :key="category.category_id"
                  :value="category.category_id"
                  :disabled="category_id == productData.category_id"
                >
                  {{ category.category_name }}
                </option>
              </select>
              <div class="ms-1 text-danger">
                {{ categoryIdError }}
              </div>
            </div>
          </div>

          <!-- Text input-->
          <div class="mb-3 row">
            <label for="product_price" class="col-sm-4 col-form-label"
              >GIÁ BÁN</label
            >
            <div class="col-sm-8">
              <input
                type="text"
                class="form-control"
                id="product_price"
                name="product_price"
                placeholder="Giá bán"
                v-model="productData.product_price"
              />
              <div class="ms-1 text-danger">
                {{ productPriceError }}
              </div>
            </div>
          </div>

          <!-- Textarea -->

          <div class="mb-3 row">
            <label for="product_quantity" class="col-sm-4 col-form-label"
              >SỐ LƯỢNG CÓ SẴN</label
            >
            <div class="col-sm-8">
              <input
                type="text"
                class="form-control"
                id="product_quantity"
                name="product_quantity"
                placeholder="Số lượng có sẵn"
                v-model="productData.product_quantity"
              />
              <div class="ms-1 text-danger">
                {{ productQuantityError }}
              </div>
            </div>
          </div>

          <!-- Button -->
          <div class="mb-3 row">
            <div class="col-sm-4"></div>
            <div class="col-sm-8">
              <button type="submit" class="btn btn-dark">CẬP NHẬT</button>
            </div>
          </div>
        </fieldset>
      </form>
    </div>
  </div>
</template>

<script setup>
import productService from "@/services/product.service";
import categoryService from "@/services/category.service";
import { computed, onMounted, reactive, ref } from "vue";
import * as Yup from "yup";
import { ElLoading, ElNotification } from "element-plus";
import { useRoute } from "vue-router";
import { useRouter } from "vue-router";
const route = useRoute();
const router = useRouter();
const product_id = computed(() => route.params.id);

const productData = ref({
  product_name: "",
  product_des: "",
  category_id: "",
  product_price: "",
  product_quantity: "",
});

const showSuccess = (message) => {
  ElNotification({
    title: "Success",
    message: message,
    type: "success",
  });
};

const schema = Yup.object().shape({
  product_name: Yup.string().required("Tên sản phẩm không được để trống"),
  product_img: Yup.array()
    .min(3, "Phải tải lên ít nhất 3 hình ảnh")
    .max(3, "Chỉ được tải lên tối đa 3 hình ảnh"),
  product_des: Yup.string().required("Mô tả sản phẩm không được để trống"),
  category_id: Yup.string().required("Danh mục sản phẩm không được để trống"),
  product_price: Yup.string().required("Giá bán sản phẩm không được để trống"),
  product_quantity: Yup.string().required(
    "Số lượng sản phẩm không được để trống"
  ),
});

const handleImageUpload = (event) => {
  const imageFiles = event.target.files;
  if (imageFiles && imageFiles.length > 0) {
    productData.value.product_img = Array.from(imageFiles);
  }
};

const listCategory = ref([]);
const fetchListCategory = async () => {
  try {
    const response = await categoryService.getAll();
    listCategory.value = response.listCategory;
    console.log(listCategory.value.listCategory);
  } catch (error) {
    console.log(error);
  }
};

const fetchProduct = async () => {
  try {
    const response = await productService.get(product_id.value);
    const dataServer = response.data;
    delete dataServer.created_at;
    delete dataServer.updated_at;
    delete dataServer.product_img;
    productData.value = dataServer;
    console.log("data in server: ", productData.value);
    // Lưu ý: Không cần cập nhật product_img ở đây, vì bạn sẽ xử lý riêng trong handleImageUpload.
  } catch (error) {
    console.log(error.response);
  }
};

const handleUpdate = async (id, data) => {
  try {
    const response = await productService.update(id, data);
    console.log("Response after update: ", response);
  } catch (error) {
    console.log(error.response);
  }
};

const productNameError = ref("");
const productImgError = ref("");
const productDesError = ref("");
const categoryIdError = ref("");
const productPriceError = ref("");
const productQuantityError = ref("");
const handleSubmit = (event) => {
  event.preventDefault();

  productNameError.value = null;
  productImgError.value = null;
  productDesError.value = null;
  categoryIdError.value = null;
  productPriceError.value = null;
  productQuantityError.value = null;

  schema
    .validate(productData.value, { abortEarly: false })
    .then(() => {
      productNameError.value = null;
      productImgError.value = null;
      productDesError.value = null;
      categoryIdError.value = null;
      productPriceError.value = null;
      productQuantityError.value = null;
      const productDataUpdate = new FormData();
      for (const key in productData.value) {
        if (key !== "product_img") {
          productDataUpdate.append(key, productData.value[key]);
        }
      }
      if (productData.value.product_img) {
        for (const img of productData.value.product_img) {
          productDataUpdate.append("product_img[]", img);
        }
      }
      const loading = ElLoading.service({
        lock: true,
        text: "Đang xử lý...",
        background: "rgba(0,0,0, 0.7)",
      });
      setTimeout(() => {
        handleUpdate(product_id.value, productDataUpdate);
        loading.close();
        showSuccess("Cập nhật sản phẩm thành công");
        setTimeout(() => {
          router.push({ name: "product" });
        }, 1000);
      }, 2000);
      console.log(formData);
    })
    .catch((errors) => {
      if (errors && errors.inner) {
        errors.inner.forEach((error) => {
          if (error.path === "product_name") {
            productNameError.value = error.message;
          }
          if (error.path === "product_img") {
            productImgError.value = error.message;
          }
          if (error.path === "product_des") {
            productDesError.value = error.message;
          }
          if (error.path === "category_id") {
            categoryIdError.value = error.message;
          }
          if (error.path === "product_price") {
            productPriceError.value = error.message;
          }

          if (error.path === "product_quantity") {
            productQuantityError.value = error.message;
          }
        });
      }
    });
};

onMounted(() => {
  fetchProduct();
  fetchListCategory();
  console.log("productData: ", productData);
});
</script>
