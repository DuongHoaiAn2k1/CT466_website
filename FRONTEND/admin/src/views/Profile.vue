<template>
  <div id="layoutSidenav_content">
    <div class="container mt-2">
      <div class="row gutters">
        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
          <div class="card h-100">
            <div class="card-body">
              <div class="account-settings">
                <div class="user-profile">
                  <div class="user-avatar">
                    <img
                      src="https://bootdey.com/img/Content/avatar/avatar7.png"
                      alt="Maxwell Admin"
                    />
                  </div>
                  <h5 class="user-name">{{ dataAdmin.name }}</h5>
                  <h6 class="user-email">Quản trị viên</h6>
                </div>
                <div class="about">
                  <h5>Mô tả</h5>
                  <p>Tôi là một nhà quản lý vui vẻ và hài hước.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
          <div class="card h-100">
            <div class="card-body">
              <div class="row gutters">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                  <h6 class="mb-2 text-primary">
                    Thông Tin Tài Khoản Quản Trị
                  </h6>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                  <div class="form-group">
                    <label for="fullName" class="form-label">Họ và tên</label>
                    <input
                      type="text"
                      class="form-control"
                      id="fullName"
                      placeholder="Enter full name"
                      :value="dataAdmin.name"
                    />
                  </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                  <div class="form-group">
                    <label for="eMail" class="form-label">Email</label>
                    <input
                      type="email"
                      class="form-control"
                      id="eMail"
                      placeholder="Enter email ID"
                      :value="dataAdmin.email"
                    />
                  </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                  <div class="form-group">
                    <label for="phone" class="form-label">Phone</label>
                    <input
                      type="text"
                      class="form-control"
                      id="phone"
                      placeholder="Enter phone number"
                      :value="dataAdmin.name"
                    />
                  </div>
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
import userService from "@/services/user.service";
import adminService from "@/services/admin.service";
import { useAuthStore } from "@/stores/auth";
import { onMounted, reactive, ref } from "vue";

const authStore = useAuthStore();
const dataAdmin = ref({});
const fetchDataAdmin = async () => {
  try {
    const userId = authStore.user_id;
    const response = await adminService.get(userId);
    dataAdmin.value = response.data;
    console.log("data Admin: ", dataAdmin);
  } catch (error) {
    console.log(error.response);
  }
};

onMounted(() => {
  fetchDataAdmin();
});
</script>

<style>
body {
  margin: 0;
  padding-top: 40px;
  color: #2e323c;
  background: #f5f6fa;
  position: relative;
  height: 100%;
}
.account-settings .user-profile {
  margin: 0 0 1rem 0;
  padding-bottom: 1rem;
  text-align: center;
}
.account-settings .user-profile .user-avatar {
  margin: 0 0 1rem 0;
}
.account-settings .user-profile .user-avatar img {
  width: 90px;
  height: 90px;
  -webkit-border-radius: 100px;
  -moz-border-radius: 100px;
  border-radius: 100px;
}
.account-settings .user-profile h5.user-name {
  margin: 0 0 0.5rem 0;
}
.account-settings .user-profile h6.user-email {
  margin: 0;
  font-size: 0.8rem;
  font-weight: 400;
  color: #9fa8b9;
}
.account-settings .about {
  margin: 2rem 0 0 0;
  text-align: center;
}
.account-settings .about h5 {
  margin: 0 0 15px 0;
  color: #007ae1;
}
.account-settings .about p {
  font-size: 0.825rem;
}
.form-control {
  border: 1px solid #cfd1d8;
  -webkit-border-radius: 2px;
  -moz-border-radius: 2px;
  border-radius: 2px;
  font-size: 0.825rem;
  background: #ffffff;
  color: #2e323c;
}

.card {
  background: #ffffff;
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
  border-radius: 5px;
  border: 0;
  margin-bottom: 1rem;
}
</style>
