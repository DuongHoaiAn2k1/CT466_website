import axios from "axios";
import { useAuthStore } from "@/stores/auth";
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
      const access_token = localStorage.getItem("accessToken");
      console.log("My access_token: ", access_token);
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
        error.response.data.error === "Access Token has expired"
      ) {
        //
        // alert("Access Token has expired");
        window.location.href = "http://localhost:3002/tokenProcess";
      } else if (
        error.request &&
        error.response.status === 401 &&
        error.response.data.message === "Refresh Token has expired"
      ) {
        //
        // alert("Refresh Token has expired");
        showInfo();
        setTimeout(() => {
          const authStore = useAuthStore();
          authStore.logout();
          window.location.href = "http://localhost:3002/login";
        }, 2000);
      } else if (
        error.request &&
        error.response.status === 500 &&
        error.response.data.message === "Attempt to read property 'id' on null"
      ) {
        window.location.href = "http://localhost:3002/tokenProcess";
      }
      return Promise.reject(error);
    }
  );
  return instance;
};
