<template>
  <AppHeader />
  <router-view />
  <AppFooter />
</template>

<script>
import AppHeader from "@/components/AppHeader.vue";
import AppFooter from "@/components/AppFooter.vue";
export default {
  components: {
    AppHeader,
    AppFooter,
  },
};
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
