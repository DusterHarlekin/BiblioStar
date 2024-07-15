<template>
  <q-page class="q-px-lg q-py-sm">
    <div class="flex justify-between">
      <div class="row q-gutter-sm items-center q-mb-md q-pt-sm">
        <q-btn
          icon="mdi-chevron-left"
          flat
          color="primary"
          padding="none"
          to="/cotas"
          size="lg"
          class="q-mr-sm"
        />
        <q-icon name="mdi-bookmark" size="md" color="grey-9" />
        <div class="text-h5 text-weight-medium font-title text-grey-9">
          {{ isEditForm ? "Editar" : "Nueva" }} Cota
        </div>
      </div>
      <div class="text-right font-title">
        <q-btn
          size="md"
          class="q-my-md"
          :loading="isLoading"
          color="primary"
          padding="sm md"
          icon="mdi-check-circle-outline"
          label="Guardar"
          @click="submitForm"
        />
      </div>
    </div>
    <q-form>
      <q-card>
        <div class="row">
          <div class="col-12">
            <q-card-section class="q-pb-none">
              <q-input
                v-model="record.cota"
                label="Cota"
                class="required q-my-md"
                outlined
              />

              <q-input
                v-model="record.cod_isbn"
                label="Código ISBN"
                class="required q-my-md"
                outlined
              />

              <q-input
                v-model="record.cutter"
                label="Cutter"
                class="required q-my-md"
                outlined
              />

              <q-input
                v-model="record.cota_completa"
                label="Cota completa"
                class="required q-my-md"
                outlined
              />

              <q-input
                v-model="record.volumen"
                label="Volumen"
                class="required q-my-md"
                outlined
              />

              <q-input
                v-model="record.ejemplar"
                label="Ejemplares"
                class="required q-my-md"
                outlined
              />
            </q-card-section>
          </div>
        </div>
      </q-card>
    </q-form>
  </q-page>
</template>

<script setup>
import { date, useQuasar } from "quasar";

import { reactive, ref, toRefs } from "vue";

import { useRouter, useRoute } from "vue-router";

const $q = useQuasar();
const $router = useRouter();
const $route = useRoute();

const isLoading = ref(false);

const isEditForm =
  $route.path.includes("editar") && $route.params.id ? true : false;

const state = reactive({
  record: {
    cod_isbn: "",
    cota: "",
    cutter: "",
    volumen: "",
    ejemplar: "",
    cota_completa: "",

    fecha_ing: date.formatDate(Date.now(), "DD/MM/YYYY"),

    session_user_name: localStorage.getItem("usuario"),
    session_user_role: localStorage.getItem("rol"),
  },
});

const syncChanges = async () => {
  try {
    if (isEditForm) {
      const requestOptions = {
        method: "GET",
        headers: { "Content-Type": "application/json" },
      };

      // API URL
      const url =
        process.env.API_URL +
        `cotas.php?N=${
          $route.params.id
        }&session_user_name=${localStorage.getItem(
          "usuario"
        )}&session_user_role=${localStorage.getItem("rol")}`;

      const response = await fetch(url, requestOptions);

      if (!response.ok) {
        throw new Error(response.statusText);
      }
      const data = await response.json();

      if (data.error) {
        throw new Error(data.error);
      }
      console.log(data[0]);
      state.record = data[0];

      state.record.session_user_name = localStorage.getItem("usuario");
      state.record.session_user_role = localStorage.getItem("rol");
    }
  } catch (error) {
    $q.notify({
      color: "negative",
      position: "top",
      message: error.message,
      icon: "mdi-alert",
    });
  }
};

const submitForm = async () => {
  try {
    const requestOptions = {
      method: isEditForm ? "PUT" : "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(state.record),
    };

    const response = await fetch(
      process.env.API_URL + "cotas.php",
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
    $router.push({ path: "/cotas" });
  } catch (error) {
    $q.notify({
      color: "negative",
      position: "top",
      message: error.message,
      icon: "mdi-alert",
    });
  }
};

syncChanges();
const { record } = toRefs(state);
</script>
