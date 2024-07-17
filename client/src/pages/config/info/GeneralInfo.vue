<template>
  <q-page class="q-py-md q-px-lg">
    <div
      class="row justify-between items-center q-gutter-x-xl q-gutter-y-md q-mb-md q-pt-sm"
    >
      <div class="col">
        <div class="row q-gutter-sm items-center q-mb-sm">
          <q-icon name="mdi-file-eye" size="lg" color="primary" />
          <div class="text-h4 text-weight-medium font-title">
            Información general
          </div>
        </div>
      </div>
    </div>

    <div
      class="row q-mb-lg justify-center content-end items-center q-col-gutter-xl text-white"
    >
      <q-form @submit="submitForm" class="q-gutter-md col">
        <q-card class="my-card">
          <q-card-section
            class="bg-primary row items-center justify-between text-white"
          >
            <div class="text-h6">Información de la biblioteca</div>
            <q-toggle
              v-model="editInfo"
              left-label
              label="Editar información"
              color="secondary"
            ></q-toggle>
          </q-card-section>
          <q-card-section>
            <q-input
              v-model="config.nombre_organizacion"
              label="Nombre de la organización"
              :readonly="!editInfo"
            />
            <q-input v-model="config.RIF" label="RIF" :readonly="!editInfo" />
            <q-input
              v-model="config.direccion"
              label="Dirección"
              type="textarea"
              :readonly="!editInfo"
            />
            <q-btn
              type="submit"
              color="primary"
              class="q-mt-md full-width"
              size="md"
              :loading="isloading"
              label="Guardar cambios"
              v-if="editInfo"
            />
          </q-card-section>
        </q-card>
      </q-form>
    </div>
  </q-page>
</template>

<script setup>
import { ref } from "vue";
import { useQuasar } from "quasar";

const config = ref({
  nombre_organizacion: "",
  RIF: "",
  direccion: "",
});
const editInfo = ref(false);
const isLoading = ref(false);
const $q = useQuasar();

const isloading = ref(false);

const submitForm = async () => {
  try {
    config.value.session_user_name = localStorage.getItem("usuario");
    config.value.session_user_role = localStorage.getItem("rol");

    const requestOptions = {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(config.value),
    };

    const response = await fetch(
      process.env.API_URL + "config.php",
      requestOptions
    );
    if (!response.ok) {
      throw new Error(response.statusText);
    }
    const data = await response.json();
    if (data.error) {
      throw new Error(data.error);
    }
    $q.notify({
      color: "positive",
      position: "top",
      message: data.success,
      icon: "mdi-checkbox-marked-circle",
    });
    await fetchConfig();
    editInfo.value = false;
  } catch (error) {
    $q.notify({
      color: "negative",
      position: "top",
      message: error.message,
      icon: "mdi-alert",
    });
  }
};
const fetchConfig = async (page = 1) => {
  try {
    const requestOptions = {
      method: "GET",
      headers: { "Content-Type": "application/json" },
    };
    // API URL
    let url =
      process.env.API_URL +
      `config.php?page=${page}&session_user_name=${localStorage.getItem(
        "usuario"
      )}&session_user_role=${localStorage.getItem("rol")}`;

    isloading.value = true;
    const response = await fetch(url, requestOptions);
    const data = await response.json();

    if (data.error) {
      throw new Error(data.error);
    }
    config.value = data[0] ? data[0] : {};
  } catch (error) {
    $q.notify({
      color: "negative",
      position: "top",
      message: error.message,
      icon: "mdi-alert",
    });
  } finally {
    isloading.value = false;
  }
};

fetchConfig();
</script>
