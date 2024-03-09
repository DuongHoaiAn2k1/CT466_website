<template>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <h1 class="mt-4">DANH MỤC</h1>
        <ol class="breadcrumb mb-4">
          <button
            type="button"
            class="btn btn-dark"
            data-bs-toggle="modal"
            data-bs-target="#exampleModal"
          >
            Thêm (+)
          </button>
        </ol>
        <!-- Button trigger modal -->

        <!-- Modal -->
        <div
          class="modal fade"
          id="exampleModal"
          tabindex="-1"
          aria-labelledby="exampleModalLabel"
          aria-hidden="true"
        >
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1
                  class="modal-title fs-5"
                  id="exampleModalLabel"
                  style="font-weight: 600"
                >
                  Thêm danh mục
                </h1>

                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <form @submit="submitCreate">
                <div class="modal-body text-center">
                  <h5 style="font-weight: 600">Tên danh mục</h5>
                  <input
                    name="category_name"
                    type="text"
                    v-model="dataCreate.category_name"
                  />
                  <p>
                    <span class="text-danger">{{ categoryNameError }}</span>
                  </p>
                  <input
                    class="ms-5 ps-5"
                    type="file"
                    name="category_img"
                    @change="handleUpload"
                  />
                  <p>
                    <span class="text-danger">{{ categoryImgError }}</span>
                  </p>
                </div>

                <div class="modal-footer">
                  <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                  >
                    Đóng
                  </button>
                  <button type="submit" class="btn btn-dark">Thêm</button>
                </div>
              </form>
            </div>
          </div>
        </div>
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
                v-model="search"
              />
            </div>
            <span class="counter pull-right"></span>
            <table class="table table-hover table-bordered results">
              <thead>
                <tr>
                  <th>STT</th>
                  <th class="col">Tên danh mục</th>
                  <th class="col">Hình ảnh</th>
                  <th class="col">Thời gian tạo</th>
                  <th class="col">Thời gian cập nhật</th>
                  <th class="col"></th>
                  <th class="col"></th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(category, index) in datasearch"
                  :key="category.category_id"
                >
                  <th scope="row">{{ index + 1 }}</th>
                  <td>
                    <input
                      type="text"
                      v-model="category.category_name"
                      :readonly="index !== editingIndex"
                      :class="{ 'design-input': index !== editingIndex }"
                    />
                  </td>
                  <td>
                    <img
                      :src="
                        'http://127.0.0.1:8000/storage/' +
                        JSON.parse(category.category_img)[0]
                      "
                      alt=""
                      width="50px"
                    />
                  </td>
                  <td>{{ convertTime(category.created_at) }}</td>
                  <td>{{ convertTime(category.updated_at) }}</td>
                  <td>
                    <button
                      type="button"
                      class="btn btn-sm btn-secondary design-button"
                      @click="handleEdit(index)"
                    >
                      Edit
                    </button>
                  </td>
                  <td>
                    <!-- <button
                      v-show="index !== editingIndex"
                      type="button"
                      class="btn btn-sm btn-danger popconfirm-toggle"
                      data-message="This is example"
                      @click="handleDelete(category.category_id)"
                    >
                      Delete
                    </button> -->
                    <el-popconfirm
                      confirm-button-text="Yes"
                      cancel-button-text="No"
                      width="200"
                      confirm-button-type="danger"
                      title="Bạn có muốn xóa?"
                      @confirm="handleDelete(category.category_id)"
                      @cancel="cancelEvent"
                    >
                      <template #reference>
                        <el-button v-show="index !== editingIndex" type="danger"
                          >Delete</el-button
                        >
                      </template>
                    </el-popconfirm>
                    <button
                      v-show="index === editingIndex"
                      type="button"
                      class="btn btn-sm btn-success"
                      @click="handleUpdate(category)"
                    >
                      Update
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
                :total="Math.ceil(categoryLength / pageSize) * 10"
                class="mt-4"
              />
            </div>
            <div v-show="datasearch.length == 0">
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
import * as Yup from "yup";
import { ElLoading, ElNotification } from "element-plus";

const currentPage = ref(1);
const pageSize = 8;
const categoryLength = ref(0);
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

const handleUpload = (event) => {
  const imageFiles = event.target.files;
  if (imageFiles && imageFiles.length > 0) {
    dataCreate.category_img = Array.from(imageFiles);
  } else {
    dataCreate.category_img = [];
  }
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
  category_img: Yup.array()
    .max(1, "Chỉ được tải lên tối đa 1 hình ảnh")
    .typeError("Ảnh danh mục không được để trống"),
});
const listCategory = ref([]);
const search = ref("");
const editingIndex = ref(null);
const dataCreate = reactive({
  category_name: "",
  category_img: "",
});
const fetchListCategory = async () => {
  try {
    const response = await categoryService.getAll();
    listCategory.value = response.listCategory;
    categoryLength.value = response.length;
    console.log(response);
  } catch (error) {
    console.log(error);
  }
};

onMounted(() => {
  fetchListCategory();
});

const datasearch = computed(() => {
  const dataSearch = String(search.value).trim();
  const startIndex = (currentPage.value - 1) * pageSize;
  if (!dataSearch) {
    return listCategory.value.slice(startIndex, startIndex + pageSize);
  }

  return listCategory.value.filter((data) => {
    return String(data.category_name)
      .toLowerCase()
      .includes(dataSearch.toLowerCase());
  });
});

const handleEdit = (index) => {
  // console.log(category.category_id);
  if (editingIndex.value === index) {
    editingIndex.value = null;
  } else {
    editingIndex.value = index;
  }
};
//Fucntion update category
const updateCategory = async (category_id, data) => {
  try {
    const response = await categoryService.update(category_id, data);
    console.log(response);
    showSuccess("Cập nhật danh mục thành công");
  } catch (error) {
    console.log(error.response);
  }
};
// Hanlde update category
const handleUpdate = (category) => {
  console.log(category.category_name);
  const loading = ElLoading.service({
    lock: true,
    text: "Đang xử lý...",
    background: "rbga(0,0,0, 0.7)",
  });

  setTimeout(() => {
    updateCategory(category.category_id, category);
    loading.close();
    fetchListCategory();
  }, 2000);
};

// Function create category
const createCategory = async (data) => {
  try {
    const response = await categoryService.create(data);
    showSuccess("Thêm danh mục thành công");
    console.log(response);
  } catch (error) {
    console.log(error.response);
    if (error.response.data.message === "Danh mục đã tồn tại") {
      showWarning('"Danh mục đã tồn tại"');
    }
  }
};

// Function delete category
const deleteCategory = async (category_id) => {
  try {
    const response = await categoryService.delete(category_id);
    showSuccess("Xóa danh mục thành công");
    console.log(response);
  } catch (error) {
    console.log(error.response);
  }
};

//Hanlde delete category
const handleDelete = (category_id) => {
  const loading = ElLoading.service({
    lock: true,
    text: "Đang xử lý...",
    background: "rbga(0,0,0, 0.7)",
  });

  setTimeout(() => {
    deleteCategory(category_id);
    loading.close();
    fetchListCategory();
  }, 2000);
};
const categoryNameError = ref(null);
const categoryImgError = ref(null);

// Hanlde create new category
const submitCreate = async (event) => {
  event.preventDefault();
  categoryNameError.value = null;
  categoryImgError.value = null;
  schema
    .validate(dataCreate, { abortEarly: false })
    .then(() => {
      categoryNameError.value = null;
      categoryImgError.value = null;
      // console.log(dataCreate);
      console.log(dataCreate);

      const formData = new FormData();
      for (const key in dataCreate) {
        if (key !== "category_img") {
          formData.append(key, dataCreate[key]);
        }
      }

      for (const img of dataCreate.category_img) {
        formData.append("category_img[]", img);
      }
      const loading = ElLoading.service({
        lock: true,
        text: "Đang xử lý...",
        background: "rgba(0,0,0, 0.7)",
      });
      setTimeout(() => {
        createCategory(formData);
        loading.close();
        fetchListCategory();
      }, 2000);
    })
    .catch((errors) => {
      errors.inner.forEach((error) => {
        if (error.path == "category_name") {
          categoryNameError.value = error.message;
        }
        if (error.path == "category_img") {
          categoryImgError.value = error.message;
        }
      });
    });
  // console.log(dataCreate.category_name);
};

const convertTime = (dateTimeString) => {
  var dateTime = moment(dateTimeString);
  dateTime.utcOffset(7);

  var formattedDateTime = dateTime.format("DD/MM/YYYY HH:mm:ss");
  return formattedDateTime;
};

// Proccess pagination

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
</style>
