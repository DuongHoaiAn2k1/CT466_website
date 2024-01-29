import { createWebHistory, createRouter } from "vue-router";

const routes = [
  {
    path: "/:pathMatch(.*)*",
    name: "notfound",
    component: () => import("@/views/404.vue"),
  },

  {
    path: "/",
    name: "home",
    component: () => import("@/components/Home.vue"),
  },

  {
    path: "/register",
    name: "register",
    component: () => import("@/views/Register.vue"),
  },
  {
    path: "/login",
    name: "login",
    component: () => import("@/views/Login.vue"),
  },
  {
    path: "/product/detail/:id",
    name: "product-detail",
    component: () => import("@/views/ProductDetail.vue"),
  },
  {
    path: "/product/:id",
    name: "product",
    component: () => import("@/views/Product.vue"),
  },
  {
    path: "/profile",
    name: "profile",
    component: () => import("@/views/Profile.vue"),
  },
  {
    path: "/cart",
    name: "cart",
    component: () => import("@/views/Cart.vue"),
  },
  {
    path: "/tokenProcess",
    name: "token",
    component: () => import("@/components/ProcessToken.vue"),
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;
