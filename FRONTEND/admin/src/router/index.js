import { useAuthStore } from "@/stores/auth";
import { createWebHistory, createRouter } from "vue-router";
import { ElNotification } from "element-plus";
const routes = [
  {
    path: "/:pathMatch(.*)*",
    name: "notfound",
    component: () => import("@/views/404.vue"),
  },
  {
    path: "/login",
    name: "login",
    component: () => import("@/views/Login.vue"),
    // beforeEnter: (to, from, next) => {},
    beforeEnter: (to, from, next) => {
      const authStore = useAuthStore();
      if (authStore.isAdminLoggedIn) {
        next("/home");
      } else {
        next();
      }
    },
  },
  {
    path: "/",
    name: "home",
    component: () => import("@/views/Home.vue"),
    beforeEnter: (to, from, next) => {
      const authStore = useAuthStore();
      if (authStore.isAdminLoggedIn) {
        next();
      } else {
        next({ name: "login" });
        showWarning();
      }
    },
  },
  {
    path: "/product",
    name: "product",
    component: () => import("@/views/Products.vue"),
    beforeEnter: (to, from, next) => {
      const authStore = useAuthStore();
      if (authStore.isAdminLoggedIn) {
        next();
      } else {
        next({ name: "login" });
        showWarning();
      }
    },
  },
  {
    path: "/product/create",
    name: "create-product",
    component: () => import("@/views/CreateProduct.vue"),
    beforeEnter: (to, from, next) => {
      const authStore = useAuthStore();
      if (authStore.isAdminLoggedIn) {
        next();
      } else {
        next({ name: "login" });
        showWarning();
      }
    },
  },

  {
    path: "/product/:id",
    name: "update-product",
    component: () => import("@/views/UpdateProduct.vue"),
    beforeEnter: (to, from, next) => {
      const authStore = useAuthStore();
      if (authStore.isAdminLoggedIn) {
        next();
      } else {
        next({ name: "login" });
        showWarning();
      }
    },
  },

  {
    path: "/proflie",
    name: "profile",
    component: () => import("@/views/Profile.vue"),
    beforeEnter: (to, from, next) => {
      const authStore = useAuthStore();
      if (authStore.isAdminLoggedIn) {
        next();
      } else {
        next({ name: "login" });
        showWarning();
      }
    },
  },

  {
    path: "/customer",
    name: "customer",
    component: () => import("@/views/Customers.vue"),
    beforeEnter: (to, from, next) => {
      const authStore = useAuthStore();
      if (authStore.isAdminLoggedIn) {
        next();
      } else {
        next({ name: "login" });
        showWarning();
      }
    },
  },
  {
    path: "/order",
    name: "order",
    component: () => import("@/views/Orders.vue"),
    beforeEnter: (to, from, next) => {
      const authStore = useAuthStore();
      if (authStore.isAdminLoggedIn) {
        next();
      } else {
        next({ name: "login" });
        showWarning();
      }
    },
  },

  {
    path: "/order-detail/:id",
    name: "order-detail",
    component: () => import("@/views/OrderDetail.vue"),
    beforeEnter: (to, from, next) => {
      const authStore = useAuthStore();
      if (authStore.isAdminLoggedIn) {
        next();
      } else {
        next({ name: "login" });
        showWarning();
      }
    },
  },
  {
    path: "/statistics",
    name: "statistics",
    component: () => import("@/views/Statistics.vue"),
    beforeEnter: (to, from, next) => {
      const authStore = useAuthStore();
      if (authStore.isAdminLoggedIn) {
        next();
      } else {
        next({ name: "login" });
        showWarning();
      }
    },
  },
  {
    path: "/tokenProcess",
    name: "token",
    component: () => import("@/components/ProcessToken.vue"),
    beforeEnter: (to, from, next) => {
      const authStore = useAuthStore();
      if (authStore.isAdminLoggedIn) {
        next();
      } else {
        next({ name: "login" });
        showWarning();
      }
    },
  },
];

const showWarning = () => {
  ElNotification({
    title: "Warning",
    message: "Vui lòng đăng nhập",
    type: "warning",
  });
};
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;
