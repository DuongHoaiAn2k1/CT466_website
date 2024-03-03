<template>
  <div class="container mt-1">
    <div class="row">
      <div class="col-xl-8">
        <div class="">
          <div class="card-body pb-0">
            <div class="row align-items-center">
              <div class="col-md-3">
                <div class="text-center border-end">
                  <img
                    src="https://bootdey.com/img/Content/avatar/avatar1.png"
                    class="img-fluid avatar-xxl rounded-circle"
                    alt=""
                  />
                  <h4 class="text-dark font-size-20 mt-3 mb-2">
                    {{ userData.name }}
                  </h4>
                </div>
              </div>
              <!-- end col -->
              <div class="col-md-9">
                <div class="ms-3">
                  <div>
                    <h4 class="card-title mb-2">Thông tin khách hàng</h4>
                    <div class="table-responsive">
                      <table class="table table-bordered mb-0">
                        <tbody>
                          <tr>
                            <th scope="row">Họ và tên</th>
                            <td>
                              <input
                                style="width: 100%; border: none"
                                type="text"
                                v-model="userData.name"
                              />
                            </td>
                          </tr>
                          <!-- end tr -->
                          <tr>
                            <th scope="row">Email</th>
                            <td>
                              <input
                                style="width: 100%; border: none"
                                type="text"
                                v-model="userData.email"
                              />
                            </td>
                          </tr>
                          <tr>
                            <th scope="row">Điểm tích lũy</th>
                            <td>{{ userData.point }}</td>
                          </tr>
                          <!-- end tr -->
                        </tbody>
                        <!-- end tbody -->
                      </table>
                      <!-- end table -->
                    </div>
                  </div>
                </div>
              </div>
              <!-- end col -->
            </div>
            <!-- end row -->
          </div>
          <!-- end card body -->
        </div>
        <hr />
        <div class="contain-list-order">
          <div class="tab-content contain-list-order-child">
            <div class="tab-pane active show" id="tasks-tab" role="tabpanel">
              <h4 class="card-title mb-4">Đơn hàng của bạn</h4>
              <el-scrollbar height="400px">
                <div
                  class="row order-item"
                  v-for="data in listOrder"
                  :key="data.order_id"
                >
                  <div class="col-xl-12">
                    <div class="task-list-box" id="comp-task">
                      <div id="task-item-4">
                        <div class="task-box rounded-3">
                          <div class="card-body">
                            <div class="row align-item-center">
                              <div class="col-xl-6 col-sm-5">Mã đơn hàng:</div>
                            </div>
                            <div class="row align-items-center">
                              <div class="col-2">
                                <div class="font-size-15">
                                  <label
                                    class="form-check-label task-title"
                                    for="customChat"
                                    >#{{ data.bill_id }}</label
                                  >
                                </div>
                              </div>
                              <!-- end col -->
                              <div class="col-10">
                                <div class="row align-items-center">
                                  <div class="col-2">
                                    <div
                                      class="avatar-group mt-3 mt-xl-0 task-assigne"
                                    >
                                      <div class="avatar-group-item">
                                        <a
                                          href="javascript: void(0);"
                                          class="d-inline-block"
                                          data-bs-toggle="tooltip"
                                          data-bs-placement="top"
                                          value="member-3"
                                          aria-label="Annmarie Paul"
                                          data-bs-original-title="Annmarie Paul"
                                        >
                                          <img
                                            :src="
                                              'http://127.0.0.1:8000/storage/' +
                                              JSON.parse(
                                                data.order_detail[0].product
                                                  .product_img
                                              )[0]
                                            "
                                            alt=""
                                            class="rounded-circle avatar-sm"
                                          />
                                        </a>
                                      </div>
                                    </div>
                                    <!-- end avatar group -->
                                  </div>

                                  <div class="col-2">
                                    {{ formatCurrency(data.total_cost) }}
                                  </div>
                                  <div class="col-4">
                                    {{ covertTime(data.created_at) }}
                                  </div>
                                  <!-- end col -->
                                  <div class="col-2">
                                    <div
                                      class="d-flex flex-wrap gap-3 mt-3 mt-xl-0 justify-content-md-end"
                                    >
                                      <div>
                                        <span
                                          v-show="data.status == '1'"
                                          class="badge rounded-pill text-info font-size-11 task-status"
                                          >Đang chuẩn bị</span
                                        >
                                        <span
                                          v-show="data.status == '2'"
                                          class="badge rounded-pill orange font-size-11 task-status"
                                          >Đang giao</span
                                        >
                                        <span
                                          v-show="data.status == '3'"
                                          class="badge rounded-pill text-success font-size-11 task-status"
                                          >Đã giao</span
                                        >
                                        <span
                                          v-show="data.status == '0'"
                                          class="badge rounded-pill red font-size-11 task-status"
                                          >Đã hủy</span
                                        >
                                      </div>

                                      <div>
                                        <a
                                          href="#"
                                          class="mb-0 text-muted fw-medium"
                                          data-bs-toggle="modal"
                                          data-bs-target=".bs-example-new-task"
                                          ><i
                                            class="mdi mdi-square-edit-outline font-size-16 align-middle"
                                            onclick="editTask('task-item-4')"
                                          ></i
                                        ></a>
                                      </div>
                                      <div>
                                        <a
                                          href="#"
                                          class="delete-item"
                                          onclick="deleteProjects('task-item-4')"
                                        >
                                          <i
                                            class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger"
                                          ></i>
                                        </a>
                                      </div>
                                    </div>
                                  </div>

                                  <div class="col-2">
                                    <router-link
                                      :to="{
                                        name: 'order-detail',
                                        params: { id: data.order_id },
                                      }"
                                      >Xem chi tiết</router-link
                                    >
                                  </div>
                                  <!-- end col -->
                                </div>
                                <!-- end row -->
                              </div>
                              <!-- end col -->
                            </div>
                            <!-- end row -->
                          </div>
                          <!-- end cardbody -->
                        </div>
                        <!-- end card -->
                      </div>
                    </div>
                  </div>
                </div>
              </el-scrollbar>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-4">
        <div class="">
          <div class="">
            <div>
              <h4 class="mb-4">Địa chỉ của bạn</h4>
              <div
                class="d-flex flex-column flex-md-row align-items-center justify-content-center"
              >
                <div class="list-group">
                  <el-scrollbar height="230px">
                    <a
                      v-for="(data, index) in listAddressUser"
                      class="list-group-item d-flex gap-3 py-3"
                    >
                      <i class="fa-solid fa-location-dot"></i>
                      <div class="d-flex gap-2 w-100 justify-content-between">
                        <div>
                          <h6 class="mb-0">Địa chỉ {{ index + 1 }}</h6>
                          <p class="mb-0 opacity-75">
                            {{ data.address }} {{ data.commue }}
                            {{ data.district }} {{ data.city }} -
                            {{ data.name }} - {{ data.phone }}
                          </p>
                        </div>
                        <small class="opacity-50 text-nowrap mt-4"
                          ><button
                            class="border-none"
                            @click="handleDeteleAddress(index)"
                          >
                            <i class="fa-solid fa-trash"></i></button
                        ></small>
                      </div>
                      <hr />
                    </a>
                  </el-scrollbar>
                  <a
                    href="#"
                    class="list-group-item list-group-item-action d-flex gap-3 py-3"
                    aria-current="true"
                  >
                    <div
                      class="d-flex w-100 justify-content-between"
                      data-bs-toggle="modal"
                      data-bs-target="#exampleModal"
                    >
                      <div>
                        <p class="mb-0 opacity-75">
                          <i
                            class="fa-solid fa-plus border border-dark rounded-circle"
                          ></i>
                          Thêm địa chỉ mới.
                        </p>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <!-- end card body -->
        </div>
        <!-- end card -->

        <!-- Start Model -->
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
                <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">
                  Thêm địa chỉ mới
                </h1>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">
                <div class="table-responsive">
                  <table class="table table-bordered mb-0">
                    <tbody>
                      <tr>
                        <th scope="row">Họ và tên</th>
                        <td>
                          <input
                            type="text"
                            name="name"
                            v-model="addressInfoData.name"
                            placeholder="Vui lòng nhập họ tên"
                          />
                        </td>
                      </tr>
                      <span class="text-danger">{{ nameError }}</span>
                      <tr>
                        <th scope="row">Số điện thoại</th>
                        <td>
                          <input
                            type="text"
                            name="phone"
                            v-model="addressInfoData.phone"
                            placeholder="Vui lòng nhập số điện thoại"
                          />
                        </td>
                      </tr>
                      <span class="text-danger">{{ phoneError }}</span>
                      <tr>
                        <th scope="row">Tỉnh/Thành phố</th>
                        <td>
                          <select name="city" id="city" v-model="cityAddress">
                            <option value="0">--Chọn tỉnh/thành phố--</option>
                            <option
                              v-for="data in addressData.data"
                              :value="data.level1_id"
                              :key="data.level1_id"
                            >
                              {{ data.name }}
                            </option>
                          </select>
                        </td>
                      </tr>
                      <span class="text-danger">{{ cityError }}</span>
                      <!-- end tr -->
                      <tr>
                        <th scope="row">Quận/Huyện</th>
                        <td>
                          <select
                            v-show="cityAddress"
                            name="district"
                            id="district"
                            v-model="districtAddress"
                          >
                            <option value="0">--Quận/Huyện--</option>
                            <option
                              v-for="data in listDistrict"
                              :value="data.level2_id"
                              :key="data.level2_id"
                            >
                              {{ data.name }}
                            </option>
                          </select>
                        </td>
                      </tr>
                      <span class="text-danger">{{ districtError }}</span>
                      <tr>
                        <th scope="row">Phường/Xã</th>
                        <td>
                          <select
                            v-show="districtAddress"
                            name="commue"
                            id="commue"
                            v-model="commueAddress"
                          >
                            <option value="0">--Phường/Xã--</option>
                            <option
                              v-for="data in listCommune"
                              :value="data.level3_id"
                              :key="data.level3_id"
                            >
                              {{ data.name }}
                            </option>
                          </select>
                        </td>
                      </tr>
                      <span class="text-danger">{{ commueError }}</span>
                      <tr>
                        <th scope="row">Địa chỉ cụ thể</th>
                        <td>
                          <input
                            type="text"
                            name="address"
                            v-model="addressInfoData.address"
                          />
                        </td>
                      </tr>
                      <span class="text-danger">{{ addressError }}</span>
                      <!-- end tr -->
                    </tbody>
                    <!-- end tbody -->
                  </table>
                  <!-- end table -->
                </div>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  data-bs-dismiss="modal"
                >
                  Đóng
                </button>
                <button
                  @click="handleCreateAddress"
                  type="button"
                  class="btn btn-primary"
                >
                  Thêm
                </button>
              </div>
            </div>
          </div>
        </div>
        <!-- End Model -->
        <hr />
        <div class="mt-1">
          <div>
            <h4 class="card-title mb-4">Quản lý tài khoản</h4>
            <ul class="list-group hover">
              <li
                class="list-group-item list-group-item-action my-1"
                @click="dialogListFavorite = true"
              >
                <i class="fa-solid fa-heart"></i>
                Danh sách sản phẩm yêu thích
              </li>
              <li class="list-group-item list-group-item-action my-1">
                <i class="fa-solid fa-pen-to-square"></i> Cập nhật thông tin tài
                khoản
              </li>
              <li class="list-group-item list-group-item-action my-1">
                <i class="fa-solid fa-key"></i> Đổi mật khẩu
              </li>
              <li class="list-group-item list-group-item-action my-1">
                <i class="fa-solid fa-trash"></i> Xóa tài khoản
              </li>
            </ul>
            <!-- end ul -->
          </div>
        </div>
        <!-- end card -->
      </div>
      <!-- end col -->
    </div>
  </div>

  <!-- Content dialog -->
  <el-dialog
    v-model="dialogListFavorite"
    title="Danh sách yêu thích"
    width="800"
  >
    <section class="intro">
      <div class="gradient-custom-1 h-100">
        <div class="mask d-flex align-items-center h-100">
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-12">
                <div class="table-responsive bg-white">
                  <table class="table mb-0">
                    <thead>
                      <tr>
                        <th scope="col">STT</th>
                        <th scope="col">HÌNH ẢNH</th>
                        <th scope="col">TÊN SẢN PHẨM</th>
                        <th scope="col">GIÁ BÁN</th>
                        <th scope="col">Chi tiết</th>
                        <th scope="col">Trạng thái</th>
                      </tr>
                    </thead>

                    <tbody>
                      <tr
                        v-for="(data, index) in paginatedList"
                        :key="data.favorite_id"
                      >
                        <th scope="row" style="color: #666666">
                          {{ index + 1 }}
                        </th>
                        <td>
                          <img
                            :src="
                              'http://127.0.0.1:8000/storage/' +
                              JSON.parse(data.product.product_img)[0]
                            "
                            alt=""
                            width="40px"
                          />
                        </td>
                        <td>{{ data.product.product_name }}</td>
                        <td>
                          {{ formatCurrency(data.product.product_price) }}
                        </td>
                        <td>
                          <router-link
                            :to="{
                              name: 'product-detail',
                              params: { id: data.product.product_id },
                            }"
                            >Xem chi tiết</router-link
                          >
                        </td>
                        <td>
                          <div
                            class="design-heart"
                            @click="deleteFavorite(data.product.product_id)"
                          >
                            <i class="fa-solid fa-heart"></i>
                          </div>
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
                      :total="Math.ceil(favoriteLength / pageSize) * 10"
                      class="mt-4"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </el-dialog>
</template>

<script setup>
import * as Yup from "yup";
import userService from "@/services/user.service";
import { computed, onMounted, reactive, ref, watchEffect } from "vue";
import { useAuthStore } from "@/stores/auth";
import addressData from "@/assets/address/dvhc.json";
import { compose } from "element-plus/es/components/table/src/util";
import { ElLoading, ElMessage } from "element-plus";
import orderService from "@/services/order.service";
import productService from "@/services/product.service";
import favoriteService from "@/services/favorite.service";

// Dialog var
const dialogListFavorite = ref(false);

// End
const schema = Yup.object().shape({
  name: Yup.string().required("Họ và tên không được để trống"),
  phone: Yup.string()
    .min(10, "Số điện thoại phải 10 ký tự")
    .max(10, "Số điện thoại phải 10 ký tự")
    .required("Số điện thoại không được để trống"),
  city: Yup.string().required("Tỉnh thành phố không được để trống"),
  district: Yup.string().required("Quận huyện không được để trống"),
  commue: Yup.string().required("Xã phường không được để trống"),
  address: Yup.string().required("Địa chỉ cụ thể không được để trống"),
});

const authStore = useAuthStore();
const userData = ref({});
const listAddressUser = ref([]);
const currentPage = ref(1);
const pageSize = 4;
const favoriteLength = ref(0);

const addressInfoData = reactive({
  name: "",
  phone: "",
  city: "",
  district: "",
  commue: "",
  address: "",
});

const listOrder = ref([]);

const cityAddress = ref(0);
const districtAddress = ref(0);
const commueAddress = ref(0);
const listDistrict = ref([]);
const listCommune = ref([]);
const listProduct = ref([]);
const listFavorite = ref([]);

const fetchUserData = async () => {
  try {
    const userId = authStore.user_id;

    const response = await userService.get(userId);
    userData.value = response.data;
    console.log(response.data.address);
    listAddressUser.value = JSON.parse(response.data.address);
  } catch (error) {
    console.log(error.response);
  }
};

const fetchListOrder = async () => {
  try {
    const response = await orderService.getByUser();
    listOrder.value = response.data;
    console.log("List order: ", response);
  } catch (error) {
    console.log(error.response);
  }
};

const fetchListProduct = async () => {
  try {
    const response = await productService.getAll();
    listProduct.value = response.listProduct;
    console.log("List product: ", response);
  } catch (error) {
    console.log(error.response);
  }
};

const fetchListFavorite = async () => {
  try {
    const response = await favoriteService.getByUser();
    listFavorite.value = response.data;
    favoriteLength.value = response.length;
    console.log("List Favorite Product: ", response);
  } catch (error) {
    console.log(error.response);
  }
};

const getDataDistric = (id) => {
  return addressData.data.filter((data) => data.level1_id === id);
};

const getDataCommune = (id) => {
  return listDistrict.value.filter((data) => data.level2_id === id);
};

const getNameCommune = (id) => {
  return listCommune.value.filter((data) => data.level3_id === id);
};

const createAddress = async (data) => {
  try {
    const response = await userService.createAddress(data);
    console.log("After create address: ", response);
  } catch (error) {
    console.log(error.response);
  }
};

const deleteFavorite = async (productId) => {
  try {
    const response = await favoriteService.delete(productId);
    fetchListFavorite();
    showSuccess("Đã loại bỏ khỏi danh sách yêu thích");
    setTimeout(() => {
      fetchListFavorite();
    }, 500);
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

// Handle create address
const nameError = ref(null);
const phoneError = ref(null);
const cityError = ref(null);
const districtError = ref(null);
const commueError = ref(null);
const addressError = ref(null);
const handleCreateAddress = () => {
  nameError.value = null;
  phoneError.value = null;
  cityError.value = null;
  districtError.value = null;
  commueError.value = null;
  addressError.value = null;
  schema
    .validate(addressInfoData, { abortEarly: false })
    .then(() => {
      nameError.value = null;
      phoneError.value = null;
      cityError.value = null;
      districtError.value = null;
      commueError.value = null;
      addressError.value = null;

      const loading = ElLoading.service({
        lock: true,
        text: "Đang xử lý...",
        background: "rgba(0,0,0, 0.7)",
      });
      createAddress(addressInfoData).then(() => {
        showSuccess("Thêm địa chỉ thành công.");
        setTimeout(() => {
          loading.close();
          fetchUserData();
        }, 1000);
      });
    })
    .catch((errors) => {
      errors.inner.forEach((error) => {
        if (error.path == "name") {
          nameError.value = error.message;
        }
        if (error.path == "phone") {
          phoneError.value = error.message;
        }
        if (error.path == "city") {
          cityError.value = error.message;
        }
        if (error.path == "district") {
          districtError.value = error.message;
        }
        if (error.path == "commue") {
          commueError.value = error.message;
        }
        if (error.path == "address") {
          addressError.value = error.message;
        }
      });
    });
  console.log("The last info: ", addressInfoData);
};

// Hanlde delete address

const handleDeteleAddress = async (index) => {
  try {
    const response = await userService.deleteAddress(index);
    showSuccess("Xóa địa chỉ thành công.");
    fetchUserData();
  } catch (error) {
    console.log(error.response);
  }
};

const handleFetchCartCount = async () => {
  try {
    await cartStore.fetchCartCount();
  } catch (error) {
    console.log(error.response);
  }
};

onMounted(() => {
  handleFetchCartCount();
  fetchUserData();
  fetchListOrder();
  fetchListProduct();
  fetchListFavorite();

  // console.log("GET: ", getDataDistric("01")[0].name);
});

const formatCurrency = (amount) => {
  return (
    amount?.toLocaleString("vi-VN", {
      style: "currency",
      currency: "VND",
    }) || ""
  );
};

const covertTime = (dateTimeString) => {
  var dateTime = moment(dateTimeString);
  dateTime.utcOffset(7);

  var formattedDateTime = dateTime.format("DD/MM/YYYY HH:mm:ss");
  return formattedDateTime;
};

watchEffect(() => {
  if (cityAddress.value != 0) {
    const district = getDataDistric(cityAddress.value);
    listDistrict.value = district[0].level2s;
    console.log("GET: ", listDistrict);
  }

  if (districtAddress.value != 0) {
    listCommune.value = getDataCommune(districtAddress.value)[0].level3s;
    console.log("Distric address: ", listCommune);
  }

  if (commueAddress.value != 0) {
    // console.log("cityAddress: ", cityAddress.value);
    // console.log("districtAddress: ", districtAddress.value);
    // console.log("commueAddress: ", commueAddress.value);
    addressInfoData.city = getDataDistric(cityAddress.value)[0].name;
    addressInfoData.district = getDataCommune(districtAddress.value)[0].name;
    addressInfoData.commue = getNameCommune(commueAddress.value)[0].name;
  }
});

const handleCurrentChange = (val) => {
  currentPage.value = val;
  console.log(`current page: ${val}`);
};

const paginatedList = computed(() => {
  const startIndex = (currentPage.value - 1) * pageSize;
  return listFavorite.value.slice(startIndex, startIndex + pageSize);
});
</script>

<style>
.yellow {
  color: yellow;
}

.primary-orange {
  color: #ffe599;
}

.orange {
  color: #ffa500;
}

.red {
  color: #c81f30;
}

.border-none {
  border: none;
}
.border-none:hover {
  color: #038edc;
}

.hover li {
  background-color: #ccc;
}
.hover li:hover {
  background-color: #038edc;
}

.avatar-xxl {
  height: 7rem;
  width: 7rem;
}

.card {
  margin-bottom: 20px;
  -webkit-box-shadow: 0 2px 3px #eaedf2;
  box-shadow: 0 2px 3px #eaedf2;
}

.pb-0 {
  padding-bottom: 0 !important;
}

.font-size-16 {
  font-size: 16px !important;
}
.avatar-title {
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  background-color: #038edc;
  color: #fff;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  font-weight: 500;
  height: 100%;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  width: 100%;
}

.bg-soft-primary {
  background-color: rgba(3, 142, 220, 0.15) !important;
}
.rounded-circle {
  border-radius: 50% !important;
}

.nav-tabs-custom .nav-item .nav-link.active {
  color: #038edc;
}
.nav-tabs-custom .nav-item .nav-link {
  border: none;
}
.nav-tabs-custom .nav-item .nav-link.active {
  color: #038edc;
}

.avatar-group {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  padding-left: 12px;
}

.border-end {
  border-right: 1px solid #eff0f2 !important;
}

.d-inline-block {
  display: inline-block !important;
}

.avatar-group .avatar-group-item {
  margin-left: -14px;
  border: 2px solid #fff;
  border-radius: 50%;
  -webkit-transition: all 0.2s;
  transition: all 0.2s;
}

.avatar-sm {
  height: 2rem;
  width: 2rem;
}

.nav-tabs-custom .nav-item {
  position: relative;
  color: #343a40;
}

.nav-tabs-custom .nav-item .nav-link.active:after {
  -webkit-transform: scale(1);
  transform: scale(1);
}

.nav-tabs-custom .nav-item .nav-link::after {
  content: "";
  background: #038edc;
  height: 2px;
  position: absolute;
  width: 100%;
  left: 0;
  bottom: -2px;
  -webkit-transition: all 250ms ease 0s;
  transition: all 250ms ease 0s;
  -webkit-transform: scale(0);
  transform: scale(0);
}

/* My design */
.contain-list-order {
  border-style: groove;
}

.contain-list-order-child {
  padding: 8px;
}

.order-item {
  border-style: ridge;
}

/* Customize Favorite Hanle */
.design-heart {
  margin-left: 24px;
  color: red;
}
</style>
