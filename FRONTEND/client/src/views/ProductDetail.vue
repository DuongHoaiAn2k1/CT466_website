<template>
  <div class="container">
    <div class="single_product">
      <div
        class="container-fluid"
        style="background-color: #fff; padding: 11px"
      >
        <div class="row" v-show="!loading">
          <div class="col-lg-4 order-lg-1 order-2">
            <div class="container">
              <div
                id="carouselExampleControls"
                class="carousel slide"
                data-bs-ride="carousel"
              >
                <div class="carousel-inner">
                  <div class="carousel-item active">
                    <img
                      class="d-block w-100"
                      :src="'http://127.0.0.1:8000/storage/' + img_1"
                    />
                  </div>
                  <div class="carousel-item">
                    <img
                      class="d-block w-100"
                      :src="'http://127.0.0.1:8000/storage/' + img_2"
                      alt="..."
                    />
                  </div>
                  <div class="carousel-item">
                    <img
                      class="d-block w-100"
                      :src="'http://127.0.0.1:8000/storage/' + img_3"
                      alt="..."
                    />
                  </div>
                </div>
                <button
                  class="carousel-control-prev"
                  type="button"
                  data-bs-target="#carouselExampleControls"
                  data-bs-slide="prev"
                >
                  <span
                    class="carousel-control-prev-icon"
                    aria-hidden="true"
                  ></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button
                  class="carousel-control-next"
                  type="button"
                  data-bs-target="#carouselExampleControls"
                  data-bs-slide="next"
                >
                  <span
                    class="carousel-control-next-icon"
                    aria-hidden="true"
                  ></span>
                  <span class="visually-hidden">Next</span>
                </button>
              </div>
            </div>
          </div>
          <div class="col-lg-8 order-3">
            <div class="product_description">
              <div class="product_name">
                {{ product.product_name }}
              </div>
              <!-- <div class="product-rating">
                <span class="badge badge-success"
                  ><i class="fa fa-star"></i> 4.5 Star</span
                >
                <span class="rating-review">35 Ratings & 45 Reviews</span>
              </div> -->
              <div>
                <span class="product_price">{{
                  formatCurrency(product.product_price)
                }}</span>
              </div>
              <p class="text-muted mb-0">
                <el-rate
                  v-model="averageRating"
                  disabled
                  show-score
                  text-color="#ff9900"
                  score-template="{value} points"
                />
                <!-- <i class="bx bxs-star-half text-warning"></i> -->
              </p>
              <div>
                <span class="product_saved">Khối lượng:</span>
                <span style="color: black">1kg</span>
              </div>
              <hr class="singleline" />
              <div>
                <p
                  v-for="(des, index) in productDetail"
                  class="product_info"
                  :key="index"
                >
                  {{ des }}.
                </p>
              </div>
              <div class="order_info d-flex flex-row"></div>
              <div class="row">
                <div class="col-xs-6" style="margin-left: 13px">
                  <div class="product_quantity">
                    <span>Số lượng: </span>
                    <input
                      id="quantity_input"
                      type="text"
                      pattern="[0-9]*"
                      :value="quantity"
                    />
                    <div class="quantity_buttons">
                      <div
                        id="quantity_inc_button"
                        class="quantity_inc quantity_control"
                        @click="incrementQuantity"
                      >
                        <i class="fas fa-chevron-up"></i>
                      </div>
                      <div
                        id="quantity_dec_button"
                        class="quantity_dec quantity_control"
                        @click="decrementQuantity"
                      >
                        <i class="fas fa-chevron-down"></i>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6">
                  <button
                    @click="handleAddToCart(product.product_id)"
                    type="button"
                    class="btn btn-dark shop-button me-1"
                  >
                    Thêm vào giỏ hàng
                  </button>
                  <button
                    @click="handleBuyNow(product.product_id)"
                    type="button"
                    class="btn btn-success shop-button"
                  >
                    Mua ngay
                  </button>
                  <div
                    class="product_fav"
                    v-show="!product.liked"
                    @click="createFavorite(product.product_id)"
                  >
                    <i class="fa-regular fa-heart"></i>
                  </div>
                  <div
                    class="product_fav"
                    v-show="product.liked"
                    @click="deleteFavorite(product.product_id)"
                  >
                    <i class="fa-solid fa-heart"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="text-center">
            <div class="v-spinner" v-show="loading">
              <div
                class="v-pulse v-pulse1"
                :style="[spinnerStyle, spinnerDelay1]"
              ></div>
              <div
                class="v-pulse v-pulse2"
                :style="[spinnerStyle, spinnerDelay2]"
              ></div>
              <div
                class="v-pulse v-pulse3"
                :style="[spinnerStyle, spinnerDelay3]"
              ></div>
            </div>
          </div>
        </div>
        <div class="row row-underline">
          <div class="col-md-6">
            <span class="deal-text">Các sản phẩm có liên quan</span>
          </div>
          <div class="col-md-6"></div>
        </div>
        <div class="row row-underline">
          <el-scrollbar>
            <div class="scrollbar-flex-content">
              <div
                v-for="product in listProductByCategory"
                :key="product.product_id"
                class="scrollbar-demo-item"
              >
                <div class="card h-100 shadow-sm">
                  <router-link
                    :to="{
                      name: 'product-detail',
                      params: { id: product.product_id },
                    }"
                  >
                    <img
                      :src="
                        'http://127.0.0.1:8000/storage/' +
                        JSON.parse(product.product_img)[0]
                      "
                      class="card-img-top-2"
                      alt="product.title"
                    />
                  </router-link>

                  <!-- <div class="label-top shadow-sm">
            <a class="text-white" href="#">asus</a>
          </div> -->
                  <div class="card-body">
                    <h5 style="margin-bottom: 0px" class="card-title">
                      <p class="name-product-2">{{ product.product_name }}</p>
                      <p
                        style="
                          margin-bottom: 0px !important;
                          font-weight: 600;
                          font-size: 15px;
                        "
                        class="mt-2 text-danger text-center"
                      >
                        {{ formatCurrency(product.product_price) }}/kg
                      </p>
                    </h5>
                  </div>
                </div>
              </div>
            </div>
          </el-scrollbar>
        </div>
        <div class="container bootstrap snippets bootdey">
          <div v-show="!isReviewProduct" class="row mt-2">
            <div class="col-3"></div>
            <div class="col-6">
              <h2>Cho chúng tôi xin đánh giá về sản phẩm</h2>

              <div class="mb-3">
                <el-rate v-model="rateComment" allow-half />
              </div>
              <div class="mb-3">
                <label for="comment">Đánh giá:</label>
                <textarea
                  class="form-control"
                  id="comment"
                  rows="3"
                  v-model="commentValue"
                ></textarea>
              </div>
              <button @click="handleComment" class="btn btn-dark">
                Đánh giá
              </button>
            </div>
            <div class="col-3"></div>
          </div>
          <div v-show="dataReviewByProductLength != 0" class="row mt-2">
            <div class="panel panel-default widget">
              <div class="panel-heading">
                <span class="glyphicon glyphicon-comment"></span>
                <h3 class="panel-title">Các phản hồi của khách hàng</h3>
              </div>
              <div class="panel-body">
                <ul
                  class="list-group"
                  v-for="data in listCommentPagination"
                  :key="data.review_id"
                >
                  <li class="list-group-item">
                    <div class="row">
                      <div class="col-xs-10 col-md-11">
                        <div>
                          <a href="#">
                            {{ data.user.name }}
                            <el-rate v-model="data.rating" allow-half
                          /></a>
                          <div class="mic-info">
                            Thời gian: {{ convertTime(data.created_at) }}
                          </div>
                        </div>
                        <div></div>
                        <div class="comment-text">{{ data.comment }}</div>
                      </div>
                    </div>
                  </li>
                </ul>
                <div class="text-end" v-show="dataReviewByProductLength != 0">
                  <el-pagination
                    v-model:current-page="currentPage"
                    @current-change="handleCurrentChange"
                    small
                    background
                    layout="prev, pager, next"
                    :total="
                      Math.ceil(dataReviewByProductLength / pageSize) * 10
                    "
                    class="mt-4"
                  />
                </div>
                <div v-show="dataReviewByProductLength == 0">
                  <p class="text-center">Không có sản phẩm nào</p>
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
import "../assets/css/PulseLoader.css";
import usePulseLoader from "../assets/js/PulseLoader.js";
import productService from "@/services/product.service";
import { computed, onMounted, ref, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import cartService from "@/services/cart.service";
import favoriteService from "@/services/favorite.service";
import reviewService from "@/services/review.service";
import { useCartStore } from "@/stores/cart";
import { ElMessage } from "element-plus";
const { loading, spinnerStyle, spinnerDelay1, spinnerDelay2, spinnerDelay3 } =
  usePulseLoader();
const cartStore = useCartStore();
const authStore = useAuthStore();
const product = ref([]);
const route = useRoute();
const router = useRouter();
const productId = computed(() => route.params.id);
const productDetail = ref([]);
const listProductByCategory = ref([]);
const listFavorite = ref([]);
const img_1 = ref("");
const img_2 = ref("");
const img_3 = ref("");
const rateComment = ref();
const quantity = ref(1);
const commentValue = ref("");
const isReviewProduct = ref(true);
const listCommentReview = ref([]);
const currentPage = ref(1);
const pageSize = 6;
const averageRating = ref(0);
// const addToCartSuccess = () => {
//   ElMessage({
//     message: "Thêm vào giỏ hàng thành công",
//     type: "success",
//   });
// };
const categoryId = ref(0);

const increaseProductViews = async () => {
  try {
    const response = await productService.increaseView(productId.value);
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
const fetchProduct = async () => {
  try {
    const response = await productService.get(productId.value);
    product.value = response.data;
    categoryId.value = response.data.category_id;
    console.log("category id: ", categoryId.value);
    img_1.value = JSON.parse(product.value.product_img)[0];
    img_2.value = JSON.parse(product.value.product_img)[1];
    img_3.value = JSON.parse(product.value.product_img)[2];
    console.log("Product detail: ", img_1);
  } catch (error) {
    console.log(error.response);
  }
};

const dataReviewByProductLength = ref(0);
const fetchReviewByProduct = async () => {
  try {
    const response = await reviewService.getByProduct(productId.value);
    listCommentReview.value = response.data;
    averageRating.value = response.average_rating;
    dataReviewByProductLength.value = response.length;
  } catch (error) {
    console.log(error.response);
  }
};

const fetchProductByCategoryId = async () => {
  try {
    const response = await productService.getProductFromCategoryId(
      categoryId.value
    );
    listProductByCategory.value = response.data;
    console.log(listProductByCategory);
  } catch (error) {
    console.log(error.response);
  }
};
const fetchListFavorite = async () => {
  try {
    const response = await favoriteService.getByUser();
    listFavorite.value = response.data;
    console.log(response);
  } catch (error) {
    console.log(error.response);
  }
};

const checkUserReviewProduct = async () => {
  try {
    const response = await reviewService.userHasReviewedProduct(
      productId.value
    );
    console.log("Check user review product: ", response);
    isReviewProduct.value = response.data;
  } catch (error) {
    console.log(error.response);
  }
};

onMounted(() => {
  fetchListFavorite();
  cartStore.fetchCartCount();
  fetchProduct().then(() => {
    productDetail.value = product.value.product_des.split(".");
    console.log("YEYE: ", productDetail);
    setTimeout(() => {
      updateDetailProductWithLikes();
      fetchProductByCategoryId();

      loading.value = false;
    }, 500);

    checkUserReviewProduct();
  });

  increaseProductViews();
  fetchReviewByProduct();
  // setTimeout(() => {
  //   console.log("Product Detail After Updated: ", product);
  // }, 1000);
});

const handleAddToCart = (product_id) => {
  addToCart(product_id);
  showSuccess("Thêm vào giỏ hàng thành công");
};

const handleBuyNow = (product_id) => {
  addToCart(product_id);
  router.push({ name: "cart" });
};

const addToCart = async (product_id) => {
  try {
    const user_id = authStore.user_id;
    const response = await cartService.create({
      user_id: user_id,
      product_id: product_id,
      quantity: quantity.value,
    });
    await cartStore.fetchCartCount();
    // console.log("Ket qua them: ", response);
    // showSuccess("Thêm vào giỏ hàng thành công");
    // alert("Thêm vào giỏ hành thành công");
  } catch (error) {
    console.log(error.response);
    // if (error.response.data.message === "Qúa số lượng cho phép") {
    //   // addToCartWarning();
    // }
  }
};

const updateDetailProductWithLikes = () => {
  const isLiked = listFavorite.value.some(
    (favorite) => favorite.product_id == productId.value
  );

  product.value.liked = isLiked;
};

const deleteFavorite = async (productId) => {
  // console.log(productId);
  try {
    const response = await favoriteService.delete(productId);
    fetchListFavorite();
    showSuccess("Đã loại bỏ khỏi danh sách yêu thích");
    setTimeout(() => {
      updateDetailProductWithLikes();
    }, 1000);
  } catch (error) {
    console.log(error.response);
  }
};

const createFavorite = async (productId) => {
  try {
    const response = await favoriteService.create({ product_id: productId });
    fetchListFavorite();
    showSuccess("Thêm vào danh sách yêu thích thành công");
    rateComment.value = 0;
    commentValue.value = 0;
    setTimeout(() => {
      updateDetailProductWithLikes();
    }, 500);
  } catch (error) {
    console.log(error.response);
  }
};

const handleComment = () => {
  // console.log("Rate Comment: ", rateComment.value);
  // console.log("Comment: ", commentValue.value);
  createComment(productId.value, rateComment.value, commentValue.value);
  showSuccess("Cảm ơn bạn đã đánh giá");
  fetchReviewByProduct();
  checkUserReviewProduct();
};

const listCommentPagination = computed(() => {
  const startIndex = (currentPage.value - 1) * pageSize;
  return listCommentReview.value.slice(startIndex, startIndex + pageSize);
});

const handleCurrentChange = (val) => {
  currentPage.value = val;
  console.log(`current page: ${val}`);
};

watch(productId, () => {
  fetchProduct();
  fetchProductByCategoryId();
  increaseProductViews();
  fetchReviewByProduct();
  checkUserReviewProduct();
});

const createComment = async (productId, rating, comment) => {
  try {
    const response = await reviewService.create({
      product_id: productId,
      rating: rating,
      comment: comment,
    });
    console.log(response);
  } catch (error) {
    console.log(error.response);
  }
};
const incrementQuantity = () => {
  if (quantity.value < 10) {
    quantity.value++;
  }
};

const decrementQuantity = () => {
  if (quantity.value > 1) {
    quantity.value--;
  }
};

const formatCurrency = (amount) => {
  return (
    amount?.toLocaleString("vi-VN", {
      style: "currency",
      currency: "VND",
    }) || ""
  );
};

const convertTime = (dateTimeString) => {
  var dateTime = moment(dateTimeString);
  dateTime.utcOffset(7);

  var formattedDateTime = dateTime.format("DD/MM/YYYY HH:mm:ss");
  return formattedDateTime;
};
</script>

<style>
.scrollbar-flex-content {
  display: flex;
}
.scrollbar-demo-item {
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 200px;
  height: 200px;
  margin: 10px;
  text-align: center;
  border-radius: 4px;
  background: var(--el-color-danger-light-9);
  color: var(--el-color-danger);
}
.name-product-2 {
  font-size: 16px;
}
.card-img-top-2 {
  width: 180px;
}
.breadcrumb-item + .breadcrumb-item::before {
  content: ">";
}

.breadcrumb {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  padding: 0.1rem 0rem !important;
  margin-bottom: 0rem;
  list-style: none;
  background-color: #ffffff;
  border-radius: 0.25rem;
}

.single_product {
  padding-top: 66px;
  padding-bottom: 140px;
  background-color: #e5e5e5;
  margin-top: 0px;
  padding: 4px;
}

.product_name {
  font-size: 24px;
  font-weight: 600;
  margin-top: 0px;
}

/* .badge {
  display: inline-block;
  padding: 0.5em 0.4em;
  font-size: 75%;
  font-weight: 700;
  line-height: 1;
  text-align: center;
  white-space: nowrap;
  vertical-align: baseline;
  border-radius: 0.25rem;
} */

.product-rating {
  margin-top: 10px;
}

.rating-review {
  color: #5b5b5b;
}

.product_price {
  display: inline-block;
  font-size: 30px;
  font-weight: 500;
  margin-top: 9px;
  clear: left;
}

.product_discount {
  display: inline-block;
  font-size: 17px;
  font-weight: 300;
  margin-top: 9px;
  clear: left;
  margin-left: 10px;
  color: red;
}

.product_saved {
  display: inline-block;
  font-size: 15px;
  font-weight: 200;
  color: #999999;
  clear: left;
}

.singleline {
  margin-top: 1rem;
  margin-bottom: 0.4rem;
  border: 0;
  border-top: 1px solid rgba(0, 0, 0, 0.1);
}

.product_info {
  color: #4d4d4d;
  display: inline-block;
}

.product_options {
  margin-bottom: 10px;
}

.product_description {
  padding-left: 0px;
}

.product_quantity {
  width: 104px;
  height: 47px;
  border: solid 1px #e5e5e5;
  border-radius: 3px;
  overflow: hidden;
  padding-left: 8px;
  padding-top: -4px;
  padding-bottom: 44px;
  float: left;
  margin-right: 22px;
  margin-bottom: 11px;
}

.order_info {
  margin-top: 18px;
}

.shop-button {
  height: 47px;
}

.product_fav i {
  line-height: 44px;
  color: #eb1919;
  font-size: 20px;
}

.product_fav {
  display: inline-block;
  width: 52px;
  height: 46px;
  background: #ffffff;
  box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
  border-radius: 11%;
  text-align: center;
  cursor: pointer;
  margin-left: 3px;
  -webkit-transition: all 200ms ease;
  -moz-transition: all 200ms ease;
  -ms-transition: all 200ms ease;
  -o-transition: all 200ms ease;
  transition: all 200ms ease;
}

.br-dashed {
  border-radius: 5px;
  border: 1px dashed #dddddd;
  margin-top: 6px;
}

.pr-info {
  margin-top: 2px;
  padding-left: 2px;
  margin-left: -14px;
  padding-left: 0px;
}

.break-all {
  color: #5e5e5e;
}

.image_selected {
  display: -webkit-box;
  display: -moz-box;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  width: calc(100% + 15px);
  height: 525px;
  -webkit-transform: translateX(-15px);
  -moz-transform: translateX(-15px);
  -ms-transform: translateX(-15px);
  -o-transform: translateX(-15px);
  transform: translateX(-15px);
  border: solid 1px #e8e8e8;
  box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  padding: 15px;
}

.image_list li {
  display: -webkit-box;
  display: -moz-box;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 165px;
  border: solid 1px #e8e8e8;
  box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.1) !important;
  margin-bottom: 15px;
  cursor: pointer;
  padding: 15px;
  -webkit-transition: all 200ms ease;
  -moz-transition: all 200ms ease;
  -ms-transition: all 200ms ease;
  -o-transition: all 200ms ease;
  transition: all 200ms ease;
  overflow: hidden;
}

@media (max-width: 390px) {
  .product_fav {
    display: none;
  }
}

.bbb_combo {
  width: 100%;
  margin-right: 7%;
  padding-top: 21px;
  padding-left: 20px;
  padding-right: 20px;
  padding-bottom: 24px;
  border-radius: 5px;
  margin-top: 0px;
  text-align: -webkit-center;
}

.bbb_combo_image {
  width: 170px;
  height: 170px;
  margin-bottom: 15px;
}

.fs-10 {
  font-size: 10px;
}

.step {
  background: #167af6;
  border-radius: 0.8em;
  -moz-border-radius: 0.8em;
  -webkit-border-radius: 6.8em;
  color: #ffffff;
  display: inline-block;
  font-weight: bold;
  line-height: 3.6em;
  margin-right: 5px;
  text-align: center;
  width: 3.6em;
  margin-top: 116px;
}

.row-underline {
  content: "";
  display: block;
  border-bottom: 2px solid #3798db;
  margin: 0px 0px;
  margin-bottom: 20px;
  margin-top: 15px;
}

.deal-text {
  margin-left: -10px;
  font-size: 25px;
  margin-bottom: 10px;
  color: #000;
  font-weight: 700;
}

.padding-0 {
  padding-left: 0;
  padding-right: 0;
}

.padding-2 {
  margin-right: 2px;
  margin-left: 2px;
}

.vertical-line {
  display: inline-block;
  border-left: 3px solid #167af6;
  margin: 0 10px;
  height: 364px;
  margin-top: 4px;
}

.p-rating {
  color: green;
}

.combo-pricing-item {
  display: flex;
  flex-direction: column;
}

.boxo-pricing-items {
  display: inline-flex;
}

.combo-plus {
  margin-left: 10px;
  margin-right: 18px;
  margin-top: 10px;
}

.add-both-cart-button {
  margin-left: 36px;
}

.items_text {
  color: #b0b0b0;
}

.combo_item_price {
  font-size: 18px;
}

.p_specification {
  font-weight: 500;
  margin-left: 22px;
}

.mt-10 {
  margin-top: 10px;
}

* {
  margin: 0;
  padding: 0;
  -webkit-font-smoothing: antialiased;
  -webkit-text-shadow: rgba(0, 0, 0, 0.01) 0 0 1px;
  text-shadow: rgba(0, 0, 0, 0.01) 0 0 1px;
}

body {
  font-family: "Rubik", sans-serif;
  font-size: 14px;
  font-weight: 400;
  background: #ffffff;
  color: #000000;
}

div {
  display: block;
  position: relative;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}

ul {
  list-style: none;
  margin-bottom: 0px;
}

.single_product {
  padding-bottom: 4px;
}

.image_list li {
  display: -webkit-box;
  display: -moz-box;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 165px;
  border: solid 1px #e8e8e8;
  box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
  margin-bottom: 15px;
  cursor: pointer;
  padding: 15px;
  -webkit-transition: all 200ms ease;
  -moz-transition: all 200ms ease;
  -ms-transition: all 200ms ease;
  -o-transition: all 200ms ease;
  transition: all 200ms ease;
  overflow: hidden;
}

.image_list li:last-child {
  margin-bottom: 0;
}

.image_list li:hover {
  box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.3);
}

.image_list li img {
  max-width: 100%;
}

.image_selected {
  display: -webkit-box;
  display: -moz-box;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  width: calc(100% + 15px);
  height: 525px;
  -webkit-transform: translateX(-15px);
  -moz-transform: translateX(-15px);
  -ms-transform: translateX(-15px);
  -o-transform: translateX(-15px);
  transform: translateX(-15px);
  border: solid 1px #e8e8e8;
  box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  padding: 15px;
}

.image_selected img {
  max-width: 100%;
}

.product_category {
  font-size: 12px;
  color: rgba(0, 0, 0, 0.5);
}

.product_rating {
  margin-top: 7px;
}

.product_rating i {
  margin-right: 4px;
}

.product_rating i::before {
  font-size: 13px;
}

.product_text {
  margin-top: 27px;
}

.product_text p:last-child {
  margin-bottom: 0px;
}

.order_info {
  margin-top: 16px;
}

.product_quantity {
  width: 182px;
  height: 50px;
  border: solid 1px #e5e5e5;
  border-radius: 5px;
  overflow: hidden;
  padding-left: 25px;
  float: left;
  margin-right: 30px;
}

.product_quantity span {
  display: block;
  height: 50px;
  font-size: 16px;
  font-weight: 300;
  color: rgba(0, 0, 0, 0.5);
  line-height: 50px;
  float: left;
}

.product_quantity input {
  display: block;
  width: 30px;
  height: 50px;
  border: none;
  outline: none;
  font-size: 16px;
  font-weight: 300;
  color: rgba(0, 0, 0, 0.5);
  text-align: left;
  padding-left: 9px;
  line-height: 50px;
  float: left;
}

.quantity_buttons {
  position: absolute;
  top: 0;
  right: 0;
  height: 100%;
  width: 29px;
  border-left: solid 1px #e5e5e5;
}

.quantity_inc,
.quantity_dec {
  display: -webkit-box;
  display: -moz-box;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
  height: 50%;
  cursor: pointer;
}

.quantity_control i {
  font-size: 11px;
  color: rgba(0, 0, 0, 0.3);
  pointer-events: none;
}

.quantity_control:active {
  border: solid 1px rgba(14, 140, 228, 0.2);
}

.quantity_inc {
  padding-bottom: 2px;
  justify-content: flex-end;
  border-top-right-radius: 5px;
}

.quantity_dec {
  padding-top: 2px;
  justify-content: flex-start;
  border-bottom-right-radius: 5px;
}
/* Element */

/* Comment design */
.widget .panel-body {
  padding: 0px;
}
.widget .list-group {
  margin-bottom: 0;
}
.widget .panel-title {
  display: inline;
}
.widget .label-info {
  float: right;
}
.widget li.list-group-item {
  border-radius: 0;
  border: 0;
  border-top: 1px solid #ddd;
}
.widget li.list-group-item:hover {
  background-color: rgba(86, 61, 124, 0.1);
}
.widget .mic-info {
  color: #666666;
  font-size: 11px;
}
.widget .action {
  margin-top: 5px;
}
.widget .comment-text {
  font-size: 12px;
}
.widget .btn-block {
  border-top-left-radius: 0px;
  border-top-right-radius: 0px;
}
.img-user {
  width: 80px;
  height: auto;
}
</style>
