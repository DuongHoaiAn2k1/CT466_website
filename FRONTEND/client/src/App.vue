<template>
  <AppHeader :number="number" />
  <router-view />
  <AppFooter />
</template>

<script setup>
import { ref, onMounted, watchEffect, watch } from "vue";
import AppHeader from "@/components/AppHeader.vue";
import AppFooter from "@/components/AppFooter.vue";
import { useCartStore } from "@/stores/cart";
import { useAuthStore } from "@/stores/auth";
const number = ref(0);

const cartStore = useCartStore();
const authStore = useAuthStore();
onMounted(async () => {
  if (authStore.isUserLoggedIn == true) {
    await cartStore.fetchCartCount();
    number.value = cartStore.count;
  } else {
    number.value = 0;
  }
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
import { useAuthStore } from "./stores/auth";useAuthStore,
