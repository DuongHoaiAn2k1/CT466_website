<template>
  <AppHeader :number="number" />
  <router-view />
  <AppFooter />
</template>

<script setup>
import { ref, onMounted, watchEffect, watch } from "vue";
import AppHeader from "@/components/AppHeader.vue";
import AppFooter from "@/components/AppFooter.vue";
import cartService from "@/services/cart.service";
import { useCartStore } from "@/stores/cart";
const number = ref(0);
// const countCart = async () => {
//   try {
//     const response = await cartService.count();
//     number.value = response.number;
//   } catch (error) {
//     console.error(error.response);
//   }
// };

const cartStore = useCartStore();

onMounted(async () => {
  await cartStore.fetchCartCount();
  number.value = cartStore.count;
});

watch(() => {
  number.value = cartStore.count;
});
</script>
<style>
body {
  margin: 0;
  font-family: "Quicksand", sans-serif !important;
}

#app {
  font-family: Roboto, Helvetica, Arial, sans-serif;
}
</style>
