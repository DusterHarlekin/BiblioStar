<template>
  <q-page class="q-py-md q-px-lg">
    <div
      class="row justify-between items-center q-gutter-x-xl q-gutter-y-md q-mb-md q-pt-sm"
    >
      <div class="col-md-6">
        <div class="row q-gutter-sm items-center">
          <q-icon name="mdi-account" size="lg" color="primary" />
          <div class="text-h4 text-weight-medium font-title">Usuarios</div>
        </div>
      </div>
      <div class="col-auto">
        <q-btn
          label="Nuevo Usuario"
          icon="mdi-plus-circle"
          color="secondary"
          text-color="white"
          @click="createUser"
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
                    v-model="filter.usuario"
                    outlined
                    dense
                    debounce="400"
                    label="Usuario"
                  />
                </div>

                <div class="col-auto">
                  <q-select
                    v-model="filter.rol"
                    style="min-width: 140px"
                    map-options
                    dense
                    outlined
                    emit-value
                    :options="[
                      { label: 'Administrador', value: 'admin' },
                      { label: 'Bibliotecario', value: 'librarian' },
                    ]"
                    label="Rol"
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
            @click="fetchUsuarios()"
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
      :rows="usuarios"
      :columns="columns"
      :filter="filter"
      :loading="isloading"
      row-key="cedula"
      :rows-per-page-options="[]"
      @request="handleRequest"
    >
      <template #body-cell-actions="props">
        <q-td :props="props">
          <q-btn
            flat
            round
            icon="mdi-lead-pencil"
            color="accent"
            @click="editUser(props.row)"
          >
            <q-tooltip>Editar</q-tooltip>
          </q-btn>
          <q-btn
            v-if="props.row.usuario != authStore.usuario"
            flat
            round
            icon="mdi-delete-variant"
            color="red"
            @click="deleteUser(props.row)"
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
import UserForm from "components/users/UserForm.vue";
import { useQuasar } from "quasar";
import { useAuthStore } from "src/stores/auth/auth";

const authStore = useAuthStore();

const $q = useQuasar();

//VALORES INICIALES
const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 0,
});

const filter = reactive({
  cedula: "",
  usuario: "",
  rol: "",
  nombre: "",
  apellido: "",
});

const usuarios = ref([]);
const isloading = ref(false);

// Q-Table columns
const columns = [
  {
    name: "usuario",
    label: "Usuario",
    field: "usuario",
    align: "left",
  },
  {
    name: "cedula",
    label: "Cédula",
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
    name: "rol",
    label: "Rol",
    field: (row) => {
      switch (row.rol) {
        case "admin":
          return "Administrador";
        case "librarian":
          return "Bibliotecario";
        default:
          return "--";
      }
    },
    align: "left",
  },
  {
    name: "actions",
    label: "Acciones",
    align: "left",
  },
];

const deleteUser = (user) => {
  $q.dialog({
    title: "Eliminar país",
    message: `¿Estás seguro de que deseas eliminar este usuario? (${user.usuario})`,
    cancel: true,
    persistent: true,
  }).onOk(async () => {
    try {
      const requestOptions = {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          cedula: user.cedula,
          session_user_name: localStorage.getItem("usuario"),
          session_user_role: localStorage.getItem("rol"),
        }),
      };

      // API URL
      const url = process.env.API_URL + `auth/auth.php`;

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
        message: data.success,
        icon: "mdi-check",
      });

      fetchUsuarios();
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

const fetchUsuarios = async (page = 1) => {
  try {
    const requestOptions = {
      method: "GET",
      headers: { "Content-Type": "application/json" },
    };
    // API URL
    let url =
      process.env.API_URL +
      `auth/auth.php?page=${page}&session_user_name=${localStorage.getItem(
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

    usuarios.value = data.data ? data.data : [];
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

const handleRequest = (props) => {
  fetchUsuarios(props.pagination.page);
};

const clearFilters = () => {
  filter.usuario = "";
  filter.cedula = "";
  filter.rol = "";
  filter.nombre = "";
  filter.apellido = "";
};

const createUser = async () => {
  $q.dialog({
    component: UserForm,
    componentProps: { isEditForm: false },
  }).onOk(async () => {
    await fetchUsuarios();
  });
};

const editUser = async (user) => {
  $q.dialog({
    component: UserForm,
    componentProps: { isEditForm: true, cedula: user.cedula },
  }).onOk(async () => {
    await fetchUsuarios();
  });
};

fetchUsuarios();
</script>
