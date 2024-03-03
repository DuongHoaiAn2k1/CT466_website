<template>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <h1 class="mt-4">DANH SÁCH ĐƠN HÀNG</h1>

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
              />
            </div>
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
                </tr>
              </thead>
              <tbody>
                <tr v-for="(data, index) in paginatedList" :key="data.order_id">
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
const currentPage = ref(1);
const listOrder = ref([]);
const pageSize = 8;
const orderLength = ref(0);

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

onMounted(() => {
  fetchListOrder();
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

const paginatedList = computed(() => {
  const startIndex = (currentPage.value - 1) * pageSize;
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
