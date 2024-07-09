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
                <q-item-label>Mary</q-item-label>
                <q-item-label class="text-subtitle1 text-grey"
                  >Administrador</q-item-label
                >
              </q-item-section>
            </q-item>
          </q-item-label>

          <q-list padding class="text-white q-pl-lg">
            <div>
              <q-item to="/libros" clickable v-ripple>
                <q-item-section avatar>
                  <q-icon name="book" />
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

              <q-item to="/libros" clickable v-ripple>
                <q-item-section avatar>
                  <q-icon name="book" />
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

              <q-item to="/libros" clickable v-ripple>
                <q-item-section avatar>
                  <q-icon name="book" />
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

              <q-item to="/libros" clickable v-ripple>
                <q-item-section avatar>
                  <q-icon name="book" />
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

              <q-item to="/libros" clickable v-ripple>
                <q-item-section avatar>
                  <q-icon name="book" />
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

              <q-item to="/libros" clickable v-ripple>
                <q-item-section avatar>
                  <q-icon name="book" />
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

              <q-item to="/libros" clickable v-ripple>
                <q-item-section avatar>
                  <q-icon name="book" />
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
            </div>
          </q-list>
        </div>

        <q-list padding class="text-white q-pl-lg">
          <div>
            <q-item to="/libros" clickable v-ripple>
              <q-item-section avatar>
                <q-icon name="book" />
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

              <q-item-section style="font-size: 1.5em"> Libros </q-item-section>
            </q-item>

            <q-item to="/libros" clickable v-ripple>
              <q-item-section avatar>
                <q-icon name="book" />
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

              <q-item-section style="font-size: 1.5em"> Libros </q-item-section>
            </q-item>
          </div>
        </q-list>

        <q-list padding class="text-white q-pl-lg">
          <div>
            <q-item to="/libros" clickable v-ripple>
              <q-item-section avatar>
                <q-icon name="book" />
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

              <q-item-section style="font-size: 1.5em"> Libros </q-item-section>
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
            color="accent"
            text-color="grey-10"
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
import { ref } from "vue";
import EssentialLink from "components/EssentialLink.vue";

defineOptions({
  name: "MainLayout",
});

const linksList = [
  {
    title: "Libros",

    icon: "book",
    to: "/libros",
  },
  {
    title: "Github",
    caption: "github.com/quasarframework",
    icon: "code",
    link: "https://github.com/quasarframework",
  },
  {
    title: "Discord Chat Channel",
    caption: "chat.quasar.dev",
    icon: "chat",
    link: "https://chat.quasar.dev",
  },
  {
    title: "Forum",
    caption: "forum.quasar.dev",
    icon: "record_voice_over",
    link: "https://forum.quasar.dev",
  },
  {
    title: "Twitter",
    caption: "@quasarframework",
    icon: "rss_feed",
    link: "https://twitter.quasar.dev",
  },
  {
    title: "Facebook",
    caption: "@QuasarFramework",
    icon: "public",
    link: "https://facebook.quasar.dev",
  },
  {
    title: "Quasar Awesome",
    caption: "Community Quasar projects",
    icon: "favorite",
    link: "https://awesome.quasar.dev",
  },
];

const leftDrawerOpen = ref(false);
const drawerMiniState = ref(false);

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value;
}

function toggleMiniState() {
  drawerMiniState.value = !drawerMiniState.value;
}
</script>
