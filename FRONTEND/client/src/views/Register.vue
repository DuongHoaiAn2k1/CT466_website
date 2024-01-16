<template>
  <div class="container">
    <div class="row">
      <div class="col-lg-10 col-xl-9 mx-auto">
        <div
          class="card flex-row mb-2 border-0 shadow rounded-3 overflow-hidden"
        >
          <div class="card-img-left d-none d-md-flex">
            <!-- Background image for card set in CSS! -->
          </div>
          <div class="card-body p-4 p-sm-5">
            <h2 class="card-title text-center mb-5 fw-light">Đăng ký</h2>
            <form @submit="submitSignUp">
              <div class="form-floating mb-3">
                <input
                  type="text"
                  class="form-control"
                  id="floatingInputUsername"
                  placeholder="myusername"
                  v-model="dataSignUp.name"
                />
                <label class="" for="floatingInputUsername">Họ và tên</label>
                <span class="text-danger">{{ nameErrors }}</span>
              </div>

              <div class="form-floating mb-3">
                <input
                  type="text"
                  class="form-control"
                  id="floatingInputPhone"
                  placeholder="myusername"
                  v-model="dataSignUp.phone"
                />
                <label for="floatingInputPhone">Số điện thoại</label>
                <span class="text-danger">{{ phoneErrors }}</span>
              </div>

              <div class="form-floating mb-3">
                <input
                  type="email"
                  class="form-control"
                  id="floatingInputEmail"
                  placeholder="name@example.com"
                  v-model="dataSignUp.email"
                />
                <label for="floatingInputEmail">Email</label>
                <span class="text-danger">{{ emailErrors }}</span>
              </div>

              <div class="form-floating mb-3">
                <input
                  type="password"
                  class="form-control"
                  id="floatingPassword"
                  placeholder="Password"
                  v-model="dataSignUp.password"
                />
                <label for="floatingPassword">Mật khẩu</label>
                <span class="text-danger">{{ passwordErrors }}</span>
              </div>

              <div class="form-floating mb-3">
                <input
                  type="password"
                  class="form-control"
                  id="floatingPasswordConfirm"
                  placeholder="Confirm Password"
                  v-model="dataSignUp.co_password"
                />
                <label for="floatingPasswordConfirm">Nhập lại mật khẩu</label>
                <span class="text-danger">{{ copasswordErrors }}</span>
              </div>
              <div class="form-floating mb-3 row" v-show="otpDisplay">
                <span class="col-3" style="margin-top: 18px; font-size: 20px"
                  >OTP:
                </span>
                <input
                  type="text"
                  class="col"
                  id="otpInput"
                  name="otp"
                  v-model="dataSignUp.otp"
                  placeholder="Vui lòng nhập mã OTP"
                  maxlength="6"
                />
                <span
                  v-show="timeOver > 0"
                  class="col-2"
                  style="margin-top: 18px"
                  >{{ timeOver }}s</span
                >
                <button
                  v-show="timeOver == 0"
                  type="submit"
                  class="col-2"
                  style="
                    margin-top: 18px;
                    margin-right: 12px;
                    border: none;
                    font-size: 16px;
                    background-color: #fff;
                    padding: 0;
                    color: blue;
                    text-decoration: underline;
                  "
                >
                  Gửi lại
                </button>
                <!-- <label for="floatingPasswordConfirm">Nhập lại mật khẩu</label> -->
                <!-- <span class="text-danger">{{ copasswordErrors }}</span> -->
              </div>
              <!-- <el-button :plain="true" @click="open4">error</el-button> -->
              <div class="d-grid mb-2">
                <button
                  class="btn btn-lg btn-primary btn-login fw-bold text-uppercase"
                  type="submit"
                >
                  Đăng ký
                </button>
                <!-- <el-button type="submit"> Đăng ký </el-button> -->
              </div>

              <router-link
                :to="{ name: 'login' }"
                class="d-block text-center mt-2 small"
                href="#"
                >Bạn đã có tài khoản? Đăng nhập</router-link
              >
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import * as Yup from "yup";
import { ref, reactive } from "vue";
import { ElMessage } from "element-plus";
import userService from "@/services/user.service";
import { ElLoading } from "element-plus";
import { useRouter } from "vue-router";

const router = useRouter();
const otpDisplay = ref(false);
const timeOver = ref(60);
const startCountdown = () => {
  const countdownInterval = setInterval(() => {
    if (timeOver.value > 0) {
      timeOver.value--;
    } else {
      // Khi countdown kết thúc, bạn có thể thực hiện các hành động khác ở đây
      // Ví dụ: Ẩn hoặc xóa input OTP, gửi request xác minh OTP, vv.

      // Dừng interval khi countdown kết thúc
      // timeOver.value = 60;
      clearInterval(countdownInterval);
    }
  }, 1000);
};
const schema = Yup.object().shape({
  name: Yup.string().required("Họ và tên không được để trống"),
  phone: Yup.string()
    .min(10, "Số điện thoại phải có 10 chữ số")
    .max(10, "Số điện thoại phải có 10 chữ số")
    .required("Số điện thoại không được để trống"),
  email: Yup.string()
    .email("Email phải đúng định dạng")
    .required("Email không được để trống"),
  password: Yup.string()
    .matches(/[a-zA-Z]/, "Mật khẩu phải có ít nhất một chữ cái")
    .min(8, "Mật khẩu phải có ít nhất 8 ký tự")
    .max(20, "Mật khẩu chỉ tối đa 20 ký tự")
    .required("Mật khẩu không được để trống"),
  co_password: Yup.string()
    .oneOf([Yup.ref("password")], "Mật khẩu nhập lại chưa khớp")
    .required("Mật khẩu nhập lại không được để trống"),
});
const nameErrors = ref(null);
const emailErrors = ref(null);
const passwordErrors = ref(null);
const copasswordErrors = ref(null);
const phoneErrors = ref(null);
const dataSignUp = reactive({
  name: "",
  phone: "",
  email: "",
  password: "",
  co_password: "",
  otp: "",
});
const submitSignUp = async (event) => {
  event.preventDefault();
  nameErrors.value = null;
  phoneErrors.value = null;
  emailErrors.value = null;
  passwordErrors.value = null;
  copasswordErrors.value = null;
  schema
    .validate(dataSignUp, { abortEarly: false })
    .then(() => {
      nameErrors.value = null;
      emailErrors.value = null;
      passwordErrors.value = null;
      copasswordErrors.value = null;
      phoneErrors.value = null;
      // delete dataSignUp.co_password;
      const loading = ElLoading.service({
        lock: true,
        text: "Đang xử lý...",
        background: "rgba(0, 0, 0, 0.7)",
      });
      // Xử lý khi xác thực thành công, bạn có thể gửi dữ liệu lên máy chủ ở đây
      console.log(dataSignUp);
      const register = async (dataSignUp) => {
        try {
          // otpDisplay.value = true;

          const { co_password, ...data } = { ...dataSignUp };
          const response = await userService.register(data);
          delete dataSignUp.otp;
          if (response.status == "pending") {
            timeOver.value = 60;
            otpDisplay.value = true;
            startCountdown();
          } else if (response.status == "ErrorOTP") {
            showErrorOTP();
          } else {
            showSuccess();
            setTimeout(() => {
              router.push({ name: "login" });
            }, 1000);
            console.log("THANH CONG");
          }
          console.log(response);
        } catch (error) {
          console.log("Đăng ký thất bại!!!");
          // console.log(error.response.data.message);
          if (error.response.data.message == "Email đã tồn tại") {
            showEmailExists();
          }
        }
      };

      setTimeout(() => {
        register(dataSignUp);

        //
        loading.close();
      }, 2000);
    })
    .catch((errors) => {
      errors.inner.forEach((error) => {
        if (error.path === "name") {
          nameErrors.value = error.message;
        }
        if (error.path === "email") {
          emailErrors.value = error.message;
        }
        if (error.path === "password") {
          passwordErrors.value = error.message;
        }
        if (error.path === "co_password") {
          copasswordErrors.value = error.message;
        }
        if (error.path === "phone") {
          phoneErrors.value = error.message;
        }
      });
    });
};

const showEmailExists = () => {
  ElMessage({
    showClose: true,
    message: "Email đã tồn tại.",
    type: "error",
  });
};
const showErrorOTP = () => {
  ElMessage({
    showClose: true,
    message: "Mã OTP không chính xác.",
    type: "error",
  });
};

const showSuccess = () => {
  ElMessage({
    showClose: true,
    message: "Đăng ký thành công.",
    type: "success",
  });
};
</script>

<style>
.card-img-left {
  width: 45%;
  /* Link to your background image using in the property below! */
  background: scroll center
    /* url("https://source.unsplash.com/WEQbe2jBg40/414x512"); */
    url("../assets/camau.jpg");
  background-size: cover;
}

.btn-login {
  font-size: 0.9rem;
  letter-spacing: 0.05rem;
  padding: 0.75rem 1rem;
}

.btn-google {
  color: white !important;
  background-color: #ea4335;
}

.btn-facebook {
  color: white !important;
  background-color: #3b5998;
}
</style>
