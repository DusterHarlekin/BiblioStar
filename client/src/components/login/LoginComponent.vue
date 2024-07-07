<template>
  <div class="q-pa-md row items-start q-gutter-md">
    <q-card class="my-card q-px-lg q-py-xl">
      <div class="logo-container">
        <img src="../../assets/logo.png" />
      </div>

      <div class="q-pa-md">
        <q-form @submit="onSubmit" class="q-gutter-md">
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
      </div>
    </q-card>
  </div>
</template>

<script setup>
import { useQuasar } from "quasar";
import { ref } from "vue";

const $q = useQuasar();

const username = ref(null);
const password = ref(null);
const isPwd = ref(true);

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

  fetch(process.env.API_URL + "auth/auth.php", requestOptions)
    .then((response) => response.json())
    .then((data) => console.log(data));
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
</style>
