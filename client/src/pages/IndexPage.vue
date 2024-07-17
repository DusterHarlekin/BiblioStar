<template>
  <q-page class="q-py-md q-px-lg">
    <div
      class="row justify-between items-center q-gutter-x-xl q-gutter-y-md q-mb-md q-pt-sm"
    >
      <div class="col">
        <div class="row q-gutter-sm items-center q-mb-sm">
          <q-icon name="mdi-file-document-minus" size="lg" color="primary" />
          <div class="text-h4 text-weight-medium font-title">Resumen</div>
        </div>
      </div>
    </div>

    <div
      class="row q-mb-lg justify-center content-end items-center q-col-gutter-lg text-white"
    >
      <div class="col-lg-4 col-md-6 col-xs-12">
        <q-card class="q-py-xl bg-primary">
          <q-card-section class="row items-center">
            <q-icon name="mdi-account-clock" class="q-mr-md text-h3" />
            <div class="text-weight-light font-title" style="font-size: 2.7rem">
              Préstamos
            </div>
          </q-card-section>
          <q-card-section class="row">
            <div class="text-weight-light font-title" style="font-size: 5.5rem">
              25
            </div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-lg-4 col-md-6 col-xs-12">
        <q-card class="q-py-xl bg-secondary">
          <q-card-section class="row items-center">
            <q-icon name="mdi-book" class="q-mr-md text-h3" />
            <div class="text-weight-light font-title" style="font-size: 2.7rem">
              Libros
            </div>
          </q-card-section>
          <q-card-section class="row">
            <div class="text-weight-light font-title" style="font-size: 5.5rem">
              39500
            </div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-lg-4 col-md-6 col-xs-12">
        <q-card class="q-py-xl bg-primary">
          <q-card-section class="row items-center">
            <q-icon name="mdi-home-search" class="q-mr-md text-h3" />
            <div class="text-weight-light font-title" style="font-size: 2.7rem">
              Salas
            </div>
          </q-card-section>
          <q-card-section class="row">
            <div class="text-weight-light font-title" style="font-size: 5.5rem">
              10
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>

    <div class="row justify-center content-end items-center q-col-gutter-lg">
      <div class="col">
        <div class="row q-gutter-sm items-center q-mb-sm">
          <q-icon name="mdi-account-clock" size="lg" color="primary" />
          <div class="text-h4 text-weight-medium font-title">
            Libros prestados
          </div>
        </div>
        <!-- TABLA -->

        <q-table
          flat
          :dense="$q.screen.lt.lg"
          bordered
          v-model:pagination="pagination"
          :rows="prestamos"
          :columns="columns"
          :loading="isloading"
          row-key="cod_isbn"
          :rows-per-page-options="[]"
          @request="handleRequest"
        >
          <template #body-cell-actions="props">
            <q-td :props="props">
              <q-btn
                flat
                round
                icon="mdi-eye"
                color="positive"
                :to="`/prestamos/prestamo/${props.row.cod_isbn}`"
              >
                <q-tooltip>Ver</q-tooltip>
              </q-btn>
            </q-td>
          </template>
        </q-table>
      </div>
    </div>
  </q-page>
</template>

<script setup>
import { ref } from "vue";
import { useQuasar } from "quasar";

const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 0,
});

const $q = useQuasar();
const numPrestamos = ref(0);
const numSalas = ref(0);
const numLibros = ref(0);
const prestamos = ref([]);
const isloading = ref(false);

const columns = [
  {
    name: "cedula",
    label: "Cédula del lector",
    field: "cedula",
    align: "left",
  },
  {
    name: "cod_isbn",
    label: "Libro",
    field: (row) => `${row.cod_isbn} - ${row.titulo}`,
    align: "left",
  },
  {
    name: "fecha_s",
    label: "Fecha de salida",
    field: (row) => `${row.fecha_s} ${row.hora_s}`,
    align: "left",
  },
  {
    name: "fecha_e",
    label: "Fecha de entrega",
    field: (row) => `${row.fecha_e} ${row.hora_e}`,
    align: "left",
  },
  {
    name: "actions",
    label: "Acciones",
    align: "left",
  },
];

const fetchDashboard = async (page = 1) => {
  try {
    const requestOptions = {
      method: "GET",
      headers: { "Content-Type": "application/json" },
    };
    // API URL
    let url =
      process.env.API_URL +
      `dashboard.php?page=${page}&session_user_name=${localStorage.getItem(
        "usuario"
      )}&session_user_role=${localStorage.getItem("rol")}`;

    isloading.value = true;
    const response = await fetch(url, requestOptions);
    const data = await response.json();

    prestamos.value = data.data ? data.data : [];

    numPrestamos.value = data.cantPrestamos ? data.cantPrestamos : 0;
    numSalas.value = data.cantSalas ? data.cantSalas : 0;
    numLibros.value = data.cantLibros ? data.cantLibros : 0;

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
  fetchDashboard(props.pagination.page);
};

fetchDashboard();
</script>
