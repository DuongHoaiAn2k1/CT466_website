<template>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <h1 class="mt-4">DANH SÁCH ĐƠN HÀNG</h1>

        <div class="card mb-4">
          <div class="card-header">
            <el-button @click="sortOrderShow('today')">Hôm nay</el-button>
            <el-button @click="sortOrderShow('all')">Tất cả</el-button>
            <el-date-picker
              class="ms-3"
              v-model="dateSelect"
              type="daterange"
              range-separator="Đến"
              start-placeholder="Ngày bắt đầu"
              end-placeholder="Ngày kết thúc"
              :size="size"
            />
            <el-button @click="sortDate">Lọc</el-button>
          </div>
          <div class="card-body">
            <div class="form-group pull-right contain-search"></div>
            <span class="counter pull-right"></span>
            <table class="table table-hover table-bordered results">
              <thead>
                <tr>
                  <th>STT</th>
                  <th class="col">Mã đơn hàng</th>
                  <th class="col">Hình ảnh</th>
                  <th class="col">Tổng tiền</th>
                  <th class="col">Thời gian đặt hàng</th>
                  <th class="col">Trạng thái</th>
                  <th class="col">Chi tiết</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-show="todayOrderShow"
                  v-for="(data, index) in sortTodayOrderList"
                  :key="data.order_id"
                >
                  <th scope="row">{{ index + 1 }}</th>
                  <td>#{{ data.bill_id }}</td>
                  <td>
                    <img
                      :src="
                        'http://127.0.0.1:8000/storage/' +
                        JSON.parse(data.order_detail[0].product.product_img)[0]
                      "
                      alt=""
                      width="50px"
                    />
                  </td>
                  <td>{{ formatCurrency(data.total_cost) }}</td>
                  <td>{{ convertTime(data.created_at) }}</td>
                  <td>
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
                  </td>
                  <td>
                    <router-link
                      :to="{
                        name: 'order-detail',
                        params: { id: data.order_id },
                      }"
                      >Xem chi tiết</router-link
                    >
                  </td>
                  <td>
                    <el-button @click="handleDeleteOrder(data.order_id)"
                      ><i class="fa-solid fa-trash"></i
                    ></el-button>
                  </td>
                </tr>

                <tr
                  v-show="allOrderShow"
                  v-for="(data, index) in orderListAll"
                  :key="data.order_id"
                >
                  <th scope="row">{{ index + 1 }}</th>
                  <td>#{{ data.bill_id }}</td>
                  <td>
                    <img
                      :src="
                        'http://127.0.0.1:8000/storage/' +
                        JSON.parse(data.order_detail[0].product.product_img)[0]
                      "
                      alt=""
                      width="50px"
                    />
                  </td>
                  <td>{{ formatCurrency(data.total_cost) }}</td>
                  <td>{{ convertTime(data.created_at) }}</td>
                  <td>
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
                  </td>
                  <td>
                    <router-link
                      :to="{
                        name: 'order-detail',
                        params: { id: data.order_id },
                      }"
                      >Xem chi tiết</router-link
                    >
                  </td>
                  <td>
                    <el-button @click="handleDeleteOrder(data.order_id)"
                      ><i class="fa-solid fa-trash"></i
                    ></el-button>
                  </td>
                </tr>
                <tr
                  v-show="byDateOrderShow"
                  v-for="(data, index) in sortBydayOrderList"
                  :key="data.order_id"
                >
                  <th scope="row">{{ index + 1 }}</th>
                  <td>#{{ data.bill_id }}</td>
                  <td>
                    <img
                      :src="
                        'http://127.0.0.1:8000/storage/' +
                        JSON.parse(data.order_detail[0].product.product_img)[0]
                      "
                      alt=""
                      width="50px"
                    />
                  </td>
                  <td>{{ formatCurrency(data.total_cost) }}</td>
                  <td>{{ convertTime(data.created_at) }}</td>
                  <td>
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
                  </td>
                  <td>
                    <router-link
                      :to="{
                        name: 'order-detail',
                        params: { id: data.order_id },
                      }"
                      >Xem chi tiết</router-link
                    >
                  </td>
                  <td>
                    <el-button @click="handleDeleteOrder(data.order_id)"
                      ><i class="fa-solid fa-trash"></i
                    ></el-button>
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="text-end">
              <el-pagination
                v-show="todayOrderShow"
                v-model:current-page="currentPage"
                @current-change="handleCurrentChange"
                small
                background
                layout="prev, pager, next"
                :total="Math.ceil(todayListLength / pageSize) * 10"
                class="mt-4"
              />

              <el-pagination
                v-show="allOrderShow"
                v-model:current-page="currentPage"
                @current-change="handleCurrentChange"
                small
                background
                layout="prev, pager, next"
                :total="Math.ceil(orderLength / pageSize) * 10"
                class="mt-4"
              />
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import orderService from "@/services/order.service";
import { onMounted, ref, computed } from "vue";
import { ElLoading, ElMessage, ElMessageBox } from "element-plus";
const currentPage = ref(1);
const listOrder = ref([]);
const pageSize = 8;
const orderLength = ref(0);
const size = (ref < "default") | "large" | ("small" > "default");
const dateSelect = ref("");
const fetchListOrder = async () => {
  try {
    const response = await orderService.getAll();
    listOrder.value = response.data;
    orderLength.value = response.length;
    console.log(response);
  } catch (error) {
    console.log(error.response);
  }
};

const deleteOrder = async (id) => {
  try {
    const response = await orderService.delete(id);
  } catch (error) {
    console.log(error.response);
  }
};

const handleDeleteOrder = (orderId) => {
  // alert(orderId);
  ElMessageBox.confirm("Bạn có chắc muốn xóa đơn hàng này?", "Thông báo", {
    confirmButtonText: "OK",
    cancelButtonText: "Cancel",
    type: "warning",
  })
    .then(() => {
      deleteOrder(orderId);
      const loading = ElLoading.service({
        lock: true,
        text: "Đang xử lý...",
        background: "rgba(0,0,0, 0.7)",
      });
      setTimeout(() => {
        fetchListOrder();
        fetchToDayOrder();
        loading.close();

        ElMessage({
          type: "success",
          message: "Xóa đơn hàng thành công",
        });
      }, 2000);
    })
    .catch(() => {
      // ElMessage({
      //   type: "info",
      //   message: "Delete canceled",
      // });
    });
};

//Handle show Order
const sortOrderShow = (type) => {
  switch (type) {
    case "today":
      todayOrderShow.value = true;
      allOrderShow.value = false;
      byDateOrderShow.value = false;
      break;
    case "all":
      todayOrderShow.value = false;
      allOrderShow.value = true;
      byDateOrderShow.value = false;
      break;
    default:
      type = "today";
      break;
  }
};

//End

const todayOrderShow = ref(true);
const allOrderShow = ref(false);
const byDateOrderShow = ref(false);
//
const todayOrderList = ref([]);
const todayListLength = ref(0);
const fetchToDayOrder = async () => {
  try {
    const response = await orderService.getToday();
    todayOrderList.value = response.data;
    todayListLength.value = response.length;
  } catch (error) {
    console.log(error.response);
  }
};
const sortTodayOrderList = computed(() => {
  const startIndex = (currentPage.value - 1) * pageSize;
  return todayOrderList.value.slice(startIndex, startIndex + pageSize);
});

const listOrderByDate = ref([]);
const orderByDateLength = ref(0);
const sortOrderByDate = async (startDate, endDate) => {
  try {
    const response = await orderService.getByDate({
      start_date: startDate,
      end_date: endDate,
    });
    listOrderByDate.value = response.data;
    orderByDateLength.value = response.length;
    console.log("By Date: ", response);
  } catch (error) {
    console.log(error.response);
  }
};
const sortDate = () => {
  byDateOrderShow.value = true;
  todayOrderShow.value = false;
  allOrderShow.value = false;
  const { startDate, endDate } = getDateRange();
  console.log("Start: ", startDate);
  console.log("endDate: ", endDate);
  sortOrderByDate(startDate, endDate);
};

const sortBydayOrderList = computed(() => {
  const startIndex = (currentPage.value - 1) * pageSize;
  return listOrderByDate.value.slice(startIndex, startIndex + pageSize);
});

onMounted(() => {
  fetchListOrder();
  fetchToDayOrder();

  setTimeout(() => {
    // console.log("HIII: ", paginatedList.value);
  }, 2000);
});

const getDateRange = () => {
  const dateString = dateSelect.value.toString();
  const commaIndex = dateString.indexOf(",");
  const startDateString = dateString.slice(0, commaIndex).trim();
  const endDateString = dateString.slice(commaIndex + 1).trim();

  // Parse the dates from input string
  let startDate = new Date(startDateString);
  let endDate = new Date(endDateString);
  // Adjust times
  startDate.setHours(0, 0, 0, 0); // Set start date to 00:00:00
  endDate.setHours(23, 59, 59, 999); // Set end date to 23:59:59

  // Convert to ISO string and adjust for timezone offset
  const offsetStart = startDate.getTimezoneOffset() * 60000; // Offset in milliseconds
  const offsetEnd = endDate.getTimezoneOffset() * 60000; // Offset in milliseconds
  const isoStartDate = new Date(
    startDate.getTime() - offsetStart
  ).toISOString();
  const isoEndDate = new Date(endDate.getTime() - offsetEnd).toISOString();

  return {
    startDate: isoStartDate,
    endDate: isoEndDate,
  };
};

function formatCurrency(amount) {
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

const handleCurrentChange = (val) => {
  currentPage.value = val;
  console.log(`current page: ${val}`);
};

const orderListAll = computed(() => {
  const startIndex = (currentPage.value - 1) * pageSize;
  // Sử dụng filteredOrders.value thay vì listOrder.value
  return listOrder.value.slice(startIndex, startIndex + pageSize);
});
</script>

<style scoped>
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
.contain-table {
  padding-top: 0px !important;
}
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
.el-col {
  text-align: center;
}

/* Table */
.table-responsive {
  max-height: 200px;
  overflow-y: auto;
}

.table thead th {
  position: sticky;
  top: 0;
  background-color: #ffffff;
  z-index: 1;
}

.table tbody th,
.table tbody td {
  z-index: 0;
}
</style>
