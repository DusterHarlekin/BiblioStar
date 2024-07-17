<template>
  <q-page class="q-py-md q-px-lg">
    <div
      class="row justify-between items-center q-gutter-x-xl q-gutter-y-md q-mb-md q-pt-sm"
    >
      <div class="col-md-6">
        <div class="row q-gutter-sm items-center">
          <q-icon
            name="mdi-invoice-text-clock-outline"
            size="lg"
            color="primary"
          />
          <div class="text-h4 text-weight-medium font-title">
            Reporte de Préstamos Activos
          </div>
        </div>
      </div>
      <div class="col-md-auto q-gutter-md flex justify-evenly">
        <div>
          <q-btn
            label="Consultar reporte"
            icon="mdi-file-search-outline"
            color="primary"
            outline
            @click="makeRequest(tableRef)"
          />
        </div>
        <div>
          <q-btn
            label="Generar PDF"
            icon="mdi-file-pdf-box"
            color="secondary"
            text-color="white"
            @click="generatePDF()"
          />
        </div>
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
                <div class="col-auto flex no-wrap items-center">
                  <div class="row items-center justify-center q-gutter-md">
                    <div class="q-ml-lg">
                      <q-select
                        v-model="dateType"
                        style="min-width: 140px"
                        map-options
                        dense
                        outlined
                        emit-value
                        :options="[
                          { label: 'Fecha de salida', value: 'fecha_s' },
                          { label: 'Fecha de entrega', value: 'fecha_e' },
                        ]"
                        label="Tipo de fecha"
                        @update:model-value="fetchPrestamos"
                      />
                    </div>
                    <q-btn
                      color="primary"
                      flat
                      icon="mdi-calendar"
                      class="cursor-pointer q-pa-sm q-ml-xs"
                      rounded
                    >
                      <q-popup-proxy
                        cover
                        transition-show="scale"
                        transition-hide="scale"
                      >
                        <q-date mask="DD/MM/YYYY" range v-model="filter.date">
                          <div class="row items-center justify-end">
                            <q-btn
                              v-close-popup
                              label="Close"
                              color="primary"
                              flat
                            />
                          </div>
                        </q-date>
                      </q-popup-proxy>
                    </q-btn>

                    <q-item-label caption class="q-ml-xs">
                      <span
                        caption
                        v-if="filter.date && filter.date.from"
                        class="text-weight-medium"
                        >Desde
                        {{
                          filter.date.from && filter.date.to
                            ? filter.date.from + " Hasta " + filter.date.to
                            : filter.date.from
                        }}
                      </span>

                      <span
                        caption
                        v-else-if="filter.date && !filter.date.from"
                        class="text-weight-medium"
                        >{{ filter.date }}</span
                      >

                      <span caption v-else class="text-weight-medium"
                        >Ninguna</span
                      >
                    </q-item-label>
                  </div>
                </div>
                <div class="col-auto">
                  <q-input
                    v-model="filter.titulo"
                    outlined
                    dense
                    debounce="400"
                    label="Título"
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
                    v-model="filter.cedula"
                    outlined
                    dense
                    debounce="400"
                    label="Cédula"
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
            @click="fetchPrestamos()"
          >
            <q-tooltip> Actualizar tabla </q-tooltip>
          </q-btn>
        </div>
      </div>
    </q-card>

    <q-table
      flat
      ref="tableRef"
      :dense="$q.screen.lt.lg"
      bordered
      v-model:pagination="pagination"
      :rows="libros"
      :columns="columns"
      :filter="filter"
      :loading="isloading"
      row-key="N"
      :rows-per-page-options="[]"
    >
    </q-table>
  </q-page>
</template>

<script setup>
import { reactive, ref } from "vue";
import { useAuthStore } from "src/stores/auth/auth";
import { useQuasar } from "quasar";
import loansReport from "src/composables/reports/useLoansReport";

const $q = useQuasar();
const authStore = useAuthStore();

const tableRef = ref(null);

//VALORES INICIALES
const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 0,
});

const dateType = ref("fecha_s");

const filter = reactive({
  titulo: "",
  cod_isbn: "",
  cedula: "",
  date: "",
});

const salas = ref([]);
const libros = ref([]);
const isloading = ref(false);

// Q-Table columns
const columns = [
  {
    name: "cedula",
    label: "Cédula",
    field: "cedula",
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
    name: "titulo",
    label: "Título",
    field: "titulo",
    classes: "text-capitalize",
    align: "left",
  },
  {
    name: "fecha_s",
    label: "Fecha de salida",
    field: (row) =>
      row.fecha_s && row.fecha_s.trim() != ""
        ? row.fecha_s + " " + row.hora_s
        : "--",
    classes: "text-capitalize",
    align: "left",
  },
  {
    name: "fecha_e",
    label: "Fecha de entrega",
    field: (row) =>
      row.fecha_e && row.fecha_e.trim() != ""
        ? row.fecha_e + " " + row.hora_e
        : "--",
    classes: "text-capitalize",
    align: "left",
  },
];

const fetchPrestamos = async (page = 1) => {
  try {
    const requestOptions = {
      method: "GET",
      headers: { "Content-Type": "application/json" },
    };
    // API URL
    let url =
      process.env.API_URL +
      `prestamos.php?page=${page}&session_user_name=${localStorage.getItem(
        "usuario"
      )}&session_user_role=${localStorage.getItem("rol")}`;

    let params = new URLSearchParams(filter);

    //CONTROL DE FECHA
    if (filter.date) {
      params.set("dateQuery", dateType.value);
    } else {
      params.date = "empty";
    }

    let keysForDel = [];
    params.forEach((value, key) => {
      if (value == null || value.trim() == "") {
        console.log(key, value);
        keysForDel.push(key);
      }
    });

    if (filter.date && filter.date.from && filter.date.to) {
      params.set("date_from", filter.date.from);
      params.set("date_to", filter.date.to);
      params.delete("date");
    }
    //ELIMINAR KEYS VACIOS
    keysForDel.forEach((key) => {
      params.delete(key);
    });

    if (params.toString() != "") {
      url += `&${params.toString()}`;
    }
    console.log(url);
    isloading.value = true;
    const response = await fetch(url, requestOptions);
    const data = await response.json();

    libros.value = data.data ? data.data : [];
    console.log(data);

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

const makeRequest = async (props) => {
  if (filter.date || (filter.date.from && filter.date.to)) {
    await fetchPrestamos(props.pagination.page);
  } else {
    $q.notify({
      color: "negative",
      position: "top",
      message: "Por favor seleccione un rango de fechas",
      icon: "mdi-alert",
    });
  }
};

const generatePDF = () => {
  loansReport();
};

const clearFilters = () => {
  filter.titulo = "";
  filter.cedula = "";
  filter.cod_isbn = "";
  filter.date = null;
};
</script>
