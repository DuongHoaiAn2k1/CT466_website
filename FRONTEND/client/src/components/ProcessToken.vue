<template></template>

<script setup>
import authService from "@/services/auth.service";
import { useAuthStore } from "@/stores/auth";
import { onMounted } from "vue";
import { ElLoading, ElNotification } from "element-plus";
import { useRouter } from "vue-router";

const router = useRouter();
const authStore = useAuthStore();
const handlRefresh = async () => {
  try {
    const authStore = useAuthStore();
    const refreshToken = authStore.refreshTokenUser;
    const response = await authService.refresh({
      refresh_Token: refreshToken,
    });

    authStore.login(
      response.access_token,
      response.refresh_token,
      response.user_id
    );
    console.log("After refresh Token: ", response);
    router.push({ name: "home" });
  } catch (error) {
    console.log(error.response);
  }
};

onMounted(() => {
  const loading = ElLoading.service({
    lock: true,
    text: "Đang đăng nhập lại...",
    background: "rgba(0,0,0, 0.7)",
  });
  setTimeout(() => {
    handlRefresh();
    loading.close();
  }, 2000);
});
</script>
