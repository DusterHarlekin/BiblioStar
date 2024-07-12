<template>
  <div class="q-pa-md row items-start q-gutter-md">
    <q-card class="my-card column q-px-lg q-py-xl">
      <div class="logo-container">
        <img src="../../assets/logo.png" />
      </div>

      <div class="q-pa-md">
        <q-form v-if="!isGuest" @submit="onSubmit" class="q-gutter-md">
          <q-input v-model="username" label="Usuario" />

          <q-input
            v-model="password"
            label="Contraseña"
            :type="isPwd ? 'password' : 'text'"
          >
            <template v-slot:append>
              <q-icon
                :name="isPwd ? 'visibility_off' : 'visibility'"
                class="cursor-pointer"
                @click="isPwd = !isPwd"
              />
            </template>
          </q-input>

          <div class="column items-center justify-center q-mt-xl">
            <q-btn
              size="18px"
              class="q-px-xl q-py-xs"
              type="submit"
              color="primary"
              label="Iniciar Sesión"
              no-caps
            />
          </div>
        </q-form>

        <q-form v-if="isGuest" @submit="onGuestSubmit" class="q-gutter-md">
          <q-input v-model="cedula" label="Cédula" />

          <div class="column items-center justify-center q-mt-xl">
            <q-btn
              size="18px"
              class="q-px-xl q-py-xs"
              type="submit"
              color="primary"
              label="Entrar"
              no-caps
            />
          </div>
        </q-form>
      </div>

      <RouterLink v-if="!isGuest" class="guestLink" to="/login/invitado"
        >Entrar como invitado</RouterLink
      >
      <RouterLink v-if="isGuest" class="guestLink" to="/login"
        >Volver al inicio de sesión</RouterLink
      >
    </q-card>
  </div>
</template>

<script setup>
import { useQuasar } from "quasar";
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";

import { useAuthStore } from "src/stores/auth/auth";

const authStore = useAuthStore();

const $route = useRoute();

const $router = useRouter();

defineProps({
  isGuest: Boolean,
});

const $q = useQuasar();

const username = ref(null);
const password = ref(null);
const cedula = ref(null);
const isPwd = ref(true);

onMounted(() => {
  //$q.localStorage.clear();
});

const onSubmit = async () => {
  try {
    await authStore.login({
      username: username.value,
      password: password.value,
    });
    const redirectUrl = "/" + ($route.query.redirect || "");
    $router.replace(redirectUrl);
    $q.notify({
      type: "positive",
      message: "Iniciaste sesión correctamente.",
    });
  } catch (error) {
    console.log(error.message);
    $q.notify({
      color: "negative",
      position: "top",
      message: error.message,
      icon: "warning",
    });
  }
};

const onGuestSubmit = async () => {
  try {
    await authStore.loginGuest({
      cedula: cedula.value,
    });
    const redirectUrl = "/" + ($route.query.redirect || "");
    $router.replace(redirectUrl);
    $q.notify({
      type: "positive",
      message: "Iniciaste sesión correctamente.",
    });
  } catch (error) {
    console.log(error.message);
    $q.notify({
      color: "negative",
      position: "top",
      message: error.message,
      icon: "warning",
    });
  }
};
</script>

<style scoped>
.my-card {
  width: 40vw;
  min-width: 300px;
  max-width: 400px;
}

.logo-container {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.guestLink {
  text-align: center;
  font-size: 15px;
  color: #3e8ed0;
  text-decoration: none;
}
</style>
