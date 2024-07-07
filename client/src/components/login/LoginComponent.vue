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

defineProps({
  isGuest: Boolean,
});

const $q = useQuasar();

const username = ref(null);
const password = ref(null);
const cedula = ref(null);
const isPwd = ref(true);

onMounted(() => {
  $q.localStorage.clear();
});

const onSubmit = async () => {
  // $q.notify({
  //   color: "green-4",
  //   textColor: "white",
  //   icon: "cloud_done",
  //   message: "Submitted",
  // });

  const requestOptions = {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      usuario: username.value,
      clave: password.value,
      request: "Login",
    }),
  };
  console.log(requestOptions.body);

  try {
    const response = await fetch(
      process.env.API_URL + "auth/auth.php",
      requestOptions
    );
    if (!response.ok) {
      throw new Error(response.statusText);
    }
    const data = await response.json();

    if (data.error) {
      throw new Error(data.error);
    }

    $q.localStorage.set("usuario", data[0].usuario);
    $q.localStorage.set("rol", data[0].rol);

    console.log(data[0]);
    console.log($q.localStorage.getItem("rol"));
    console.log($q.localStorage.getItem("usuario"));
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
  const requestOptions = {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      cedula: cedula.value,
      rol: "invitado",
      request: "Login",
    }),
  };
  console.log(requestOptions.body);

  try {
    const response = await fetch(
      process.env.API_URL + "auth/auth.php",
      requestOptions
    );
    if (!response.ok) {
      throw new Error(response.statusText);
    }
    const data = await response.json();

    if (data.error) {
      throw new Error(data.error);
    }

    $q.localStorage.set("cedula", data.cedula);
    $q.localStorage.set("rol", data.rol);

    console.log(data);
    console.log($q.localStorage.getItem("rol"));
    console.log($q.localStorage.getItem("cedula"));
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
  width: 100%;
  min-width: 400px;
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
