import axios from "axios";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";
import { ElNotification } from "element-plus";
const showInfo = () => {
  ElNotification({
    title: "Info",
    message: "Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.",
    type: "info",
  });
};
const commonConfig = {
  headers: {
    Accept: "application/json",
  },
};
export default (baseURL) => {
  const instance = axios.create({
    baseURL,
    ...commonConfig,
  });

  instance.interceptors.request.use(
    (config) => {
      const authStore = useAuthStore();
      const access_token = authStore.accessTokenUser;
      // console.log("My access_token: ", access_token);
      if (access_token) {
        config.headers.Authorization = `Bearer ${access_token}`;
      }

      return config;
    },
    (error) => {
      return Promise.reject(error);
    }
  );

  instance.interceptors.response.use(
    (response) => {
      return response;
    },
    async (error) => {
      if (
        error.request &&
        error.response.status == 500 &&
        error.response.data.message == "Token has expired"
      ) {
        const authStore = useAuthStore();
        const refresh_token = authStore.refreshTokenUser;
        if (refresh_token) {
          // Gán refresh token vào header của request
          const newConfig = error.config;
          newConfig.headers.Authorization = `Bearer ${refresh_token}`;
          try {
            // Thực hiện lại request với refresh token mới
            const response = await axios(newConfig);
            return response;
          } catch (error) {
            // Xử lý lỗi khi request với refresh token mới
            return Promise.reject(error);
          }
        } else {
          // Nếu không có refresh token, chuyển hướng người dùng đến trang đăng nhập
          window.location.href = "http://localhost:3001/login";
        }
      }
      if (
        error.request &&
        error.response.status == 401 &&
        error.response.data.error == "Access Token has expired"
        // error.response.statusText === "Unauthorized"
      ) {
        window.location.href = "http://localhost:3001/tokenProcess";
      }

      if (
        error.request &&
        error.response.status == 401 &&
        error.response.statusText == "Unauthorized"
      ) {
        window.location.href = "http://localhost:3001/tokenProcess";
      }
      // if (
      //   error.request &&
      //   error.response.status === 401 &&
      //   error.response.data.message === "Refresh Token has expired"
      // ) {
      //   // alert("Refresh Token has expired");
      //   showInfo();
      //   setTimeout(() => {
      //     const authStore = useAuthStore();
      //     authStore.logout();
      //     window.location.href = "http://localhost:3001/login";
      //   }, 2000);
      // }
      if (
        error.request &&
        error.response.status == 500 &&
        error.response.data.message == "Attempt to read property 'id' on null"
        // error.response.data.message === "Attempt to read property 'id' on null"
      ) {
        // alert("Refresh Token has expired");
        window.location.href = "http://localhost:3001/tokenProcess";
      }
      return Promise.reject(error);
    }
  );

  return instance;
};
// Attempt to read property "id" on null
