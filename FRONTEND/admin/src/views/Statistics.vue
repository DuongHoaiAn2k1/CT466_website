<template>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <h1 class="mt-4">THỐNG KÊ</h1>

        <div class="row">
          <div class="col-xl-3 col-md-6" @click="showDetail('customer')">
            <div class="card bg-dark text-white mb-4">
              <div class="card-body">Khách hàng</div>
              <div
                class="card-footer d-flex align-items-center justify-content-between"
              >
                <a class="small text-white stretched-link" href="#"
                  >Xem chi tiết</a
                >
                <div class="small text-white">
                  <i class="fas fa-angle-right"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6" @click="showDetail('product')">
            <div class="card bg-dark text-white mb-4">
              <div class="card-body">Sản phẩm</div>
              <div
                class="card-footer d-flex align-items-center justify-content-between"
              >
                <a class="small text-white stretched-link" href="#"
                  >Xem chi tiết</a
                >
                <div class="small text-white">
                  <i class="fas fa-angle-right"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6" @click="showDetail('order')">
            <div class="card bg-dark text-white mb-4">
              <div class="card-body">Đơn hàng</div>
              <div
                class="card-footer d-flex align-items-center justify-content-between"
              >
                <a class="small text-white stretched-link" href="#"
                  >Xem chi tiết</a
                >
                <div class="small text-white">
                  <i class="fas fa-angle-right"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6" @click="showDetail('income')">
            <div class="card bg-dark text-white mb-4">
              <div class="card-body">Doanh Thu</div>
              <div
                class="card-footer d-flex align-items-center justify-content-between"
              >
                <a class="small text-white stretched-link" href="#"
                  >Xem chi tiết</a
                >
                <div class="small text-white">
                  <i class="fas fa-angle-right"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Customer View -->
        <div class="card mb-4" v-show="showCustomer">
          <el-row class="mx-2">
            <el-col :span="6">
              <el-row
                ><span class="design-font">Tổng số khách hàng</span></el-row
              >
              <el-row
                ><span class="design-content">{{
                  sortedCustomers.length
                }}</span></el-row
              >
            </el-col>
          </el-row>

          <el-row class="mx-2 mt-3">
            <div class="form-group pull-right contain-search">
              <el-select
                v-model="value"
                clearable
                placeholder="Chọn điều kiện"
                style="width: 140px"
              >
                <el-option
                  v-for="item in options"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>
              <el-select
                v-show="value == 1"
                v-model="valueCondition"
                clearable
                placeholder="Select"
                style="width: 140px"
              >
                <el-option
                  v-for="item in timeJoinning"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>
              <el-select
                v-show="value == 2"
                v-model="valueCondition"
                clearable
                placeholder="Select"
                style="width: 140px"
              >
                <el-option
                  v-for="item in buyingCondition"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>

              <el-button @click="fetchUsersByCondition">Lọc</el-button>
              <span class="ms-2" style="font-size: 14px"
                >Kết quả: {{ sortedCustomers.length }}</span
              >
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
                  <th>STT</th>
                  <th class="col">Tên khách hàng</th>
                  <th class="col">Email</th>
                  <th class="col">Điểm tích lũy</th>
                  <th class="col">Điểm đã dùng</th>
                  <th class="col">Tổng đơn hàng</th>
                  <th class="col">
                    Tổng tiền đã mua
                    <button class="border-none" @click="toggleSortOrder">
                      <i class="fa-solid fa-sort"></i>
                    </button>
                  </th>
                  <th>Thời gian tham gia</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(user, index) in sortedCustomers" :key="user.id">
                  <th scope="row">{{ index + 1 }}</th>
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.point }}</td>
                  <td>{{ user.point_used }}</td>
                  <td>
                    {{ user.order_count }}
                  </td>
                  <td>
                    {{
                      formatCurrency(
                        user.order_sum_total_cost
                          ? parseInt(user.order_sum_total_cost)
                          : 0
                      )
                    }}
                  </td>
                  <td>{{ convertTime(user.created_at) }}</td>
                </tr>
                <tr
                  v-show="sortedCustomers.length == 0"
                  class="text-center text-danger"
                  height="50"
                >
                  <td colspan="7">
                    <div class="text-center">Không tìm thấy !!!</div>
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="text-end mb-2">
              <el-pagination
                v-model:current-page="currentPage"
                @current-change="handleCurrentChange"
                small
                background
                layout="prev, pager, next"
                :total="Math.ceil(listCustomerLength / pageSize) * 10"
                class="mt-4"
              />
            </div>
          </el-row>
        </div>
        <!-- Product View -->
        <div class="card mb-4" v-show="showProduct">
          <el-row class="mx-2">
            <el-col :span="6">
              <el-row><span class="design-font">Tổng số sản phẩm</span></el-row>
              <el-row
                ><span class="design-content">{{ countProduct }}</span></el-row
              >
            </el-col>
          </el-row>

          <el-row class="mx-2 mt-3">
            <div class="form-group pull-right contain-search">
              <el-select
                v-model="value"
                clearable
                placeholder="Chọn điều kiện"
                style="width: 140px"
              >
                <el-option
                  v-for="item in optionsProduct"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>
              <el-select
                v-show="value == 1"
                v-model="valueCondition"
                clearable
                placeholder="Select"
                style="width: 140px"
              >
                <el-option
                  v-for="item in timeBuying"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>

              <el-button @click="fetchProductByCondition">Lọc</el-button>
              <span class="ms-2" style="font-size: 14px"
                >Kết quả: {{ listProductLength }}</span
              >
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
                  <th class="col">Tên sản phẩm</th>
                  <th class="col">Số lượng bán ra</th>
                  <th class="col">Số lượt xem(All)</th>
                  <th class="col">
                    Tổng số tiền thu được
                    <button class="border-none" @click="toggleSortProduct">
                      <i class="fa-solid fa-sort"></i>
                    </button>
                  </th>
                  <!-- <th class="col">
                    Tổng tiền đã mua
                    <button class="border-none" @click="toggleSortOrder">
                      <i class="fa-solid fa-sort"></i>
                    </button>
                  </th> -->
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(product, index) in sortedProducts"
                  :key="product.product_id"
                >
                  <th scope="row">{{ index + 1 }}</th>
                  <td>{{ product.product_name }}</td>
                  <td>{{ product.total_quantity }}</td>
                  <td>
                    {{ product.product_views }}
                  </td>
                  <td>
                    {{ formatCurrency(parseInt(product.total_cost_detail)) }}
                  </td>
                </tr>
                <tr
                  v-show="sortedProducts.length == 0"
                  class="text-center text-danger"
                  height="50"
                >
                  <td colspan="7">
                    <div class="text-center">Không tìm thấy !!!</div>
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="text-end mb-2">
              <el-pagination
                v-model:current-page="currentPage"
                @current-change="handleCurrentChange"
                small
                background
                layout="prev, pager, next"
                :total="Math.ceil(listProductLength / pageSize) * 10"
                class="mt-4"
              />
            </div>
          </el-row>
        </div>

        <!-- Orders View -->
        <div class="card mb-4" v-show="showOrder">
          <el-row class="mx-2">
            <el-col :span="4">
              <el-row><span class="design-font">Tổng đơn hàng</span></el-row>
              <el-row
                ><span class="design-content">{{
                  sortedOrders.length
                }}</span></el-row
              >
            </el-col>
            <el-col :span="4">
              <el-row
                ><span class="design-font">Đơn hàng đang xử lý</span></el-row
              >
              <el-row
                ><span class="design-content">{{ prepareNumber }}</span></el-row
              >
            </el-col>
            <el-col :span="4">
              <el-row
                ><span class="design-font">Đơn hàng đang giao</span></el-row
              >
              <el-row
                ><span class="design-content">{{
                  shippingNumber
                }}</span></el-row
              >
            </el-col>
            <el-col :span="4">
              <el-row><span class="design-font">Đơn hàng đã giao</span></el-row>
              <el-row
                ><span class="design-content">{{ receiveNumber }}</span></el-row
              >
            </el-col>
            <el-col :span="4">
              <el-row><span class="design-font">Đơn hàng đã hủy</span></el-row>
              <el-row
                ><span class="design-content">{{ cancelNumber }}</span></el-row
              >
            </el-col>
          </el-row>

          <el-row class="mx-2 mt-3">
            <div class="form-group pull-right contain-search">
              <el-select
                v-model="value"
                clearable
                placeholder="Chọn điều kiện"
                style="width: 140px"
              >
                <el-option
                  v-for="item in optionsOrder"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>
              <el-select
                v-show="value == 1"
                v-model="valueCondition"
                clearable
                placeholder="Select"
                style="width: 140px"
              >
                <el-option
                  v-for="item in timeOrdering"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>

              <el-button @click="fetchOrderByCondition">Lọc</el-button>
              <span class="ms-2" style="font-size: 14px"
                >Kết quả: {{ sortedOrders.length }}</span
              >
            </div>
            <span class="counter pull-right"></span>
            <table class="table table-hover table-bordered results">
              <thead>
                <tr>
                  <th>STT</th>
                  <th class="col">Mã đơn hàng</th>
                  <th class="col">Trạng thái</th>
                  <th class="col">Phí giao hàng</th>
                  <th class="col">
                    Tổng tiền
                    <button class="border-none" @click="toggleSortOrder">
                      <i class="fa-solid fa-sort"></i>
                    </button>
                  </th>
                  <th class="col">Ngày đặt hàng</th>
                  <th class="col">Xem chi tiết</th>
                  <!-- <th class="col">
                    Tổng tiền đã mua
                    <button class="border-none" @click="toggleSortOrder">
                      <i class="fa-solid fa-sort"></i>
                    </button>
                  </th> -->
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(order, index) in sortedOrders"
                  :key="order.order_id"
                >
                  <th scope="row">{{ index + 1 }}</th>
                  <td>{{ order.bill_id }}</td>
                  <td>
                    <span
                      v-show="order.status == '1'"
                      class="badge rounded-pill text-info font-size-11 task-status"
                      >Đang chuẩn bị</span
                    >
                    <span
                      v-show="order.status == '2'"
                      class="badge rounded-pill orange font-size-11 task-status"
                      >Đang giao</span
                    >
                    <span
                      v-show="order.status == '3'"
                      class="badge rounded-pill text-success font-size-11 task-status"
                      >Đã giao</span
                    >
                    <span
                      v-show="order.status == '0'"
                      class="badge rounded-pill red font-size-11 task-status"
                      >Đã hủy</span
                    >
                  </td>
                  <td>{{ formatCurrency(order.shipping_fee) }}</td>
                  <td>
                    {{ formatCurrency(order.total_cost) }}
                  </td>
                  <td>
                    {{ convertTime(order.created_at) }}
                  </td>
                  <td>
                    <router-link
                      :to="{
                        name: 'order-detail',
                        params: { id: order.order_id },
                      }"
                      >Xem chi tiết</router-link
                    >
                  </td>
                </tr>
                <tr
                  v-show="sortedOrders.length == 0"
                  class="text-center text-danger"
                  height="50"
                >
                  <td colspan="7">
                    <div class="text-center">Không tìm thấy !!!</div>
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="text-end mb-2">
              <el-pagination
                v-model:current-page="currentPage"
                @current-change="handleCurrentChange"
                small
                background
                layout="prev, pager, next"
                :total="Math.ceil(listOrder.length / pageSize) * 10"
                class="mt-4"
              />
            </div>
          </el-row>
        </div>

        <!-- Income View -->
        <div class="card mb-4" v-show="showIncome">
          <div class="row mx-1 mb-2">
            <span style="font-size: 24px">Tháng này</span>
          </div>
          <el-row class="ms-10">
            <el-col :span="8">
              <el-row
                ><span class="design-font ms-5">Tổng tiền thu</span></el-row
              >
              <el-row
                ><span class="design-content">{{
                  formatCurrency(parseInt(calculateTotalCostThisMonth))
                }}</span></el-row
              >
            </el-col>
            <el-col :span="8">
              <el-row
                ><span class="design-font ms-5"
                  >Tổng tiền bán hàng</span
                ></el-row
              >
              <el-row
                ><span class="design-content">{{
                  formatCurrency(
                    parseInt(
                      calculateTotalCostThisMonth -
                        calculateShippingFeeThisMonth
                    )
                  )
                }}</span></el-row
              >
            </el-col>
            <el-col :span="8">
              <el-row
                ><span class="design-font ms-5">Tiền hỗ trợ ship</span></el-row
              >
              <el-row
                ><span class="design-content">{{
                  formatCurrency(parseInt(calculateShippingFeeThisMonth))
                }}</span></el-row
              >
            </el-col>
          </el-row>

          <el-row class="mx-2 mt-3">
            <div class="form-group pull-right contain-search">
              <el-select
                v-model="value"
                clearable
                placeholder="Chọn điều kiện"
                style="width: 140px"
              >
                <el-option
                  v-for="item in optionsOrder"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>
              <el-select
                v-show="value == 1"
                v-model="valueCondition"
                clearable
                placeholder="Select"
                style="width: 140px"
              >
                <el-option
                  v-for="item in timeCalculate"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>

              <el-button @click="getCalculateCost">Lọc</el-button>
            </div>
            <span class="counter pull-right"></span>
          </el-row>
          <el-row class="ms-10 my-5">
            <el-col :span="8">
              <el-row
                ><span class="design-font ms-5">Tổng tiền thu</span></el-row
              >
              <el-row
                ><span class="design-content">{{
                  formatCurrency(parseInt(calculateTotalCost))
                }}</span></el-row
              >
            </el-col>
            <el-col :span="8">
              <el-row
                ><span class="design-font ms-5"
                  >Tổng tiền bán hàng</span
                ></el-row
              >
              <el-row
                ><span class="design-content">{{
                  formatCurrency(
                    parseInt(calculateTotalCost - calculateShippingFee)
                  )
                }}</span></el-row
              >
            </el-col>
            <el-col :span="8">
              <el-row
                ><span class="design-font ms-5">Tiền hỗ trợ ship</span></el-row
              >
              <el-row
                ><span class="design-content">{{
                  formatCurrency(parseInt(calculateShippingFee))
                }}</span></el-row
              >
            </el-col>
          </el-row>
        </div>
      </div>
    </main>
  </div>
</template>
<script setup>
import { ref, onMounted, computed, watch } from "vue";
import productService from "@/services/product.service";
import orderService from "@/services/order.service";
import orderDetailService from "@/services/order_detail.service.js";

import userService from "@/services/user.service";
import {
  ArrowRight,
  CaretBottom,
  CaretTop,
  Warning,
} from "@element-plus/icons-vue";
const sortOrder = ref("ascending");
const showCustomer = ref(true);
const showProduct = ref(false);
const showOrder = ref(false);
const showIncome = ref(false);
const listCustomer = ref([]);
const listOrderUser = ref([]);
const search = ref("");

// Define panigation var
const currentPage = ref(1);
const pageSize = 8;
const listCustomerLength = ref(0);

// End

const showDetail = (type) => {
  switch (type) {
    case "customer":
      showCustomer.value = true;
      showProduct.value = false;
      showOrder.value = false;
      showIncome.value = false;
      search.value = "";
      currentPage.value = 1;
      valueCondition.value = "1";
      break;
    case "product":
      showCustomer.value = false;
      showProduct.value = true;
      showOrder.value = false;
      showIncome.value = false;
      search.value = "";
      currentPage.value = 1;
      valueCondition.value = "1";
      break;
    case "order":
      showCustomer.value = false;
      showProduct.value = false;
      showOrder.value = true;
      showIncome.value = false;
      search.value = "";
      currentPage.value = 1;
      valueCondition.value = "1";
      break;
    case "income":
      showCustomer.value = false;
      showProduct.value = false;
      showOrder.value = false;
      showIncome.value = true;
      search.value = "";
      currentPage.value = 1;
      valueCondition.value = "1";
      break;
    default:
      break;
  }
};

// Customer HANDLE
const customerNumber = ref(0);
const countCustomer = async () => {
  try {
    const response = await userService.getAll();
    customerNumber.value = response.length;
  } catch (error) {
    console.log(error.response);
  }
};

// const fetchListCustomer = async () => {
//   try {
//     const response = await userService.getAll();
//     listCustomer.value = response.data;
//     listCustomerLength.value = response.length;
//     console.log(response);
//   } catch (error) {
//     console.log(error.response);
//   }
// };

const fetchListOrderUser = async () => {
  try {
    const response = await orderService.getListOrderUser();
    listOrderUser.value = response.data;
    console.log(listOrderUser);
  } catch (error) {
    console.log(error.response);
  }
};

const value = ref("1");
const valueCondition = ref("1");
watch(value, (newValue, oldValue) => {
  // Nếu giá trị mới của value khác giá trị cũ
  if (newValue !== oldValue) {
    // Đặt lại giá trị của valueCondition về 1
    valueCondition.value = "1";
  }
});
const options = [
  {
    value: "1",
    label: "Thời gian tham gia",
  },
  {
    value: "2",
    label: "Mua hàng",
  },
];

const optionsProduct = [
  {
    value: "1",
    label: "Thời gian",
  },
];

const optionsOrder = [
  {
    value: "1",
    label: "Thời gian",
  },
];

const timeCalculate = [
  {
    value: "1",
    label: "Tất cả",
  },
  {
    value: "2",
    label: "Tháng này",
  },
  {
    value: "3",
    label: "Tháng trước",
  },
  {
    value: "4",
    label: "Năm 2024",
  },
  {
    value: "5",
    label: "Năm 2023",
  },
];
const timeOrdering = [
  {
    value: "1",
    label: "Tất cả",
  },
  {
    value: "2",
    label: "Tháng này",
  },
  {
    value: "3",
    label: "Tháng trước",
  },
  {
    value: "4",
    label: "Năm 2024",
  },
  {
    value: "5",
    label: "Năm 2023",
  },
];
const timeBuying = [
  {
    value: "1",
    label: "Tất cả",
  },
  {
    value: "2",
    label: "Tháng này",
  },
  {
    value: "3",
    label: "Tháng trước",
  },
  {
    value: "4",
    label: "Năm 2024",
  },
  {
    value: "5",
    label: "Năm 2023",
  },
];
const timeJoinning = [
  {
    value: "1",
    label: "Tất cả",
  },
  {
    value: "2",
    label: "Tháng này",
  },
  {
    value: "3",
    label: "Tháng trước",
  },
  {
    value: "4",
    label: "Năm 2024",
  },
  {
    value: "5",
    label: "Năm 2023",
  },
];

const buyingCondition = [
  {
    value: "1",
    label: "Tất cả",
  },
  {
    value: "2",
    label: "Tháng này",
  },
  {
    value: "3",
    label: "Tháng trước",
  },
  {
    value: "4",
    label: "Năm 2024",
  },
  {
    value: "5",
    label: "Năm 2023",
  },
];
const fetchUsersByCondition = async () => {
  try {
    const response = await userService.filterUser({
      criteria: value.value,
      condition: valueCondition.value,
    });
    listCustomer.value = response.data;
    listCustomerLength.value = response.length;
    // console.log("FILTER: ", response);
  } catch (error) {
    console.log(error.response);
  }
};
//END Customer HANLDE

//START ORDER HANDLE
const listOrder = ref([]);
const cancelNumber = ref(0);
const prepareNumber = ref(0);
const shippingNumber = ref(0);
const receiveNumber = ref(0);
const fetchOrderByCondition = async () => {
  try {
    const response = await orderService.getOrderByCondition({
      condition: valueCondition.value,
    });
    cancelNumber.value = response.cancel_number;
    prepareNumber.value = response.prepare_number;
    cancelNumber.value = response.cancel_number;
    shippingNumber.value = response.shipping_number;
    receiveNumber.value = response.receive_number;
    console.log("List Order: ", response);
    listOrder.value = response.data;
  } catch (error) {
    console.log(error.response);
  }
};

const sortedOrders = computed(() => {
  const startIndex = (currentPage.value - 1) * pageSize;
  const data = listOrder.value.slice(startIndex, startIndex + pageSize);
  return data.sort((a, b) => {
    const amountA = parseInt(a.total_cost);
    const amountB = parseInt(b.total_cost);
    if (sortOrder.value === "ascending") {
      return amountA - amountB;
    } else {
      return amountB - amountA;
    }
  });
});

//END ORDER HANLDE

// Calculate Handle
const calculateTotalCostThisMonth = ref(0);
const calculateShippingFeeThisMonth = ref(0);
const calculateTotalCost = ref(0);
const calculateShippingFee = ref(0);
const getCalculateCostThisMonth = async () => {
  try {
    const response = await orderService.calculateCost({ condition: "2" });
    calculateTotalCostThisMonth.value = response.total_cost;
    calculateShippingFeeThisMonth.value = response.shipping_fee;
  } catch (error) {
    console.log(error.response);
  }
};

const getCalculateCost = async () => {
  try {
    const response = await orderService.calculateCost({
      condition: valueCondition.value,
    });
    calculateTotalCost.value = response.total_cost;
    calculateShippingFee.value = response.shipping_fee;
  } catch (error) {
    console.log(error.response);
  }
};
//End calculate Handle

onMounted(() => {
  fetchListProduct();
  // fetchListCustomer();
  fetchUsersByCondition();
  fetchProductByCondition();
  fetchListOrderUser();
  fetchOrderByCondition();
  countCustomer();
  getCalculateCostThisMonth();
  getCalculateCost();
  // fetchListProductSales();
  setTimeout(() => {
    // console.log("Total: ", getTotalCostByUserId(24));
    // for (const index in listProductSales.value) {
    //   console.log(listProductSales.value[index]);
    // }
  }, 4000);
});

// Product handle
const listProduct = ref([]);
const listProductLength = ref(0);
const countProduct = ref(0);
const fetchListProduct = async () => {
  try {
    const response = await productService.getAll();
    countProduct.value = response.length;
    console.log(listProduct);
  } catch (error) {
    console.log(error.response);
  }
};

const fetchProductByCondition = async () => {
  try {
    const response = await productService.getProductByCondition({
      condition: valueCondition.value,
    });
    listProduct.value = response.data;
    listProductLength.value = response.data.length;
    console.log("Product Conditon: ", response);
  } catch (error) {
    console.log(error.response);
  }
};
const dataSearchProduct = computed(() => {
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

const toggleSortProduct = () => {
  sortOrder.value =
    sortOrder.value === "ascending" ? "descending" : "ascending";
};

const sortedProducts = computed(() => {
  const data = dataSearchProduct.value.slice();
  return data.sort((a, b) => {
    const amountA = parseInt(a.total_cost_detail);
    const amountB = parseInt(b.total_cost_detail);
    if (sortOrder.value === "ascending") {
      return amountA - amountB;
    } else {
      return amountB - amountA;
    }
  });
});

// End Product Handle

const getTotalCostByUserId = (userId) => {
  if (!listOrderUser.value) {
    return 0;
  } else {
    const orders = listOrderUser.value.filter((order) => order.id === userId);
    return orders.length > 0 ? orders[0]["total_cost"] : 0;
  }
};

const getTotalOrderByUserId = (userId) => {
  if (!listOrderUser.value) {
    return 0;
  } else {
    const orders = listOrderUser.value.filter((order) => order.id === userId);
    return orders.length > 0 ? orders[0]["total_orders"] : 0;
  }
};

function formatCurrency(amount) {
  return amount.toLocaleString("vi-VN", {
    style: "currency",
    currency: "VND",
  });
}

const datasearch = computed(() => {
  const dataSearch = String(search.value).trim();
  const startIndex = (currentPage.value - 1) * pageSize;
  if (!dataSearch) {
    return listCustomer.value.slice(startIndex, startIndex + pageSize);
  }

  return listCustomer.value.filter((data) => {
    return String(data.name).toLowerCase().includes(dataSearch.toLowerCase());
  });
});

const toggleSortOrder = () => {
  sortOrder.value =
    sortOrder.value === "ascending" ? "descending" : "ascending";
};

const sortedCustomers = computed(() => {
  const data = datasearch.value.slice();
  return data.sort((a, b) => {
    const amountA = parseInt(getTotalCostByUserId(a.id));
    const amountB = parseInt(getTotalCostByUserId(b.id));
    if (sortOrder.value === "ascending") {
      return amountA - amountB;
    } else {
      return amountB - amountA;
    }
  });
});

const convertTime = (dateTimeString) => {
  var dateTime = moment(dateTimeString);
  dateTime.utcOffset(7);

  var formattedDateTime = dateTime.format("DD/MM/YYYY HH:mm:ss");
  return formattedDateTime;
};

const handleCurrentChange = (val) => {
  currentPage.value = val;
  console.log(`current page: ${val}`);
};
</script>

<style scoped>
:global(h2#card-usage ~ .example .example-showcase) {
  background-color: var(--el-fill-color) !important;
}

.el-statistic {
  --el-statistic-content-font-size: 28px;
}

.statistic-card {
  height: 100%;
  padding: 20px;
  border-radius: 4px;
  background-color: var(--el-bg-color-overlay);
}

.statistic-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  font-size: 12px;
  color: var(--el-text-color-regular);
  margin-top: 16px;
}

.statistic-footer .footer-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.statistic-footer .footer-item span:last-child {
  display: inline-flex;
  align-items: center;
  margin-left: 4px;
}

.green {
  color: var(--el-color-success);
}
.red {
  color: var(--el-color-error);
}

.design-font {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 14px;
  font-weight: 600;
}

.design-content {
  margin-left: 40px;
  margin-top: 4px;
  font-size: 28px;
}
.form-design {
  width: 220px;
  margin-left: 1010px;
}
.ms-10 {
  margin-left: 180px;
}
</style>
, computed, watchimport productService from "@/services/product.service";,
computed
