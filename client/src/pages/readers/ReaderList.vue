<template>
  <q-page class="q-py-md q-px-lg">
    <div
      class="row justify-between items-center q-gutter-x-xl q-gutter-y-md q-mb-md q-pt-sm"
    >
      <div class="col-md-6">
        <div class="row q-gutter-sm items-center">
          <q-btn
            icon="mdi-chevron-left"
            flat
            color="primary"
            padding="none"
            to="/prestamos"
            size="lg"
            class="col-md-1 q-mr-sm"
          />
          <q-icon name="mdi-library" size="lg" color="primary" />
          <div class="text-h4 text-weight-medium font-title">Lectores</div>
        </div>
      </div>
      <div class="col-auto">
        <q-btn
          label="Nuevo lector"
          icon="mdi-plus-circle"
          color="secondary"
          text-color="white"
          @click="createReader"
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
                    v-model="filter.cedula"
                    outlined
                    dense
                    debounce="400"
                    label="Cedula"
                  />
                </div>
                <div class="col-auto">
                  <q-input
                    v-model="filter.nombre"
                    outlined
                    dense
                    debounce="400"
                    label="Nombre"
                  />
                </div>

                <div class="col-auto">
                  <q-input
                    v-model="filter.apellido"
                    outlined
                    dense
                    debounce="400"
                    label="Apellido"
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
            @click="fetchLectores()"
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
      :rows="lectores"
      :columns="columns"
      :filter="filter"
      :loading="isloading"
      row-key="N"
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
            :to="`/lectores/lector/${props.row.N}`"
          >
            <q-tooltip>Ver</q-tooltip>
          </q-btn>
          <q-btn
            flat
            round
            icon="mdi-delete-variant"
            color="red"
            @click="deleteReader(props.row)"
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
import ReaderForm from "components/readers/ReaderForm.vue";

const $q = useQuasar();

//VALORES INICIALES
const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 0,
});

const filter = reactive({
  cedula: "",
  nombre: "",
  apellido: "",
  direccion: "",
  telefono: "",
  correo: "",
  edad: "",
  sexo: "",
});

const lectores = ref([]);
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
    name: "cedula",
    label: "Cedula",
    field: "cedula",
    align: "left",
  },
  {
    name: "nombre",
    label: "Nombre",
    field: "nombre",
    align: "left",
  },
  {
    name: "apellido",
    label: "Apellido",
    field: "apellido",
    align: "left",
  },
  {
    name: "telefono",
    label: "Teléfono",
    field: "telefono",
    align: "left",
  },
  {
    name: "correo",
    label: "Correo",
    field: "correo",
    align: "left",
  },
  {
    name: "edad",
    label: "Edad",
    field: "edad",
    align: "left",
  },
  {
    name: "sexo",
    label: "Sexo",
    field: "sexo",
    align: "left",
  },

  {
    name: "actions",
    label: "Acciones",
    align: "left",
  },
];

const deleteReader = (reader) => {
  $q.dialog({
    title: "Eliminar lector",
    message: `¿Estás seguro de que deseas eliminar este lector? (${reader.nombre} ${reader.apellido})`,
    cancel: true,
    persistent: true,
  }).onOk(async () => {
    try {
      const requestOptions = {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          N: reader.N,
          session_user_name: localStorage.getItem("usuario"),
          session_user_role: localStorage.getItem("rol"),
        }),
      };

      // API URL
      const url = process.env.API_URL + `lectores.php`;

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
        message: "Lector eliminado correctamente",
        icon: "mdi-check",
      });

      fetchLectores();
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

const fetchLectores = async (page = 1) => {
  try {
    const requestOptions = {
      method: "GET",
      headers: { "Content-Type": "application/json" },
    };
    // API URL
    let url =
      process.env.API_URL +
      `lectores.php?page=${page}&session_user_name=${localStorage.getItem(
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

    lectores.value = data.data ? data.data : [];

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
  fetchLectores(props.pagination.page);
};

const clearFilters = () => {
  filter.cedula = "";
  filter.nombre = "";
  filter.apellido = "";
};

const createReader = async () => {
  $q.dialog({
    component: ReaderForm,
    componentProps: { creatingFromLoan: false, isEditForm: false },
  }).onOk(async (payload) => {
    fetchLectores();
  });
};

fetchLectores();
</script>
