<template>
  <div id="layoutSidenav_content">
    <div class="container contain-form mt-2">
      <div><h1>ĐĂNG NHẬP QUẢN TRỊ</h1></div>
      <form class="mt-3" @submit="handleSubmit">
        <!-- Email input -->
        <div class="form-outline mb-4">
          <label class="form-label" for="form2Example1">Tên đăng nhập</label>
          <input
            type="text"
            id="form2Example1"
            class="form-control"
            name="username"
            v-model="dataLogin.username"
          />
          <span class="text-danger">{{ userNameErrors }}</span>
        </div>

        <!-- Password input -->
        <div class="form-outline mb-4">
          <label class="form-label" for="form2Example2">Mật khẩu</label>
          <input
            type="password"
            id="form2Example2"
            class="form-control"
            name="password"
            v-model="dataLogin.password"
          />
          <span class="text-danger">{{ passwordErrors }}</span>
        </div>

        <!-- Submit button -->
        <button type="submit" class="btn btn-dark btn-block mb-4">
          Đăng nhập
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from "vue";
import * as Yup from "yup";
import { ElLoading, ElNotification } from "element-plus";
import { ElMessage } from "element-plus";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import userService from "@/services/user.service";
import authService from "@/services/auth.service";
const router = useRouter();
const authStore = useAuthStore();
const dataLogin = reactive({
  username: "",
  password: "",
});

const schema = Yup.object().shape({
  username: Yup.string().required("Tên đăng nhập không được để trống"),
  password: Yup.string()
    .matches(/[a-zA-Z]/, "Mật khẩu phải có ít nhất một chữ cái")
    .min(8, "Mật khẩu phải có ít nhất 8 ký tự")
    .max(20, "Mật khẩu chỉ tối đa 20 ký tự")
    .required("Mật khẩu không được để trống"),
});

const showLoginSuccess = () => {
  ElMessage({
    message: "Đăng nhập thành công.",
    type: "success",
  });
};

const handleLogin = async (data) => {
  try {
    const response = await authService.login(data);
    showLoginSuccess();
    let access_token = response.access_token;
    let refresh_token = response.refresh_token;
    let user_id = response.user_id;
    authStore.login(access_token, refresh_token, user_id);
    setTimeout(() => {
      router.push({ name: "home" });
    });
    console.log("hi", response);
  } catch (error) {
    console.log(error.response);
  }
};

const userNameErrors = ref(null);
const passwordErrors = ref(null);

const handleSubmit = async (event) => {
  event.preventDefault();

  userNameErrors.value = null;
  passwordErrors.value = null;

  schema
    .validate(dataLogin, { abortEarly: false })
    .then(() => {
      userNameErrors.value = null;
      passwordErrors.value = null;
      const loading = ElLoading.service({
        lock: true,
        text: "Đang xử lý...",
        background: "rgba(0,0,0, 0.7)",
      });

      setTimeout(() => {
        handleLogin(dataLogin);

        //
        loading.close();
      }, 2000);
    })
    .catch((errors) => {
      errors.inner.forEach((error) => {
        if (error.path == "username") {
          userNameErrors.value = error.message;
        }
        if (error.path == "password") {
          passwordErrors.value = error.message;
        }
      });
    });
};
</script>

<style>
h1 {
  font-weight: 600;
}
.contain-form {
  width: 500px;
}
</style>
