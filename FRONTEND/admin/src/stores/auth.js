import { defineStore } from "pinia";

export const useAuthStore = defineStore({
  id: "auth",
  state: () => ({
    isAdminLoggedIn: JSON.parse(
      localStorage.getItem("isAdminLoggedIn") || false
    ),
    accessToken: localStorage.getItem("access_token") || null,
    refresh_token: localStorage.getItem("refresh_token") || null,
    admin_id: localStorage.getItem("admin_id"),
  }),
  actions: {
    login(accessToken, refresh_token, admin_id) {
      this.isAdminLoggedIn = true;
      this.accessToken = accessToken;
      this.refresh_token = refresh_token;
      this.admin_id = admin_id;
      localStorage.setItem("admin_id", admin_id);
      localStorage.setItem("isAdminLoggedIn", true);
      localStorage.setItem("access_token", accessToken);
      localStorage.setItem("refresh_token", refresh_token);
    },

    logout() {
      this.isAdminLoggedIn = false;
      this.accessToken = null;
      this.refresh_token = null;
      localStorage.setItem("isAdminLoggedIn", false);
      localStorage.removeItem("access_token");
      localStorage.removeItem("refresh_token");
      localStorage.removeItem("admin_id");
    },
  },
});
