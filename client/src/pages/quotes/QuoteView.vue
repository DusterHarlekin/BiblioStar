<template>
  <q-page class="q-px-lg q-py-md">
    <div class="row q-gutter-sm items-center q-mb-md">
      <div>
        <q-btn
          icon="mdi-chevron-left"
          flat
          color="primary"
          padding="none"
          to="/cotas"
          size="lg"
          class="q-mr-sm"
        />
      </div>
      <q-icon name="mdi-bookmark" size="md" color="grey-9" />
      <div class="text-h5 text-weight-medium font-title text-grey-9">
        Cota "{{ currentQuote.cota }}"
      </div>
    </div>

    <q-card class="q-pa-md q-ma-lg">
      <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
          <q-list>
            <q-item>
              <q-item-section>
                <q-item-label caption>Cota completa</q-item-label>
                <q-item-label>{{ currentQuote.cota_completa }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Código ISBN</q-item-label>
                <q-item-label>{{ currentQuote.cod_isbn }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Cutter</q-item-label>
                <q-item-label>{{ currentQuote.cutter }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Volumen</q-item-label>
                <q-item-label>{{ currentQuote.volumen }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Ejemplares</q-item-label>
                <q-item-label>{{ currentQuote.ejemplar }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Fecha de ingreso</q-item-label>
                <q-item-label>{{ currentQuote.fecha_ing }}</q-item-label>
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

const quoteId = $route.params.id;
const currentQuote = ref({
  N: "",
  cota: "",
  cod_isbn: "",
  cutter: "",
  cota_completa: "",
  volumen: "",
  ejemplar: "",
  fecha_ing: "",
});

// Fetch ship from backend
const fetchQuote = async () => {
  const requestOptions = {
    method: "GET",
    headers: { "Content-Type": "application/json" },
  };

  // API URL
  const url =
    process.env.API_URL +
    `cotas.php?N=${quoteId}&session_user_name=${localStorage.getItem(
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

  currentQuote.value = clearSpaces(data[0]);
};

const clearSpaces = (obj) => {
  for (const key in obj) {
    if (obj[key].trim() === "") {
      obj[key] = "--";
    }
  }
  return obj;
};

fetchQuote();
</script>
