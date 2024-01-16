<template>
  <AppHeader />
  <div id="layoutSidenav">
    <SideBar />
    <router-view />
  </div>
  <AppFooter />
</template>

<script setup>
import AppHeader from "@/components/AppHeader.vue";
import AppFooter from "@/components/AppFooter.vue";
import SideBar from "@/components/SideBar.vue";
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
