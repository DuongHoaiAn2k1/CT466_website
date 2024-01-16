<template>
  <div class="container">
    <div class="row">
      <div class="col-lg-10 col-xl-9 mx-auto">
        <div
          class="card flex-row my-2 border-0 shadow rounded-3 overflow-hidden"
        >
          <div class="card-img-left d-none d-md-flex">
            <!-- Background image for card set in CSS! -->
          </div>
          <div class="card-body p-4 p-sm-5">
            <h2 class="card-title text-center mb-5 fw-light">Đăng nhập</h2>
            <form @submit="submitLogin">
              <div class="form-floating mb-3">
                <input
                  type="email"
                  class="form-control"
                  id="floatingInputEmail"
                  placeholder="name@example.com"
                  v-model="dataLogin.email"
                  name="email"
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
                  v-model="dataLogin.password"
                  name="password"
                />
                <label for="floatingPassword">Mật khẩu</label>
                <span class="text-danger">{{ passwordErrors }}</span>
              </div>

              <div class="d-grid mb-2">
                <button
                  class="btn btn-lg btn-primary btn-login fw-bold text-uppercase"
                  type="submit"
                >
                  Đăng nhập
                </button>
              </div>

              <a class="d-block text-center mt-2 small" href="#"
                >Quên mật khẩu?</a
              >

              <hr class="my-4" />

              <div class="d-grid mb-2">
                <button
                  class="btn btn-lg btn-google btn-login fw-bold text-uppercase"
                  @click="notificationLogin"
                >
                  <i class="fab fa-google me-2"></i> Đăng nhập với Google
                </button>
              </div>

              <div class="d-grid">
                <button
                  class="btn btn-lg btn-facebook btn-login fw-bold text-uppercase"
                  @click="notificationLogin"
                >
                  <i class="fab fa-facebook-f me-2"></i> Đăng nhập với Facebook
                </button>
              </div>
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
import { ElLoading, ElNotification } from "element-plus";
import userService from "@/services/user.service";
import Cookies from "js-cookie";
import { useRouter } from "vue-router";
import { ElMessage } from "element-plus";
import { h } from "vue";

const router = useRouter();
const schema = Yup.object().shape({
  email: Yup.string()
    .email("Email phải đúng định dạng")
    .required("Email không được để trống"),
  password: Yup.string()
    .matches(/[a-zA-Z]/, "Mật khẩu phải có ít nhất một chữ cái")
    .min(8, "Mật khẩu phải có ít nhất 8 ký tự")
    .max(20, "Mật khẩu chỉ tối đa 20 ký tự")
    .required("Mật khẩu không được để trống"),
});

const emailErrors = ref(null);
const passwordErrors = ref(null);

const dataLogin = reactive({
  email: "",
  password: "",
});

const submitLogin = async (event) => {
  event.preventDefault();

  emailErrors.value = null;
  passwordErrors.value = null;
  schema
    .validate(dataLogin, { abortEarly: false })
    .then(() => {
      emailErrors.value = null;
      passwordErrors.value = null;

      const loading = ElLoading.service({
        lock: true,
        text: "Đang xử lý...",
        background: "rgba(0,0,0, 0.7)",
      });

      console.log(dataLogin);
      const login = async (dataLogin) => {
        try {
          const response = await userService.login(dataLogin);
          console.log(response);
          const token = response.token;
          Cookies.set("token", token, { expires: 1 });
          showLoginSuccess();
          setTimeout(() => {
            router.push({ name: "home" });
          }, 1000);
        } catch (error) {
          console.log(error);
        }
      };

      setTimeout(() => {
        login(dataLogin);
        //
        loading.close();
      }, 2000);
    })
    .catch((errors) => {
      errors.inner.forEach((error) => {
        if (error.path == "email") {
          emailErrors.value = error.message;
        }

        if (error.path == "password") {
          passwordErrors.value = error.message;
        }
      });
    });
};

const showLoginSuccess = () => {
  ElMessage({
    message: "Đăng nhập thành công.",
    type: "success",
  });
};

const notificationLogin = () => {
  ElNotification({
    title: "Thông báo",
    message: h(
      "i",
      { style: "color: teal" },
      "Chức năng hiện tại đang trong quá trình xây dựng"
    ),
  });
};
</script>

<style scoped>
body {
  background: #007bff;
  background: linear-gradient(to right, #0062e6, #33aeff);
}

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
