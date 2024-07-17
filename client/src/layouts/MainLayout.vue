<template>
  <q-layout view="lHr LpR lfr">
    <q-header
      v-if="$q.screen.lt.md"
      reveal
      bordered
      :dark="true"
      class="bg-dark"
    >
      <q-toolbar>
        <q-btn
          dense
          flat
          round
          icon="menu"
          @click="toggleLeftDrawer"
          class="text-white"
        />

        <q-toolbar-title>
          <div class="row items-center">
            <div class="text-h6 text-weight-bold font-title text-accent"></div>
          </div>
        </q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
      :mini="drawerMiniState"
      class="bg-dark text-white"
    >
      <div class="column justify-between window-height q-py-lg no-wrap">
        <div>
          <q-item-label class="q-py-md">
            <q-item v-ripple>
              <q-item-section side>
                <q-avatar rounded size="70px">
                  <img src="../assets/logo.png" />
                </q-avatar>
              </q-item-section>
              <q-item-section style="font-size: 1.5em">
                <q-item-label>{{ name }}</q-item-label>
                <q-item-label class="text-subtitle1 text-grey">{{
                  role
                }}</q-item-label>
              </q-item-section>
            </q-item>
          </q-item-label>

          <q-list class="text-white" :class="drawerMiniState ? '' : 'q-pl-lg'">
            <div>
              <q-item
                to="/"
                clickable
                v-ripple
                v-if="
                  session_user_role === 'admin' ||
                  session_user_role === 'librarian'
                "
              >
                <q-item-section avatar>
                  <q-icon name="mdi-file-document-minus" />
                  <q-tooltip
                    v-if="drawerMiniState"
                    anchor="center right"
                    self="center left"
                    :offset="[22, 10]"
                    class="text-caption bg-primary shadow-1"
                  >
                    Resumen
                  </q-tooltip>
                </q-item-section>

                <q-item-section style="font-size: 1.5em">
                  Resumen
                </q-item-section>
              </q-item>

              <q-item to="/libros" clickable v-ripple>
                <q-item-section avatar>
                  <q-icon name="mdi-book" />
                  <q-tooltip
                    v-if="drawerMiniState"
                    anchor="center right"
                    self="center left"
                    :offset="[22, 10]"
                    class="text-caption bg-primary shadow-1"
                  >
                    Libros
                  </q-tooltip>
                </q-item-section>

                <q-item-section style="font-size: 1.5em">
                  Libros
                </q-item-section>
              </q-item>

              <q-item
                to="/cotas"
                clickable
                v-ripple
                v-if="
                  session_user_role === 'admin' ||
                  session_user_role === 'librarian'
                "
              >
                <q-item-section avatar>
                  <q-icon name="mdi-bookmark" />
                  <q-tooltip
                    v-if="drawerMiniState"
                    anchor="center right"
                    self="center left"
                    :offset="[22, 10]"
                    class="text-caption bg-primary shadow-1"
                  >
                    Cotas
                  </q-tooltip>
                </q-item-section>

                <q-item-section style="font-size: 1.5em">
                  Cotas
                </q-item-section>
              </q-item>

              <q-item
                to="/salas"
                clickable
                v-ripple
                v-if="
                  session_user_role === 'admin' ||
                  session_user_role === 'librarian'
                "
              >
                <q-item-section avatar>
                  <q-icon name="mdi-home-search" />
                  <q-tooltip
                    v-if="drawerMiniState"
                    anchor="center right"
                    self="center left"
                    :offset="[22, 10]"
                    class="text-caption bg-primary shadow-1"
                  >
                    Salas
                  </q-tooltip>
                </q-item-section>

                <q-item-section style="font-size: 1.5em">
                  Salas
                </q-item-section>
              </q-item>

              <q-item
                to="/paises"
                clickable
                v-ripple
                v-if="
                  session_user_role === 'admin' ||
                  session_user_role === 'librarian'
                "
              >
                <q-item-section avatar>
                  <q-icon name="mdi-map-marker" />
                  <q-tooltip
                    v-if="drawerMiniState"
                    anchor="center right"
                    self="center left"
                    :offset="[22, 10]"
                    class="text-caption bg-primary shadow-1"
                  >
                    Países
                  </q-tooltip>
                </q-item-section>

                <q-item-section style="font-size: 1.5em">
                  Países
                </q-item-section>
              </q-item>

              <q-item
                to="/ciudades"
                clickable
                v-ripple
                v-if="
                  session_user_role === 'admin' ||
                  session_user_role === 'librarian'
                "
              >
                <q-item-section avatar>
                  <q-icon name="mdi-home-city" />
                  <q-tooltip
                    v-if="drawerMiniState"
                    anchor="center right"
                    self="center left"
                    :offset="[22, 10]"
                    class="text-caption bg-primary shadow-1"
                  >
                    Ciudades
                  </q-tooltip>
                </q-item-section>

                <q-item-section style="font-size: 1.5em">
                  Ciudades
                </q-item-section>
              </q-item>

              <q-item
                to="/idiomas"
                clickable
                v-ripple
                v-if="
                  session_user_role === 'admin' ||
                  session_user_role === 'librarian'
                "
              >
                <q-item-section avatar>
                  <q-icon name="mdi-book-open-blank-variant-outline" />
                  <q-tooltip
                    v-if="drawerMiniState"
                    anchor="center right"
                    self="center left"
                    :offset="[22, 10]"
                    class="text-caption bg-primary shadow-1"
                  >
                    Idiomas
                  </q-tooltip>
                </q-item-section>

                <q-item-section style="font-size: 1.5em">
                  Idiomas
                </q-item-section>
              </q-item>

              <q-item
                to="/prestamos"
                clickable
                v-ripple
                v-if="
                  session_user_role === 'admin' ||
                  session_user_role === 'librarian'
                "
              >
                <q-item-section avatar>
                  <q-icon name="mdi-account-clock" />
                  <q-tooltip
                    v-if="drawerMiniState"
                    anchor="center right"
                    self="center left"
                    :offset="[22, 10]"
                    class="text-caption bg-primary shadow-1"
                  >
                    Préstamos
                  </q-tooltip>
                </q-item-section>

                <q-item-section style="font-size: 1.5em">
                  Préstamos
                </q-item-section>
              </q-item>
            </div>
          </q-list>
        </div>

        <q-list
          padding
          class="text-white"
          :class="drawerMiniState ? '' : 'q-pl-lg'"
        >
          <div>
            <q-item
              to="/reportes"
              clickable
              v-ripple
              v-if="
                session_user_role === 'admin' ||
                session_user_role === 'librarian'
              "
            >
              <q-item-section avatar>
                <q-icon name="mdi-printer" />
                <q-tooltip
                  v-if="drawerMiniState"
                  anchor="center right"
                  self="center left"
                  :offset="[22, 10]"
                  class="text-caption bg-primary shadow-1"
                >
                  Reportes
                </q-tooltip>
              </q-item-section>

              <q-item-section style="font-size: 1.5em">
                Reportes</q-item-section
              >
            </q-item>

            <q-expansion-item
              icon="mdi-cog"
              label="Configuración"
              group="config"
              dense-toggle
              expand-icon-class="text-white"
              style="font-size: 1.5em"
              :content-inset-level="0.5"
              v-if="!drawerMiniState && session_user_role === 'admin'"
            >
              <q-item to="/configuracion/info-general" clickable v-ripple>
                <q-item-section avatar>
                  <q-icon name="mdi-file-eye" />
                </q-item-section>

                <q-item-section> Información </q-item-section>
              </q-item>

              <q-item to="/configuracion/usuarios" clickable v-ripple>
                <q-item-section avatar>
                  <q-icon name="mdi-account" />
                </q-item-section>

                <q-item-section> Usuarios </q-item-section>
              </q-item>
            </q-expansion-item>

            <q-item
              clickable
              v-ripple
              v-else-if="session_user_role === 'admin'"
            >
              <q-item-section
                avatar
                class="row items-center"
                style="width: 100%"
              >
                <q-icon name="mdi-cog" />
                <q-menu class="bg-dark">
                  <q-list>
                    <q-item to="/configuracion/info-general" clickable v-ripple>
                      <q-item>
                        <q-item-section avatar>
                          <q-icon name="mdi-file-eye" />
                        </q-item-section>
                        <q-item-section> Información </q-item-section>
                      </q-item>
                    </q-item>

                    <q-item to="/configuracion/usuarios" clickable v-ripple>
                      <q-item>
                        <q-item-section avatar>
                          <q-icon name="mdi-account" />
                        </q-item-section>
                        <q-item-section> Usuarios </q-item-section>
                      </q-item>
                    </q-item>
                  </q-list>
                </q-menu>
              </q-item-section>
            </q-item>
          </div>
        </q-list>

        <q-list
          padding
          class="text-white"
          :class="drawerMiniState ? '' : 'q-pl-lg'"
        >
          <div>
            <q-item @click="logout" clickable v-ripple>
              <q-item-section avatar>
                <q-icon name="mdi-logout" />
                <q-tooltip
                  v-if="drawerMiniState"
                  anchor="center right"
                  self="center left"
                  :offset="[22, 10]"
                  class="text-caption bg-primary shadow-1"
                >
                  Cerrar sesión
                </q-tooltip>
              </q-item-section>

              <q-item-section style="font-size: 1.5em">
                Cerrar sesión
              </q-item-section>
            </q-item>
          </div>
        </q-list>

        <!-- Menu button (toggles drawer mini-mode) -->
        <div
          v-if="$q.screen.gt.sm && !$q.platform.is.capacitor"
          class="absolute"
          :style="[drawerMiniState ? { top: '70px' } : { top: '101px' }]"
          style="right: -12px"
        >
          <q-btn
            dense
            round
            unelevated
            color="secondary"
            text-color="white-10"
            :icon="drawerMiniState ? 'chevron_right' : 'chevron_left'"
            size="sm"
            @click="toggleMiniState"
          />
        </div>
      </div>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useAuthStore } from "src/stores/auth/auth";
import { useRouter } from "vue-router";
import { useQuasar } from "quasar";

const $router = useRouter();
const $q = useQuasar();
const authStore = useAuthStore();

const name = authStore.nombre;
const role = authStore.showRol;

const session_user_role = authStore.rol;
defineOptions({
  name: "MainLayout",
});

onMounted(() => {});

const leftDrawerOpen = ref(false);
const drawerMiniState = ref(false);

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value;
}

function toggleMiniState() {
  drawerMiniState.value = !drawerMiniState.value;
}

const logout = () => {
  authStore.logout();
  $router.replace("/login");
  $q.notify({
    type: "positive",
    message: "Cerraste sesión correctamente.",
  });
};
</script>
\
<style scoped lang="scss">
.q-item,
.q-expansion-item {
  color: #b6b6b6;
}

.q-item.q-router-link--active,
.q-item--active {
  border-bottom: 1px solid #fff;
  color: #fff;
}
</style>
