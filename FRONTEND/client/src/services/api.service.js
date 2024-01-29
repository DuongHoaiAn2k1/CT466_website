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
    (error) => {
      if (
        error.request &&
        error.response.status === 401 &&
        error.response.statusText === "Unauthorized"
      ) {
        window.location.href = "http://localhost:3001/tokenProcess";

        // alert("Cho Hao");
      } else if (
        error.request &&
        error.response.status === 500 &&
        error.response.data.message === "Token has expired"
      ) {
        showInfo();
        setTimeout(() => {
          const authStore = useAuthStore();
          authStore.logout();
          window.location.href = "http://localhost:3001/login";
        }, 2000);
      }
      return Promise.reject(error);
    }
  );

  return instance;
};
