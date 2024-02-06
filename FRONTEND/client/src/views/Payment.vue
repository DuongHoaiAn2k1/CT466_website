<template>
  <div class="container-fluid">
    <div class="container">
      <!-- Title -->
      <div class="d-flex justify-content-between align-items-center py-3">
        <h2 class="h5 mb-0">
          <a href="#" class="text-muted"></a> Đơn hàng #{{ bill_id }}
        </h2>
      </div>

      <!-- Main content -->
      <div class="row">
        <div class="col-lg-8">
          <!-- Details -->
          <div class="card mb-4">
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
                  <tr v-for="data in cartData">
                    <td>
                      <div class="d-flex mb-2">
                        <div class="flex-shrink-0">
                          <img
                            src="https://www.bootdey.com/image/280x280/87CEFA/000000"
                            alt=""
                            width="35"
                            class="img-fluid"
                          />
                        </div>
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
                    <td colspan="2">Subtotal</td>
                    <td class="text-end">$159,98</td>
                  </tr>
                  <tr>
                    <td colspan="2">Shipping</td>
                    <td class="text-end">$20.00</td>
                  </tr>
                  <tr>
                    <td colspan="2">Discount (Code: NEWYEAR)</td>
                    <td class="text-danger text-end">-$10.00</td>
                  </tr>
                  <tr class="fw-bold">
                    <td colspan="2">TOTAL</td>
                    <td class="text-end">$169,98</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          <!-- Payment -->
          <div class="card mb-4">
            <div class="card-body">
              <div class="row">
                <div class="col-lg-6">
                  <h3 class="h6">Payment Method</h3>
                  <p>
                    Visa -1234 <br />
                    Total: $169,98
                    <span class="badge bg-success rounded-pill">PAID</span>
                  </p>
                </div>
                <div class="col-lg-6">
                  <h3 class="h6">Billing address</h3>
                  <address>
                    <strong>John Doe</strong><br />
                    1355 Market St, Suite 900<br />
                    San Francisco, CA 94103<br />
                    <abbr title="Phone">P:</abbr> (123) 456-7890
                  </address>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <!-- Customer Notes -->
          <div class="card mb-4">
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
          <div class="card mb-4">
            <!-- Shipping information -->
            <div class="card-body">
              <h3 class="h6">Shipping Information</h3>
              <strong>FedEx</strong>
              <span
                ><a href="#" class="text-decoration-underline" target="_blank"
                  >FF1234567890</a
                >
                <i class="bi bi-box-arrow-up-right"></i>
              </span>
              <hr />
              <h3 class="h6">Address</h3>
              <address>
                <strong>John Doe</strong><br />
                1355 Market St, Suite 900<br />
                San Francisco, CA 94103<br />
                <abbr title="Phone">P:</abbr> (123) 456-7890
              </address>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useCartStore } from "@/stores/cart";
import { onMounted, ref } from "vue";
import orderService from "@/services/order.service";
import { useAuthStore } from "@/stores/auth";
import productService from "@/services/product.service";
import cartService from "@/services/cart.service";
import userService from "@/services/user.service";
const cartStore = useCartStore();
const authStore = useAuthStore();
const total = ref(0);
const bill_id = "DHA-" + authStore.user_id + "-" + total.value;
const currentDay = new Date();
const day = currentDay.getDate();
const month = currentDay.getMonth() + 1;
const years = currentDay.getFullYear();
const listProduct = ref([]);
const cartData = ref([]);
const userData = ref([]);
const ListAddressOrder = ref([]);
const addressOrder = ref([]);

const countOrder = async () => {
  try {
    const response = await orderService.count();
    console.log(response);
    total.value = response.total;
  } catch (error) {
    console.log(error.response);
  }
};

const fetchListProduct = async () => {
  try {
    const response = await productService.getAll();
    listProduct.value = response.listProduct;
    console.log(listProduct);
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
    console.log("user data: ", ListAddressOrder);
  } catch (error) {
    console.log(error.response);
  }
};

const fetchCartData = async () => {
  try {
    const response = await cartService.get();
    cartData.value = response.data;
    console.log(cartData);
  } catch (error) {
    console.log(error.response);
  }
};

const getProduct = (id) => {
  return listProduct.value.filter((data) => data.product_id == id)[0];
};

onMounted(() => {
  countOrder();
  fetchListProduct();
  fetchCartData();
  fetchUserData();

  setTimeout(() => {
    console.log("Product: ", getProduct(9));
    console.log("address x: ", addressOrder);
  }, 1000);
  // console.log(cartStore.addressToPay);
  // console.log(bill_id);
});

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
