<template>
  <q-page class="q-px-lg q-py-md">
    <div class="row q-gutter-sm items-center q-mb-md">
      <div>
        <q-btn
          icon="mdi-chevron-left"
          flat
          color="primary"
          padding="none"
          to="/prestamos"
          size="lg"
          class="q-mr-sm"
        />
      </div>
      <q-icon name="mdi-account-clock" size="md" color="grey-9" />
      <div class="text-h5 text-weight-medium font-title text-grey-9">
        Préstamo "{{ currentLoan.cod_isbn }} {{ currentLoan.titulo }}"
      </div>
    </div>

    <q-card class="q-pa-md q-ma-lg">
      <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
          <q-list>
            <q-item>
              <q-item-section>
                <q-item-label caption>Código ISBN</q-item-label>
                <q-item-label>{{ currentLoan.cod_isbn }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Título</q-item-label>
                <q-item-label>{{ currentLoan.titulo }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Cédula de lector</q-item-label>
                <q-item-label>{{ currentLoan.cedula }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Observación</q-item-label>
                <q-item-label>{{ currentLoan.observacion }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Fecha de salida</q-item-label>
                <q-item-label
                  >{{ currentLoan.fecha_s }}
                  {{ currentLoan.hora_s }}</q-item-label
                >
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Fecha de entrega</q-item-label>
                <q-item-label
                  >{{ currentLoan.fecha_e }}
                  {{ currentLoan.hora_e }}</q-item-label
                >
              </q-item-section>
            </q-item>
          </q-list>
        </div>
      </div>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref } from "vue";

import { useRoute } from "vue-router";

const $route = useRoute();

const loanId = $route.params.id;
const currentLoan = ref({
  cod_isbn: "",
  titulo: "",
  cedula: "",
  observacion: "",
  fecha_s: "",
  hora_s: "",
  fecha_e: "",
  hora_e: "",
});

// Fetch ship from backend
const fetchLoan = async () => {
  const requestOptions = {
    method: "GET",
    headers: { "Content-Type": "application/json" },
  };

  // API URL
  const url =
    process.env.API_URL +
    `prestamos.php?cod_isbn=${loanId}&session_user_name=${localStorage.getItem(
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

  currentLoan.value = clearSpaces(data[0]);
};

const clearSpaces = (obj) => {
  for (const key in obj) {
    if (obj[key].trim() === "") {
      obj[key] = "--";
    }
  }
  return obj;
};

fetchLoan();
</script>
