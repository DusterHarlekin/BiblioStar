<script setup>
import LoginComponent from "components/login/LoginComponent.vue";
</script>

<template>
  <q-page class="container-page flex flex-center">
    <LoginComponent />
  </q-page>
</template>

<style scoped>
.container-page {
  background: rgb(3, 31, 103);
  background: linear-gradient(
    45deg,
    rgba(3, 31, 103, 1) 50%,
    rgba(6, 29, 87, 1) 50%
  );
}
</style>
