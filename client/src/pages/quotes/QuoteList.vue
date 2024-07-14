<template>
  <q-page class="q-py-md q-px-lg">
    <div
      class="row justify-between items-center q-gutter-x-xl q-gutter-y-md q-mb-md q-pt-sm"
    >
      <div class="col-md-6">
        <div class="row q-gutter-sm items-center">
          <q-icon name="mdi-bookmark" size="lg" color="primary" />
          <div class="text-h4 text-weight-medium font-title">Cotas</div>
        </div>
      </div>
      <div class="col-auto">
        <q-btn
          label="Nueva cota"
          icon="mdi-plus-circle"
          color="secondary"
          text-color="white"
        />
      </div>
    </div>

    <!--FILTROS-->
    <q-card class="q-pa-md q-mb-lg">
      <div class="row justify-between content-end items-center q-col-gutter-sm">
        <div class="col-auto">
          <div class="row items-center">
            <div class="col-xs-12 col-lg-auto">
              <div class="row items-center q-gutter-md">
                <q-icon name="mdi-filter-variant" size="lg" color="grey-10" />

                <div class="col-auto">
                  <q-input
                    v-model="filter.cota"
                    outlined
                    dense
                    debounce="400"
                    label="Cota"
                  />
                </div>
                <div class="col-auto">
                  <q-input
                    v-model="filter.cod_isbn"
                    outlined
                    dense
                    debounce="400"
                    label="Código ISBN"
                  />
                </div>

                <div class="col-auto">
                  <q-input
                    v-model="filter.cutter"
                    outlined
                    dense
                    debounce="400"
                    label="Cutter"
                  />
                </div>

                <div class="col-auto">
                  <q-input
                    v-model="filter.cota_completa"
                    outlined
                    dense
                    debounce="400"
                    label="Cota Completa"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-auto self-end">
          <q-btn
            flat
            size="lg"
            no-caps
            icon="mdi-eraser"
            color="negative"
            @click="clearFilters"
          />

          <q-btn
            flat
            size="lg"
            no-caps
            icon="mdi-reload"
            color="primary"
            class="q-ml-sm"
            @click="fetchCotas()"
          >
            <q-tooltip> Actualizar tabla </q-tooltip>
          </q-btn>
        </div>
      </div>
    </q-card>

    <q-table
      flat
      :dense="$q.screen.lt.lg"
      bordered
      v-model:pagination="pagination"
      :rows="cotas"
      :columns="columns"
      :filter="filter"
      :loading="isloading"
      row-key="N"
      :rows-per-page-options="[]"
      @request="handleRequest"
    >
      <template #body-cell-actions="props">
        <q-td :props="props">
          <q-btn flat round icon="mdi-eye" color="positive">
            <q-tooltip>Ver</q-tooltip>
          </q-btn>
          <q-btn flat round icon="mdi-lead-pencil" color="accent">
            <q-tooltip>Editar</q-tooltip>
          </q-btn>
          <q-btn
            flat
            round
            icon="mdi-delete-variant"
            color="red"
            @click="deleteQuote(props.row)"
          >
            <q-tooltip>Eliminar</q-tooltip>
          </q-btn>
        </q-td>
      </template>
    </q-table>
  </q-page>
</template>

<script setup>
import { reactive, ref } from "vue";

import { useQuasar } from "quasar";

const $q = useQuasar();

//VALORES INICIALES
const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 0,
});

const filter = reactive({
  cota: "",
  cod_isbn: "",
  cutter: "",
  cota_completa: "",
});

const cotas = ref([]);
const isloading = ref(false);

// Q-Table columns
const columns = [
  {
    name: "N",
    label: "Número",
    field: "N",
    align: "left",
  },
  {
    name: "cota",
    label: "Cota",
    field: (row) => (row.cota && row.cota.trim() != "" ? row.cota : "--"),
    align: "left",
  },
  {
    name: "cod_isbn",
    label: "Código ISBN",
    field: (row) =>
      row.cod_isbn && row.cod_isbn.trim() != "" ? row.cod_isbn : "--",
    align: "left",
  },
  {
    name: "cutter",
    label: "Cutter",
    field: (row) => (row.cutter && row.cutter.trim() != "" ? row.cutter : "--"),
    align: "left",
  },
  {
    name: "cota_completa",
    label: "Cota Completa",
    field: (row) =>
      row.cota_completa && row.cota_completa.trim() != ""
        ? row.cota_completa
        : "--",
    align: "left",
  },

  {
    name: "actions",
    label: "Acciones",
    align: "left",
  },
];

const deleteQuote = (quote) => {
  $q.dialog({
    title: "Eliminar cota",
    message: `¿Estás seguro de que deseas eliminar esta cota? (${quote.cota})`,
    cancel: true,
    persistent: true,
  }).onOk(async () => {
    try {
      const requestOptions = {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          N: quote.N,
          session_user_name: localStorage.getItem("usuario"),
          session_user_role: localStorage.getItem("rol"),
        }),
      };

      // API URL
      const url = process.env.API_URL + `cotas.php`;

      console.log(requestOptions.body);
      const response = await fetch(url, requestOptions);

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
        message: "Cota eliminada correctamente",
        icon: "mdi-check",
      });

      fetchCotas();
    } catch (error) {
      $q.notify({
        color: "negative",
        position: "top",
        message: error.message,
        icon: "mdi-alert",
      });
    }
  });
};

const fetchCotas = async (page = 1) => {
  try {
    const requestOptions = {
      method: "GET",
      headers: { "Content-Type": "application/json" },
    };
    // API URL
    let url =
      process.env.API_URL +
      `cotas.php?page=${page}&session_user_name=${localStorage.getItem(
        "usuario"
      )}&session_user_role=${localStorage.getItem("rol")}`;

    let params = new URLSearchParams(filter);
    let keysForDel = [];
    params.forEach((value, key) => {
      if (value.trim() == "") {
        keysForDel.push(key);
      }
    });

    keysForDel.forEach((key) => {
      params.delete(key);
    });

    if (params.toString() != "") {
      url += `&${params.toString()}`;
    }

    isloading.value = true;
    const response = await fetch(url, requestOptions);
    const data = await response.json();

    cotas.value = data.data ? data.data : [];

    //ACTUALIZO VALORES DE PAGINACIÓN
    pagination.value.rowsNumber = data.pagination?.total
      ? data.pagination.total
      : 0;

    pagination.value.page = data.pagination?.currentPage
      ? data.pagination.currentPage
      : 1;
    pagination.value.rowsPerPage = data.pagination?.perPage
      ? data.pagination.perPage
      : 10;
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

const handleRequest = (props) => {
  fetchCotas(props.pagination.page);
};

const clearFilters = () => {
  filter.cota = "";
  filter.cod_isbn = "";
  filter.cutter = "";
  filter.cota_completa = "";
};

fetchCotas();
</script>
