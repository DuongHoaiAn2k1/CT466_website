import { defineStore } from "pinia";

export const useAuthStore = defineStore("userLogin", {
  state: () => ({
    isUserLoggedIn: JSON.parse(
      localStorage.getItem("isUserLoggedIn") || "false"
    ),
    accessTokenUser: localStorage.getItem("accessTokenUser") || null,
    refreshTokenUser: localStorage.getItem("refreshTokenUser") || null,
    user_id: localStorage.getItem("user_id"),
  }),

  actions: {
    login(access_token, refresh_token, user_id) {
      this.isUserLoggedIn = true;
      this.accessTokenUser = access_token;
      this.refreshTokenUser = refresh_token;
      this.user_id = user_id;
      localStorage.setItem("isUserLoggedIn", true);
      localStorage.setItem("accessTokenUser", access_token);
      localStorage.setItem("refreshTokenUser", refresh_token);
      localStorage.setItem("user_id", user_id);
    },

    logout() {
      this.isUserLoggedIn = false;
      this.accessTokenUser = null;
      this.refreshTokenUser = null;
      this.user_id = null;
      localStorage.setItem("isUserLoggedIn", "");
      localStorage.removeItem("accessTokenUser");
      // localStorage.removeItem("refreshTokenUser");
      localStorage.removeItem("user_id");
    },
  },
});
