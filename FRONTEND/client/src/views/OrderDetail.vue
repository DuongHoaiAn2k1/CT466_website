<template>
  <section class="h-100 gradient-custom">
    <div class="container py-2 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-lg-10 col-xl-8">
          <div class="card" style="border-radius: 10px">
            <div class="card-header px-4 py-3">
              <h5 class="text-muted mb-0">
                Đơn hàng của bạn,
                <span style="color: #a8729a">{{ address.name }}</span
                >!
              </h5>
              <el-steps :active="order.status" align-center>
                <el-step title="Đang chuẩn bị" />
                <el-step title="Đang giao" />
                <el-step title="Đã nhận" />
              </el-steps>
            </div>

            <div class="card-body p-4">
              <div
                class="d-flex justify-content-between align-items-center mb-4"
              >
                <p class="lead fw-normal mb-0" style="color: #a8729a">
                  Chi tiết đơn hàng
                </p>
                <p class="small text-muted mb-0">
                  Mã đơn hàng : #{{ order.bill_id }}
                </p>
              </div>
              <div class="card shadow-0 border mb-4">
                <div class="row">
                  <div class="col-md-2"></div>
                  <div
                    class="col-md-2 text-center d-flex justify-content-center align-items-center"
                  >
                    <p class="text fw-bold mb-0">Tên sản phẩm</p>
                  </div>
                  <div
                    class="col-md-2 text-center d-flex justify-content-center align-items-center"
                  >
                    <p class="text fw-bold mb-0 small">Số lượng</p>
                  </div>
                  <div
                    class="col-md-2 text-center d-flex justify-content-center align-items-center"
                  >
                    <p class="text fw-bold mb-0 small">Kg/sản phẩm</p>
                  </div>
                  <div
                    class="col-md-2 text-center d-flex justify-content-center align-items-center"
                  >
                    <p class="text fw-bold mb-0 small">Đơn giá</p>
                  </div>
                  <div
                    class="col-md-2 text-center d-flex justify-content-center align-items-center"
                  >
                    <p class="text fw-bold mb-0 small">Tổng</p>
                  </div>
                </div>
                <div
                  class="card-body"
                  v-for="data in order.order_detail"
                  :key="data.order_detail_id"
                >
                  <div class="row">
                    <div class="col-md-2">
                      <img
                        :src="
                          'http://127.0.0.1:8000/storage/' +
                          JSON.parse(data.product.product_img)[0]
                        "
                        class="img-fluid"
                        alt="Phone"
                      />
                    </div>
                    <div
                      class="col-md-2 text-center d-flex justify-content-center align-items-center"
                    >
                      <p class="text-muted mb-0">
                        {{ data.product.product_name }}
                      </p>
                    </div>
                    <div
                      class="col-md-2 text-center d-flex justify-content-center align-items-center"
                    >
                      <p class="text-muted mb-0 small">x{{ data.quantity }}</p>
                    </div>
                    <div
                      class="col-md-2 text-center d-flex justify-content-center align-items-center"
                    >
                      <p class="text-muted mb-0 small">1kg</p>
                    </div>
                    <div
                      class="col-md-2 text-center d-flex justify-content-center align-items-center"
                    >
                      <p class="text-muted mb-0 small">
                        {{ formatCurrency(data.product.product_price) }}/kg
                      </p>
                    </div>
                    <div
                      class="col-md-2 text-center d-flex justify-content-center align-items-center"
                    >
                      <p class="text-muted mb-0 small">
                        {{
                          formatCurrency(
                            data.product.product_price * data.quantity
                          )
                        }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="d-flex justify-content-between pt-2">
                <p class="fw-bold mb-0">Chi tiết</p>
                <p class="text-muted mb-0">
                  <span class="fw-bold me-4">Tổng</span>
                  {{ formatCurrency(order.total_cost) }}
                </p>
              </div>

              <div class="d-flex justify-content-between pt-2">
                <p class="text-muted mb-0">
                  <span class="fw-bold">Tên người nhận:</span>
                  {{ address.name }}
                </p>
                <p class="text-muted mb-0">
                  <span class="fw-bold me-4">Hỗ trợ ship</span>
                  <span class="text-danger"
                    >-
                    {{ ((order.shipping_fee * 0.3) / 0.7).toFixed(0) }} ₫</span
                  >
                </p>
              </div>

              <div class="d-flex justify-content-between">
                <p class="text-muted mb-0">
                  <span class="fw-bold">Số điện thoại:</span>
                  {{ address.phone }}
                </p>
                <p class="text-muted mb-0">
                  <span class="fw-bold me-4">Phí giao hàng</span>
                  {{ formatCurrency(order.shipping_fee) }}
                </p>
              </div>

              <div class="d-flex justify-content-between mb-0">
                <p class="text-muted mb-0">
                  <span class="fw-bold">Địa chỉ:</span>
                  {{ address.address }}, {{ address.commue }},
                  {{ address.district }}, {{ address.city }}
                </p>
                <p class="text-muted mb-0">
                  <span class="fw-bold me-4">Điểm dùng</span>
                  - {{ order.point_used_order }} (
                  {{ formatCurrency(order.point_used_order * 1000) }})
                </p>
              </div>
              <div class="d-flex justify-content-between">
                <p class="text-muted mb-0">
                  <span class="fw-bold">Ngày đặt:</span>
                  {{ covertTime(order.created_at) }}
                </p>
              </div>
            </div>

            <div class="text-center my-1" v-show="order.status == 1">
              <el-button size="large" @click="centerDialogVisible = true"
                ><span style="font-size: 20px">Hủy đơn hàng</span></el-button
              >
            </div>
            <el-dialog v-model="centerDialogVisible" width="500" align-center>
              <span>Bạn có chắc chắn muốn hủy đơn hàng</span>
              <template #footer>
                <div class="dialog-footer">
                  <el-button @click="centerDialogVisible = false"
                    >Trở lại</el-button
                  >
                  <el-button type="primary" @click="handleCancelOrder(orderId)">
                    Đồng ý
                  </el-button>
                </div>
              </template>
            </el-dialog>
            <div
              class="card-footer border-0 px-4 py-2"
              style="
                background-color: #0075b1;
                border-bottom-left-radius: 10px;
                border-bottom-right-radius: 10px;
              "
            >
              <h5
                class="d-flex align-items-center justify-content-end text-white text-uppercase mb-0"
              >
                TỔNG PHẢI THANH TOÁN:
                <span class="h2 mb-0 ms-2">{{
                  formatCurrency(order.total_cost)
                }}</span>
              </h5>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import orderService from "@/services/order.service";
import { onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import { ElLoading, ElNotification, ElMessage } from "element-plus";
import userService from "@/services/user.service";
import productService from "@/services/product.service";
const centerDialogVisible = ref(false);
const order = ref([]);
const address = ref([]);
const route = useRoute();
const router = useRouter();
const orderId = route.params.id;
const productIncrease = ref([]);
const fetchOrder = async () => {
  try {
    const response = await orderService.get(orderId);
    order.value = response.data;
    address.value = JSON.parse(response.data.order_address);
    productIncrease.value = response.data.order_detail;
    console.log(response);
  } catch (error) {
    console.log(error.response);
  }
};

const handleCancelOrder = async (orderId) => {
  try {
    const response = await orderService.cancel(orderId);
    centerDialogVisible.value = false;
    const loading = ElLoading.service({
      lock: true,
      text: "Đang xử lý...",
      background: "rgba(0,0,0, 0.7)",
    });
    await userService.restorePointPaid({
      point_paid: order.value.point_used_order,
    });
    await productService.increaseProductQuantity(productIncrease.value);
    setTimeout(() => {
      loading.close();
      showCancelSuccess();
      setTimeout(() => {
        router.push({ name: "profile" });
      }, 1000);
    }, 2000);
  } catch (error) {
    console.log(error.response);
  }
};

const showCancelSuccess = () => {
  ElMessage({
    message: "Hủy dơn hàng thành công.",
    type: "success",
  });
};

onMounted(() => {
  fetchOrder();
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
</script>

<style>
.gradient-custom {
  /* fallback for old browsers */
  background: #0075b1;

  /* Chrome 10-25, Safari 5.1-6 */
  background: -webkit-linear-gradient(
    to top left,
    rgb(42, 204, 236),
    rgba(246, 243, 255, 1)
  );

  /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
  background: linear-gradient(
    to top left,
    rgb(70, 222, 252),
    rgba(246, 243, 255, 1)
  );
}
</style>
