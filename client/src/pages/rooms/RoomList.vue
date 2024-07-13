<template>
  <q-page class="q-py-md q-px-lg">
    <div
      class="row justify-between items-center q-gutter-x-xl q-gutter-y-md q-mb-md q-pt-sm"
    >
      <div class="col-md-6">
        <div class="row q-gutter-sm items-center">
          <q-icon name="mdi-home-search" size="lg" color="primary" />
          <div class="text-h4 text-weight-medium font-title">Salas</div>
        </div>
      </div>
      <div class="col-auto">
        <q-btn
          label="Nueva Sala"
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
                    v-model="filter.cod_sala"
                    outlined
                    dense
                    debounce="400"
                    label="Código de sala"
                  />
                </div>

                <div class="col-auto">
                  <q-input
                    v-model="filter.DESCRIPCION"
                    outlined
                    dense
                    debounce="400"
                    label="Descripción"
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
            @click="fetchSalas()"
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
      :rows="cod_salas"
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
          <q-btn flat round icon="mdi-delete-variant" color="red">
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
  cod_sala: "",
  DESCRIPCION: "",
});

const cod_salas = ref([]);
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
    name: "cod_sala",
    label: "Código de sala",
    field: "cod_sala",
    align: "left",
  },
  {
    name: "DESCRIPCION",
    label: "Descripción",
    field: (row) =>
      row.DESCRIPCION && row.DESCRIPCION.trim() != "" ? row.DESCRIPCION : "--",
    align: "left",
  },
  {
    name: "actions",
    label: "Acciones",
    align: "left",
  },
];

const fetchSalas = async (page = 1) => {
  try {
    const requestOptions = {
      method: "GET",
      headers: { "Content-Type": "application/json" },
    };
    // API URL
    let url = process.env.API_URL + `salas.php?page=${page}`;

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

    cod_salas.value = data.data ? data.data : [];

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
  fetchSalas(props.pagination.page);
};

const clearFilters = () => {
  filter.cod_sala = "";
  filter.DESCRIPCION = "";
};

fetchSalas();
</script>
