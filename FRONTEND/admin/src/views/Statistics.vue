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
                  customerNumber
                }}</span></el-row
              >
            </el-col>
            <el-col :span="6">
              <el-row
                ><span class="design-font"
                  >Số khách hàng đã mua hàng</span
                ></el-row
              >
              <el-row
                ><span class="design-content">{{
                  customerNumber
                }}</span></el-row
              >
            </el-col>
          </el-row>
          <el-row class="mx-2 mt-3">
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
                    {{ getTotalOrderByUserId(user.id) }}
                  </td>
                  <td>
                    {{
                      formatCurrency(parseInt(getTotalCostByUserId(user.id)))
                    }}
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
          <el-row :gutter="16">
            <el-col :span="8">
              <div class="statistic-card">
                <el-statistic :value="98500">
                  <template #title>
                    <div style="display: inline-flex; align-items: center">
                      Daily active users
                      <el-tooltip
                        effect="dark"
                        content="Number of users who logged into the product in one day"
                        placement="top"
                      >
                        <el-icon style="margin-left: 4px" :size="12">
                          <Warning />
                        </el-icon>
                      </el-tooltip>
                    </div>
                  </template>
                </el-statistic>
                <div class="statistic-footer">
                  <div class="footer-item">
                    <span>than yesterday</span>
                    <span class="green">
                      24%
                      <el-icon>
                        <CaretTop />
                      </el-icon>
                    </span>
                  </div>
                </div>
              </div>
            </el-col>
            <el-col :span="8">
              <div class="statistic-card">
                <el-statistic :value="693700">
                  <template #title>
                    <div style="display: inline-flex; align-items: center">
                      Monthly Active Users
                      <el-tooltip
                        effect="dark"
                        content="Number of users who logged into the product in one month"
                        placement="top"
                      >
                        <el-icon style="margin-left: 4px" :size="12">
                          <Warning />
                        </el-icon>
                      </el-tooltip>
                    </div>
                  </template>
                </el-statistic>
                <div class="statistic-footer">
                  <div class="footer-item">
                    <span>month on month</span>
                    <span class="red">
                      12%
                      <el-icon>
                        <CaretBottom />
                      </el-icon>
                    </span>
                  </div>
                </div>
              </div>
            </el-col>
            <el-col :span="8">
              <div class="statistic-card">
                <el-statistic :value="72000" title="New transactions today">
                  <template #title>
                    <div style="display: inline-flex; align-items: center">
                      New transactions today
                    </div>
                  </template>
                </el-statistic>
                <div class="statistic-footer">
                  <div class="footer-item">
                    <span>than yesterday</span>
                    <span class="green">
                      16%
                      <el-icon>
                        <CaretTop />
                      </el-icon>
                    </span>
                  </div>
                </div>
              </div>
            </el-col>
          </el-row>
        </div>

        <!-- Orders View -->
        <div class="card mb-4" v-show="showOrder">
          <el-row :gutter="16">
            <el-col :span="8">
              <div class="statistic-card">
                <el-statistic :value="98500">
                  <template #title>
                    <div style="display: inline-flex; align-items: center">
                      Daily active users
                      <el-tooltip
                        effect="dark"
                        content="Number of users who logged into the product in one day"
                        placement="top"
                      >
                        <el-icon style="margin-left: 4px" :size="12">
                          <Warning />
                        </el-icon>
                      </el-tooltip>
                    </div>
                  </template>
                </el-statistic>
                <div class="statistic-footer">
                  <div class="footer-item">
                    <span>than yesterday</span>
                    <span class="green">
                      24%
                      <el-icon>
                        <CaretTop />
                      </el-icon>
                    </span>
                  </div>
                </div>
              </div>
            </el-col>
            <el-col :span="8">
              <div class="statistic-card">
                <el-statistic :value="693700">
                  <template #title>
                    <div style="display: inline-flex; align-items: center">
                      Monthly Active Users
                      <el-tooltip
                        effect="dark"
                        content="Number of users who logged into the product in one month"
                        placement="top"
                      >
                        <el-icon style="margin-left: 4px" :size="12">
                          <Warning />
                        </el-icon>
                      </el-tooltip>
                    </div>
                  </template>
                </el-statistic>
                <div class="statistic-footer">
                  <div class="footer-item">
                    <span>month on month</span>
                    <span class="red">
                      12%
                      <el-icon>
                        <CaretBottom />
                      </el-icon>
                    </span>
                  </div>
                </div>
              </div>
            </el-col>
            <el-col :span="8">
              <div class="statistic-card">
                <el-statistic :value="72000" title="New transactions today">
                  <template #title>
                    <div style="display: inline-flex; align-items: center">
                      New transactions today
                    </div>
                  </template>
                </el-statistic>
                <div class="statistic-footer">
                  <div class="footer-item">
                    <span>than yesterday</span>
                    <span class="green">
                      16%
                      <el-icon>
                        <CaretTop />
                      </el-icon>
                    </span>
                  </div>
                </div>
              </div>
            </el-col>
          </el-row>
        </div>

        <!-- Income View -->
        <div class="card mb-4" v-show="showIncome">
          <el-row :gutter="16">
            <el-col :span="8">
              <div class="statistic-card">
                <el-statistic :value="98500">
                  <template #title>
                    <div style="display: inline-flex; align-items: center">
                      Daily active users
                      <el-tooltip
                        effect="dark"
                        content="Number of users who logged into the product in one day"
                        placement="top"
                      >
                        <el-icon style="margin-left: 4px" :size="12">
                          <Warning />
                        </el-icon>
                      </el-tooltip>
                    </div>
                  </template>
                </el-statistic>
                <div class="statistic-footer">
                  <div class="footer-item">
                    <span>than yesterday</span>
                    <span class="green">
                      24%
                      <el-icon>
                        <CaretTop />
                      </el-icon>
                    </span>
                  </div>
                </div>
              </div>
            </el-col>
            <el-col :span="8">
              <div class="statistic-card">
                <el-statistic :value="693700">
                  <template #title>
                    <div style="display: inline-flex; align-items: center">
                      Monthly Active Users
                      <el-tooltip
                        effect="dark"
                        content="Number of users who logged into the product in one month"
                        placement="top"
                      >
                        <el-icon style="margin-left: 4px" :size="12">
                          <Warning />
                        </el-icon>
                      </el-tooltip>
                    </div>
                  </template>
                </el-statistic>
                <div class="statistic-footer">
                  <div class="footer-item">
                    <span>month on month</span>
                    <span class="red">
                      12%
                      <el-icon>
                        <CaretBottom />
                      </el-icon>
                    </span>
                  </div>
                </div>
              </div>
            </el-col>
            <el-col :span="8">
              <div class="statistic-card">
                <el-statistic :value="72000" title="New transactions today">
                  <template #title>
                    <div style="display: inline-flex; align-items: center">
                      New transactions today
                    </div>
                  </template>
                </el-statistic>
                <div class="statistic-footer">
                  <div class="footer-item">
                    <span>than yesterday</span>
                    <span class="green">
                      16%
                      <el-icon>
                        <CaretTop />
                      </el-icon>
                    </span>
                  </div>
                </div>
              </div>
            </el-col>
          </el-row>
        </div>
      </div>
    </main>
  </div>
</template>
<script setup>
import { ref, onMounted, computed } from "vue";
import orderService from "@/services/order.service";
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
const after_load = ref(false);
const search = ref("");
const showDetail = (type) => {
  switch (type) {
    case "customer":
      showCustomer.value = true;
      showProduct.value = false;
      showOrder.value = false;
      showIncome.value = false;
      break;
    case "product":
      showCustomer.value = false;
      showProduct.value = true;
      showOrder.value = false;
      showIncome.value = false;
      break;
    case "order":
      showCustomer.value = false;
      showProduct.value = false;
      showOrder.value = true;
      showIncome.value = false;
      break;
    case "income":
      showCustomer.value = false;
      showProduct.value = false;
      showOrder.value = false;
      showIncome.value = true;
      break;
    default:
      break;
  }
};

// Define panigation var
const currentPage = ref(1);
const pageSize = 8;
const listCustomerLength = ref(0);

// End

const customerNumber = ref(0);
const countCustomer = async () => {
  try {
    const response = await userService.getAll();
    customerNumber.value = response.length;
  } catch (error) {
    console.log(error.response);
  }
};

const fetchListCustomer = async () => {
  try {
    const response = await userService.getAll();
    listCustomer.value = response.data;
    listCustomerLength.value = response.length;
    console.log(response);
  } catch (error) {
    console.log(error.response);
  }
};

const fetchListOrderUser = async () => {
  try {
    const response = await orderService.getListOrderUser();
    listOrderUser.value = response.data;
    console.log(listOrderUser);
  } catch (error) {
    console.log(error.response);
  }
};

onMounted(() => {
  fetchListCustomer();
  fetchListOrderUser();
  countCustomer();
  setTimeout(() => {
    // console.log("Total: ", getTotalCostByUserId(24));
    after_load.value = true;
  }, 4000);
});

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
  font-size: 12px;
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
</style>
, computed
