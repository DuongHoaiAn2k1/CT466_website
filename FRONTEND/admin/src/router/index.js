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
    component: () => import("@/views/Home.vue"),
  },
  {
    path: "/product",
    name: "product",
    component: () => import("@/views/Product.vue"),
  },
  {
    path: "/product/create",
    name: "create-product",
    component: () => import("@/views/CreateProduct.vue"),
  },
  {
    path: "/product/update/:id",
    name: "update-product",
    component: () => import("@/views/UpdateProduct.vue"),
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;
