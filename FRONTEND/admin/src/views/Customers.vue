<template>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <h1 class="mt-4">DANH SÁCH KHÁCH HÀNG</h1>

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
                  <th>STT</th>
                  <th class="col">Tên khách hàng</th>
                  <th class="col">Email</th>
                  <th class="col">Điểm tích lũy</th>
                  <th class="col">Thời gian tạo</th>
                  <th class="col">Thời gian cập nhật</th>
                  <th class="col">Chi tiết</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(user, index) in datasearch" :key="user.id">
                  <th scope="row">{{ index + 1 }}</th>
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.point }}</td>
                  <td>{{ convertTime(user.created_at) }}</td>
                  <td>{{ convertTime(user.updated_at) }}</td>
                  <td>
                    <el-button
                      plain
                      @click="
                        handleDetailUser(user.id, user.name, user.point_used)
                      "
                    >
                      Xem
                    </el-button>
                  </td>
                  <td>
                    <el-button @click="handleDeleteUser(user.id)"
                      ><i class="fa-solid fa-trash"></i
                    ></el-button>
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
                :total="Math.ceil(listCustomerLength / pageSize) * 10"
                class="mt-4"
              />
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
  <el-dialog v-model="dialogTableVisible" width="800">
    <el-row>
      <span>Khách hàng: {{ nameOfUser }}</span>
    </el-row>
    <el-row>
      <el-col :span="6">
        <el-statistic title="Tổng số đơn đã đặt" :value="orderBuyCount" />
      </el-col>
      <el-col :span="6">
        <el-statistic title="Số điểm đã dùng" :value="pointUsed" />
      </el-col>
      <el-col :span="6">
        <el-statistic title="Tổng số tiền đã mua" :value="totalCostBuying" />
      </el-col>
      <el-col :span="6">
        <el-statistic title="Số đơn hàng đã hủy" :value="orderCancelCount" />
      </el-col>
    </el-row>
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
                        <th scope="col">Mã hóa đơn</th>
                        <th scope="col">Tổng tiền</th>
                        <th scope="col">Phí giao hàng</th>
                        <th scope="col">Ngày đặt hàng</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Chi tiết</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="data in listOrderByUser" :key="data.order_id">
                        <th scope="row" style="color: #666666">
                          #{{ data.bill_id }}
                        </th>
                        <td>{{ formatCurrency(data.total_cost) }}</td>
                        <td>{{ formatCurrency(data.shipping_fee) }}</td>
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
                      </tr>
                    </tbody>
                  </table>
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
import userService from "@/services/user.service";
import { onMounted, ref, computed } from "vue";
import { useTransition } from "@vueuse/core";
import { ElLoading, ElMessage, ElMessageBox } from "element-plus";
import orderService from "@/services/order.service";

// Define panigation var
const currentPage = ref(1);
const pageSize = 8;
const listCustomerLength = ref(0);

// End

const listCustomer = ref([]);
const search = ref("");
const dialogTableVisible = ref(false);
// Detail User
const nameOfUser = ref("");
const idOfUser = ref("");
const listOrderByUser = ref([]);
const pointUsed = ref(0);
const orderBuyCount = computed(() => {
  return listOrderByUser.value.filter((order) => order.status == 3).length;
});
const orderCancelCount = computed(() => {
  return listOrderByUser.value.filter((order) => order.status == 0).length;
});

const totalCostBuying = computed(() => {
  return listOrderByUser.value.reduce((total, order) => {
    if (order.status == 3) {
      total += order.total_cost;
    }
    return total;
  }, 0); // Khởi tạo tổng là 0
});

//End detail user

const fetchOrderByUser = async (id) => {
  try {
    const response = await orderService.getByUserId(id);
    listOrderByUser.value = response.data;
    console.log("DETAIL USER: ", response);
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

const deleteUser = async (id) => {
  try {
    const response = await userService.deleteUser(id);
  } catch (error) {
    console.log(error);
  }
};

const handleDetailUser = (id, name, point) => {
  console.log("ID: ", id);
  idOfUser.value = id;
  nameOfUser.value = name;
  pointUsed.value = point;
  fetchOrderByUser(id);
  const loading = ElLoading.service({
    lock: true,
    text: "Đang tải...",
    background: "rgba(0, 0, 0, 0.7)",
  });
  setTimeout(() => {
    loading.close();
    dialogTableVisible.value = true;
  }, 1000);
};

const handleDeleteUser = (userId) => {
  ElMessageBox.confirm("Bạn có chắc muốn xóa người dùng?", "Thông báo", {
    confirmButtonText: "OK",
    cancelButtonText: "Cancel",
    type: "warning",
  })
    .then(() => {
      deleteUser(userId);
      const loading = ElLoading.service({
        lock: true,
        text: "Đang xử lý...",
        background: "rgba(0,0,0, 0.7)",
      });
      setTimeout(() => {
        fetchListCustomer();
        loading.close();

        ElMessage({
          type: "success",
          message: "Xóa người dùng thành công",
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
onMounted(() => {
  fetchListCustomer();
});

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
import userService from "@/services/user.service";
