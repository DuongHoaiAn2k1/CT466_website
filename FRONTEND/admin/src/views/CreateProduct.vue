<template>
  <div id="layoutSidenav_content">
    <div class="container-fluid px-4">
      <form class="row g-3 mt-2" @submit="submitAddproduct">
        <fieldset>
          <!-- Form Name -->
          <legend class="fs-4"><h1>THÊM SẢN PHẨM</h1></legend>

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
                placeholder="Tên sản phẩm"
                v-model="productData.product_name"
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
                id="product_des"
                cols="30"
                rows="10"
                v-model="productData.product_des"
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
                v-model="productData.product_price"
                placeholder="Giá bán"
              />
              <div class="ms-1 text-danger">
                {{ productPriceError }}
              </div>
            </div>
          </div>

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
              <button type="submit" class="btn btn-dark">THÊM</button>
            </div>
          </div>
        </fieldset>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import * as Yup from "yup";
import categoryService from "@/services/category.service";
import productService from "@/services/product.service";
import { ElLoading, ElNotification } from "element-plus";
import { useRouter } from "vue-router";
const router = useRouter();
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
  product_name: Yup.string().required("Tên sản phẩm không được để trống"),
  product_img: Yup.array()
    .min(3, "Phải tải lên ít nhất 3 hình ảnh")
    .max(3, "Chỉ được tải lên tối đa 3 hình ảnh")
    .typeError("Hình ảnh sản phẩm không được để trống"),
  product_des: Yup.string().required("Mô tả sản phẩm không được để trống"),
  category_id: Yup.string().required("Danh mục sản phẩm không được để trống"),
  product_price: Yup.string().required("Giá bán sản phẩm không được để trống"),

  product_quantity: Yup.string().required(
    "Số lượng sản phẩm không được để trống"
  ),
});

const productData = reactive({
  product_name: "",
  product_img: "",
  product_des: "",
  category_id: "",
  product_price: "",
  product_quantity: "",
});

const productNameError = ref("");
const productImgError = ref("");
const productDesError = ref("");
const categoryIdError = ref("");
const productPriceError = ref("");
const productQuantityError = ref("");

const handleImageUpload = (event) => {
  const imageFiles = event.target.files;
  if (imageFiles && imageFiles.length > 0) {
    productData.product_img = Array.from(imageFiles);
  } else {
    productData.product_img = [];
  }
};

const handleCreate = async (data) => {
  try {
    console.log("Sending request with data:", data);
    const response = productService.create(data);
    showSuccess("Tạo sản phẩm thành công");
    console.log("Response from server:", response);
  } catch (error) {
    console.log(error.response);
  }
};

const submitAddproduct = (event) => {
  event.preventDefault();
  productNameError.value = null;
  productImgError.value = null;
  productDesError.value = null;
  categoryIdError.value = null;
  productPriceError.value = null;
  productQuantityError.value = null;

  schema
    .validate(productData, { abortEarly: false })
    .then(() => {
      productNameError.value = null;
      productImgError.value = null;
      productDesError.value = null;
      categoryIdError.value = null;
      productPriceError.value = null;

      productQuantityError.value = null;

      console.log("product_data: ", productData.product_img);
      // Them vao formData
      const formData = new FormData();
      for (const key in productData) {
        if (key !== "product_img") {
          formData.append(key, productData[key]);
        }
      }

      for (const img of productData.product_img) {
        formData.append("product_img[]", img);
      }
      const loading = ElLoading.service({
        lock: true,
        text: "Đang xử lý...",
        background: "rgba(0,0,0, 0.7)",
      });
      setTimeout(() => {
        handleCreate(formData);
        loading.close();
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
onMounted(() => {
  fetchListCategory();
});
</script>
