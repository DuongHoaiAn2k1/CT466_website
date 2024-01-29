<template>
  <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="index.html">Trang Quản Trị</a>
    <!-- Sidebar Toggle-->
    <button
      class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0"
      id="sidebarToggle"
      href="#!"
    >
      <i class="fas fa-bars"></i>
    </button>
    <!-- Navbar Search-->
    <form
      class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"
    >
      <div class="input-group">
        <!-- <input
          class="form-control"
          type="text"
          placeholder="Nhập từ khóa để tìm kiếm..."
          aria-label="Nhập từ khóa để tìm kiếm..."
          aria-describedby="btnNavbarSearch"
        />
        <button class="btn btn-primary" id="btnNavbarSearch" type="button">
          <i class="fas fa-search"></i>
        </button> -->
      </div>
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
      <li class="nav-item dropdown">
        <a
          class="nav-link dropdown-toggle"
          id="navbarDropdown"
          href="#"
          role="button"
          data-bs-toggle="dropdown"
          aria-expanded="false"
          ><i class="fas fa-user fa-fw"></i
        ></a>
        <ul
          class="dropdown-menu dropdown-menu-end"
          aria-labelledby="navbarDropdown"
        >
          <li>
            <router-link
              :to="{ name: 'profile' }"
              class="dropdown-item"
              href="#!"
              >Tài khoản</router-link
            >
          </li>
          <li>
            <a class="dropdown-item" @click="handleLogout" href="#!"
              >Đăng Xuất</a
            >
          </li>
        </ul>
      </li>
    </ul>
  </nav>
</template>

<script setup>
import authService from "@/services/auth.service";
import { useAuthStore } from "@/stores/auth";
import { ElMessage } from "element-plus";
import { useRouter } from "vue-router";
const router = useRouter();
const authStore = useAuthStore();
const handleLogout = async () => {
  try {
    // const response = await authService.logout();
    // console.log("Response after logout: ", response);
    authStore.logout();
    showLogoutSuccess();
    setTimeout(() => {
      router.push({ name: "login" });
    }, 500);
  } catch (error) {
    console.log(error.response);
  }
};

const showLogoutSuccess = () => {
  ElMessage({
    message: "Đăng xuất thành công.",
    type: "success",
  });
};
</script>
