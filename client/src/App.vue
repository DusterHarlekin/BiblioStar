<script setup>
defineOptions({
  name: "App",
});

import { computed, watch } from "vue";
import { useRouter } from "vue-router";
import { useQuasar } from "quasar";
import { useAuthStore } from "./stores/auth/auth";

const $router = useRouter();
const $q = useQuasar();
const authStore = useAuthStore();

authStore.tryLogin();

const didAutoLogout = computed(() => authStore.didAutoLogout);

watch(didAutoLogout, (curValue, prevValue) => {
  if (curValue && curValue != prevValue) {
    $router.replace("/login");
    $q.notify({
      type: "warning",
      message: "No se pudo confirmar tu sesión. Inicia sesión nuevamente.",
    });
  }
});
</script>

<template>
  <router-view />
</template>
