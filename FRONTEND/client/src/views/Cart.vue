<template>
  <div class="container">
    <h3
      style="
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS',
          sans-serif;
      "
    >
      GIỎ HÀNG CỦA BẠN
    </h3>
    <div class="row">
      <div class="col-xl-8">
        <div class="border shadow-none">
          <div class="card-body" v-for="cart in cartData" :key="cart.cart_id">
            <div class="row align-items-start border-bottom pb-3">
              <div class="col-md-7 d-flex">
                <div class="me-4">
                  <img
                    alt=""
                    :src="
                      'http://127.0.0.1:8000/storage/' +
                      JSON.parse(
                        getProductById(cart.product_id)[0].product_img
                      )[0]
                    "
                    class="avatar-lg rounded"
                  />
                </div>
                <div class="flex-grow-1 align-self-center overflow-hidden">
                  <div>
                    <h5 class="text-truncate font-size-18">
                      <a href="#" class="text-dark"
                        >{{ getProductById(cart.product_id)[0].product_name }}
                      </a>
                    </h5>
                    <p class="text-muted mb-0">
                      <i class="bx bxs-star text-warning"></i>
                      <i class="bx bxs-star text-warning"></i>
                      <i class="bx bxs-star text-warning"></i>
                      <i class="bx bxs-star text-warning"></i>
                      <i class="bx bxs-star-half text-warning"></i>
                    </p>
                    <p class="mb-0 mt-1">
                      Giá :
                      <span class="fw-medium">{{
                        formatCurrency(
                          getProductById(cart.product_id)[0].product_price
                        )
                      }}</span>
                    </p>
                  </div>
                </div>
                <div class="flex-shrink-0 ms-2">
                  <ul class="list-inline mb-0 font-size-16">
                    <li class="list-inline-item">
                      <a href="#" class="text-muted px-1">
                        <i class="mdi mdi-trash-can-outline"></i>
                      </a>
                    </li>
                    <li class="list-inline-item">
                      <a href="#" class="text-muted px-1">
                        <i class="mdi mdi-heart-outline"></i>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-md-2">
                <p class="text-muted mb-3">Số lượng</p>
                <div class="d-inline-flex">
                  <button
                    @click="decrementQuantity(cart)"
                    class="btn btn-sm btn-outline-secondary"
                  >
                    -
                  </button>
                  <span class="mx-2">{{ cart.quantity }}</span>
                  <button
                    @click="incrementQuantity(cart)"
                    class="btn btn-sm btn-outline-secondary"
                  >
                    +
                  </button>
                </div>
              </div>
              <div class="col-md-2">
                <div class="mt-1">
                  <p class="text-muted mb-3">Tổng</p>
                  <h5>
                    {{
                      formatCurrency(
                        getProductById(cart.product_id)[0].product_price *
                          cart.quantity
                      )
                    }}
                  </h5>
                </div>
              </div>
              <div class="col-md-1">
                <div class="mt-1">
                  <div class="mt-4">
                    <a @click="handldeDelete(cart.cart_id)" class="delete-btn">
                      <i class="fa fa-trash"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row my-4">
          <div class="col-sm-6">
            <router-link
              v-show="cartStore.count != 0"
              :to="{ name: 'home' }"
              class="btn btn-link text-muted"
            >
              <i class="mdi mdi-arrow-left me-1"></i> Tiếp tục mua hàng
            </router-link>
            <span v-show="cartStore.count == 0">Giỏ hàng của bạn trống</span>
          </div>
        </div>
        <!-- end row-->
      </div>

      <div class="col-xl-4">
        <div class="mt-5 mt-lg-0">
          <div class="border shadow-none">
            <div class="card-header bg-transparent border-bottom py-3 px-4">
              <h5 class="font-size-20 mb-0">Chi tiết giỏ hàng</h5>
            </div>
            <div class="card-body p-4 pt-2">
              <div class="table-responsive">
                <table class="table mb-0">
                  <tbody>
                    <tr>
                      <th>Tổng tiền:</th>
                      <td class="text-end">{{ formatCurrency(total) }}</td>
                    </tr>
                    <tr>
                      <th>Tổng sản phẩm :</th>
                      <td class="text-end">{{ number }}</td>
                    </tr>

                    <tr class="bg-light">
                      <th>Tổng :</th>
                      <td class="text-end">
                        <span class="fw-bold">{{ formatCurrency(total) }}</span>
                      </td>
                    </tr>
                    <tr class="bg-light">
                      <th>Địa chỉ:</th>

                      <td>
                        <select
                          name="addressToPay"
                          v-model="addressToPay"
                          id=""
                          class="text-truncate"
                        >
                          <option value="" selected disabled>
                            --Chọn một địa chỉ nhận hàng--
                          </option>
                          <option
                            v-for="(data, index) in listAddress"
                            :value="index"
                            :key="index"
                          >
                            {{ data.address }} {{ data.commue }}
                            {{ data.district }} {{ data.city }} -
                            {{ data.name }} - {{ data.phone }}
                          </option>
                        </select>
                        <span class="text-danger">{{ addressError }}</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- end table-responsive -->
              <div class="row">
                <button
                  @click="hanleRedirectPayment"
                  class="btn btn-dark mt-1 text-center"
                >
                  MUA HÀNG
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- end row -->
  </div>
</template>

<script setup>
import { useAuthStore } from "@/stores/auth";
import { useCartStore } from "@/stores/cart";
import { onMounted, watchEffect } from "vue";
import productService from "@/services/product.service";
import cartService from "@/services/cart.service";
import { ref } from "vue";
import { useRouter } from "vue-router";
import { ElNotification } from "element-plus";
import userService from "@/services/user.service";
import addressData from "@/assets/address/dvhc.json";
const authStore = useAuthStore();
const cartStore = useCartStore();
const addressError = ref(null);
const cartData = ref([]);
const userData = ref([]);
const listProduct = ref([]);
const total = ref(0);
const number = ref(0);
const listAddress = ref([]);
const router = useRouter();

// Index của địa chỉ trong address json
const addressToPay = ref("");
const fetchListProduct = async () => {
  try {
    const response = await productService.getAll();
    listProduct.value = response.listProduct;
    console.log("List product: ", response.listProduct);
  } catch (error) {
    console.log(error.response);
  }
};

const fetchUserData = async () => {
  try {
    const response = await userService.get(authStore.user_id);
    listAddress.value = JSON.parse(response.data.address);
    console.log("User Data: ", listAddress);
  } catch (error) {
    console.log(error.response);
  }
};

const fetchCartData = async () => {
  try {
    const response = await cartService.get(authStore.user_id);
    cartData.value = response.data;
    console.log(response);
  } catch (error) {
    console.log(error.response);
  }
};

const getProductById = (product_id) => {
  return listProduct.value.filter(
    (product) => product.product_id == product_id
  );
};

const handleIncrease = async (id) => {
  try {
    const response = await cartService.increase(id);
  } catch (error) {
    console.log(error.response);
  }
};

const handleDecrease = async (id) => {
  try {
    const response = await cartService.decrease(id);
  } catch (error) {
    console.log(error.response);
  }
};

const handldeDelete = async (id) => {
  // alert(id);
  try {
    const response = await cartService.delete(id);
    fetchCartData();
    await cartStore.fetchCartCount();
  } catch (error) {
    console.log(error.response);
  }
};

const hanldeTotal = () => {
  total.value = 0;
  number.value = 0;
  cartData.value.forEach((cart) => {
    console.log("Cart: ", cart);
    number.value = number.value + cart.quantity;
    total.value =
      total.value +
      getProductById(cart.product_id)[0].product_price * cart.quantity;
  });
};

const hanleRedirectPayment = () => {
  if (addressToPay.value === "") {
    addressError.value = "Địa chỉ không được để trống";
  } else if (addressToPay.value !== "" || addressToPay.value === 0) {
    addressError.value = "";
    cartStore.setAddress(addressToPay.value);
    router.push({ name: "payment" });
  }
};
const showWarning = (message) => {
  ElNotification({
    title: "Warning",
    message: message,
    type: "warning",
  });
};

onMounted(() => {
  fetchListProduct();
  fetchCartData().then(() => {
    hanldeTotal();
  });
  fetchUserData();

  console.log("Data address: ", addressData);
  // setTimeout(() => {
  //   hanldeTotal();
  // }, 1000);
});

watchEffect(() => {
  hanldeTotal();
  console.log("Index address: ", addressToPay.value);
});

function formatCurrency(amount) {
  return amount.toLocaleString("vi-VN", {
    style: "currency",
    currency: "VND",
  });
}

const incrementQuantity = (cartItem) => {
  if (cartItem.quantity < 10) {
    cartItem.quantity += 1;
    handleIncrease(cartItem.cart_id);
  } else {
    showWarning("Đã quá số lượng sản phẩm cho phép");
  }
};

const decrementQuantity = (cartItem) => {
  if (cartItem.quantity > 1) {
    cartItem.quantity -= 1;
    handleDecrease(cartItem.cart_id);
  } else {
    showWarning("Đã quá đạt số lượng tối thiểu");
  }
};
</script>

<style>
.avatar-lg {
  height: 5rem;
  width: 5rem;
}

.font-size-18 {
  font-size: 18px !important;
}

.text-truncate {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

a {
  text-decoration: none !important;
}

.w-xl {
  min-width: 160px;
}

.card {
  margin-bottom: 24px;
  -webkit-box-shadow: 0 2px 3px #e4e8f0;
  box-shadow: 0 2px 3px #e4e8f0;
}

.card {
  position: relative;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  min-width: 0;
  word-wrap: break-word;
  background-color: #fff;
  background-clip: border-box;
  border: 1px solid #eff0f2;
  border-radius: 1rem;
}

/* Thêm một lớp 'text-truncate' để hiển thị dấu '...' */
.text-truncate {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  /* Đặt chiều rộng để kiểm soát kích thước của hộp select */
  width: 100%; /* Bạn có thể điều chỉnh giá trị này theo nhu cầu */
}
</style>
