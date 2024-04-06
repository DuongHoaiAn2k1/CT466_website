<template>
  <div class="container-fluid">
    <div class="container">
      <!-- Title -->
      <div class="d-flex justify-content-between align-items-center py-2">
        <h2 class="h5 mb-0">
          <a href="#" class="text-muted"></a> Đơn hàng #{{ bill_id }}
        </h2>
      </div>

      <!-- Main content -->
      <div class="row">
        <div class="col-lg-8">
          <!-- Details -->
          <div class="mb-4">
            <div class="card-body">
              <div class="mb-3 d-flex justify-content-between">
                <div>
                  <span class="me-3">{{ day }}-{{ month }}-{{ years }}</span>
                  <span class="me-3"> #{{ bill_id }}</span>
                  <span class="badge rounded-pill bg-info">SHIPPING</span>
                </div>
                <div class="d-flex">
                  <button
                    class="btn btn-link p-0 me-3 d-none d-lg-block btn-icon-text"
                  >
                    <i class="bi bi-download"></i>
                  </button>
                  <div class="dropdown">
                    <button
                      class="btn btn-link p-0 text-muted"
                      type="button"
                      data-bs-toggle="dropdown"
                    >
                      <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                        <a class="dropdown-item" href="#"
                          ><i class="bi bi-pencil"></i> Edit</a
                        >
                      </li>
                      <li>
                        <a class="dropdown-item" href="#"
                          ><i class="bi bi-printer"></i> Print</a
                        >
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <table class="table table-borderless">
                <tbody>
                  <tr v-for="(data, index) in cartData">
                    <td>
                      <div class="d-flex mb-2">
                        <div class="flex-shrink-0">{{ index + 1 }}</div>
                        <div class="flex-lg-grow-1 ms-3">
                          <h6 class="small mb-0">
                            <a href="#" class="text-reset">{{
                              getProduct(data.product_id).product_name
                            }}</a>
                          </h6>
                          <span class="small"
                            >Giá:
                            {{
                              formatCurrency(
                                getProduct(data.product_id).product_price
                              )
                            }}</span
                          >
                        </div>
                      </div>
                    </td>
                    <td>x{{ data.quantity }}</td>
                    <td class="text-end">
                      {{
                        formatCurrency(
                          getProduct(data.product_id).product_price *
                            data.quantity
                        )
                      }}
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="2">Tổng tiền</td>
                    <td class="text-end">{{ formatCurrency(totalMoney) }}</td>
                  </tr>
                  <tr>
                    <td colspan="2">Phí vận chuyển</td>
                    <td class="text-end">
                      {{ formatCurrency(calculateShippingFee(number)) }}
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      Hỗ trợ phí ship từ cửa hàng
                      <span class="text-danger">(-30%)</span>
                    </td>
                    <td class="text-danger text-end">
                      -{{ formatCurrency(calculateShippingFee(number) * 0.3) }}
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">Điểm sử dụng</td>
                    <td class="text-end text-danger">
                      -{{ formatCurrency(pointUsed * 1000) }} &nbsp;
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">Điểm tích lũy</td>
                    <td class="text-end">
                      +{{ point }} điểm &nbsp;
                      <span
                        data-bs-toggle="tooltip"
                        data-bs-placement="bottom"
                        title="Đối với các đơn hàng trên 500k sẽ được cộng 10 điểm tích lũy"
                      >
                        <i class="fa-solid fa-circle-info"></i>
                      </span>
                    </td>
                  </tr>
                  <tr class="fw-bold">
                    <td colspan="2">TỔNG</td>
                    <td class="text-end">
                      {{
                        formatCurrency(
                          totalMoney -
                            pointUsed * 1000 +
                            calculateShippingFee(number) * 0.7
                        )
                      }}
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="mb-2 p-2">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h3 class="h6">
                    <span style="font-weight: 600">Tên người nhận:</span>
                    {{ addressOrder.name }}
                  </h3>
                  <h3 class="h6">
                    <span style="font-weight: 600">Địa chỉ nhận hàng:</span>
                    {{ addressOrder.address }}, {{ addressOrder.commue }},
                    {{ addressOrder.district }}, {{ addressOrder.city }}
                  </h3>

                  <h3 class="h6">
                    <span style="font-weight: 600">Số điện thoại:</span>
                    {{ addressOrder.phone }}
                  </h3>
                </div>
              </div>
            </div>
          </div>
          <div class="mb-4">
            <!-- Shipping information -->
            <div class="card-body">
              <div class="row">
                <div class="col-lg-6">
                  <h3 class="h6">Phương thức thanh toán</h3>
                </div>
              </div>
              <div class="row">
                <div class="d-flex flex-row pb-3">
                  <div class="d-flex align-items-center pe-2">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="radioNoLabel"
                      id="radioNoLabel1"
                      value=""
                      aria-label="..."
                      checked
                    />
                  </div>
                  <div
                    class="rounded border d-flex w-100 p-3 align-items-center"
                  >
                    <p class="mb-0">
                      <i class="fa-solid fa-truck"></i>
                      Thanh toán khi nhận hàng
                    </p>
                  </div>
                </div>

                <!-- <div class="d-flex flex-row">
                  <div class="d-flex align-items-center pe-2">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="radioNoLabel"
                      id="radioNoLabel2"
                      value=""
                      aria-label="..."
                    />
                  </div>
                  <div
                    class="rounded border d-flex w-100 p-3 align-items-center"
                  >
                    <p class="mb-0">
                      <i class="fab fa-cc-mastercard fa-lg text-dark pe-2"></i
                      >Mastercard Office
                    </p>
                    <div class="ms-auto">************1038</div>
                  </div>
                </div> -->
              </div>
              <div class="row mt-2">
                <div class="input-group mb-3">
                  <span
                    class="input-group-text fw-bold"
                    id="inputGroup-sizing-default"
                    >Sử dụng điểm tích lũy</span
                  >
                  <input
                    type="text"
                    class="form-control"
                    aria-label="Sizing example input"
                    aria-describedby="inputGroup-sizing-default"
                    v-model="pointUsed"
                  />
                </div>
              </div>
              <div class="row">
                <div class="mt-2 text-center">
                  <button
                    @click="handlePayment"
                    class="btn btn-dark btn-block btn-lg"
                  >
                    ĐẶT HÀNG
                    <i class="fas fa-long-arrow-alt-right"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ElLoading, ElNotification, ElMessage } from "element-plus";
import { useRouter } from "vue-router";
import { useCartStore } from "@/stores/cart";
import { onMounted, reactive, ref } from "vue";
import orderService from "@/services/order.service";
import { useAuthStore } from "@/stores/auth";
import productService from "@/services/product.service";
import cartService from "@/services/cart.service";
import userService from "@/services/user.service";
import order_detailService from "@/services/order_detail.service";
const router = useRouter();
const cartStore = useCartStore();
const authStore = useAuthStore();
const total = ref(0);
const bill_id = ref("");
const currentDay = new Date();
const day = currentDay.getDate();
const month = currentDay.getMonth() + 1;
const years = currentDay.getFullYear();
const listProduct = ref([]);
const cartData = ref([]);
const userData = ref([]);
const ListAddressOrder = ref([]);
const addressOrder = ref([]);
const totalMoney = ref(0);
const number = ref(0);
const point = ref(0);
const pointUsed = ref(0);
const currentPoint = ref(0);

const orderData = ref({});
const showSuccess = (message) => {
  ElMessage({
    message: message,
    type: "success",
  });
};

const showWarning = (message) => {
  ElMessage({
    message: message,
    type: "warning",
  });
};
const countOrder = async () => {
  try {
    const response = await orderService.count();
    console.log("Tong: ", response);
    total.value = response.total;
  } catch (error) {
    console.log(error.response);
  }
};

const fetchListProduct = async () => {
  try {
    const response = await productService.getAll();
    listProduct.value = response.listProduct;
    // console.log(listProduct);
  } catch (error) {
    console.log(error.response);
  }
};

const fetchUserData = async () => {
  try {
    const response = await userService.get(authStore.user_id);
    userData.value = response.data;
    ListAddressOrder.value = JSON.parse(response.data.address);
    addressOrder.value = ListAddressOrder.value[cartStore.addressToPay];
    // console.log("user data: ", ListAddressOrder);
  } catch (error) {
    console.log(error.response);
  }
};

const fetchCartData = async () => {
  try {
    const response = await cartService.get();
    cartData.value = response.data;
    // console.log(cartData);
  } catch (error) {
    console.log(error.response);
  }
};

const fetchCurrentPoint = async () => {
  try {
    const response = await userService.getCurrentPoint();
    currentPoint.value = response.point;
    console.log("Current Point: ", response);
  } catch (error) {
    console.log(error.response);
  }
};

const getProduct = (id) => {
  return listProduct.value.filter((data) => data.product_id == id)[0];
};

const handleTotal = () => {
  totalMoney.value = 0;
  number.value = 0;
  cartData.value.forEach((cart) => {
    console.log("Cart: ", cart);
    // console.log("Product: ", getProduct(cart.product_id).product_price);
    number.value = number.value + cart.quantity;
    totalMoney.value =
      totalMoney.value +
      getProduct(cart.product_id).product_price * cart.quantity;
  });

  if (
    totalMoney.value -
      pointUsed.value * 1000 +
      calculateShippingFee(number.value) * 0.7 >=
    500000
  ) {
    point.value = 10;
  }
};

const handleDeleteCart = async () => {
  try {
    const response = await cartService.deleteAll();
    console.log(response);
  } catch (error) {
    console.log(error.response);
  }
};

const handlePayment = async () => {
  try {
    orderData.value.bill_id = bill_id;
    orderData.value.status = "1";
    orderData.value.name = addressOrder.value.name;
    orderData.value.phone = addressOrder.value.phone;
    orderData.value.address = addressOrder.value.address;
    orderData.value.commue = addressOrder.value.commue;
    orderData.value.district = addressOrder.value.district;
    orderData.value.city = addressOrder.value.city;
    orderData.value.paid = false;
    orderData.value.shipping_fee = calculateShippingFee(number.value);
    orderData.value.total_cost =
      totalMoney.value -
      pointUsed.value * 1000 +
      calculateShippingFee(number.value) * 0.7;
    orderData.value.point_used_order = pointUsed.value;
    if (pointUsed.value != 0 && pointUsed.value > currentPoint.value) {
      showWarning(
        "Điểm sử dụng đã vượt quá điểm tích lũy. Điểm hiện tại của bạn là: " +
          currentPoint.value +
          ""
      );
    } else {
      const orderResponse = await orderService.create(orderData.value);
      const order_id = orderResponse.order_id;

      if (order_id !== null) {
        for (const item of cartData.value) {
          const orderDetailData = {
            order_id: order_id,
            product_id: item.product_id,
            quantity: item.quantity,
            total_cost_detail: getProduct(item.product_id).product_price,
          };
          const productDecreaseQuantity = {
            product_id: item.product_id,
            quantity: item.quantity,
          };
          await order_detailService.create(orderDetailData);
          await productService.decreaseProductQuantity(productDecreaseQuantity);
        }
      }
      await userService.pointDecrement({ point_used: pointUsed.value });

      if (orderData.value.total_cost > 300000) {
        await userService.pointIncrement();
      }
      const loading = ElLoading.service({
        lock: true,
        text: "Đang xử lý...",
        background: "rgba(0,0,0, 0.7)",
      });

      setTimeout(() => {
        showSuccess("Đặt hàng thành công");
        loading.close();
        setTimeout(() => {
          handleDeleteCart();
          cartStore.deleteCart();
          router.push({ name: "profile" });
        }, 500);
      }, 2000);

      console.log("Order data after create: ", orderResponse);
    }
  } catch (error) {
    console.log(error.response);
  }
};

onMounted(() => {
  countOrder().then(() => {
    bill_id.value = "DHA-" + authStore.user_id + "-" + total.value;
  });
  fetchListProduct();
  fetchCartData();
  fetchUserData();
  fetchCurrentPoint();
  setTimeout(() => {
    handleTotal();
    // console.log("Product: ", getProduct(9));
    // console.log("address x: ", addressOrder);
    console.log("Total Money: ", totalMoney);
    console.log("Phi ship: ", calculateShippingFee(4));
  }, 1000);
  // console.log(cartStore.addressToPay);
});

function calculateShippingFee(weight) {
  let baseFee = 22000; // Base fee per kg
  let additionalFee = 0;
  let shippingFee = 0;

  if (weight <= 1) {
    shippingFee = baseFee * weight;
  } else if (weight <= 2) {
    shippingFee = 27000 * weight;
  } else if (weight <= 3) {
    shippingFee = 30000 * weight;
  } else {
    additionalFee = 6000; // Increase 3000 VND for each kg over 3 kg
    shippingFee = (baseFee + additionalFee * (weight - 3)) * weight;
  }
  return shippingFee;
}

function formatCurrency(amount) {
  return amount.toLocaleString("vi-VN", {
    style: "currency",
    currency: "VND",
  });
}
</script>

<style>
body {
  background: #eee;
}
.card {
  box-shadow: 0 20px 27px 0 rgb(0 0 0 / 5%);
}
.card {
  position: relative;
  display: flex;
  flex-direction: column;
  min-width: 0;
  word-wrap: break-word;
  background-color: #fff;
  background-clip: border-box;
  border: 0 solid rgba(0, 0, 0, 0.125);
  border-radius: 1rem;
}
.text-reset {
  --bs-text-opacity: 1;
  color: inherit !important;
}
a {
  color: #5465ff;
  text-decoration: none;
}
</style>
