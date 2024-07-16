import { defineStore } from "pinia";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    usuario: null,
    rol: null,
    nombre: null,
    didAutoLogout: false,
    showRol: null,
  }),
  getters: {
    isAuthenticated: (state) => {
      return (
        !!(state.rol && state.usuario) &&
        state.rol !== "" &&
        state.usuario !== ""
      );
    },
  },
  actions: {
    async login(payload) {
      const requestOptions = {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          usuario: payload.username,
          clave: payload.password,
          request: "Login",
        }),
      };

      const response = await fetch(
        process.env.API_URL + "auth/auth.php",
        requestOptions
      );
      if (!response.ok) {
        throw new Error(response.statusText);
      }
      const data = await response.json();

      if (data.error) {
        throw new Error(data.error);
      }

      localStorage.setItem("nombre", data[0].nombre + " " + data[0].apellido);
      localStorage.setItem("usuario", data[0].usuario);
      localStorage.setItem("rol", data[0].rol);

      this.nombre = data[0].nombre + " " + data[0].apellido;
      this.usuario = data[0].usuario;
      this.rol = data[0].rol;

      switch (this.rol) {
        case "admin":
          this.showRol = "Administrador";
          break;
        case "librarian":
          this.showRol = "Bibliotecario";
          break;

        case "guest":
          this.showRol = "Invitado";
          break;
        default:
          this.showRol = null;
          break;
      }
    },

    async loginGuest(payload) {
      const requestOptions = {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          cedula: payload.cedula,
          rol: "guest",
          request: "Login",
        }),
      };

      const response = await fetch(
        process.env.API_URL + "auth/auth.php",
        requestOptions
      );
      if (!response.ok) {
        throw new Error(response.statusText);
      }
      const data = await response.json();

      if (data.error) {
        throw new Error(data.error);
      }

      localStorage.setItem("nombre", data.cedula);
      localStorage.setItem("usuario", data.cedula);
      localStorage.setItem("rol", data.rol);

      this.nombre = data.cedula;
      this.usuario = data.cedula;
      this.rol = data.rol;

      switch (this.rol) {
        case "admin":
          this.showRol = "Administrador";
          break;
        case "librarian":
          this.showRol = "Bibliotecario";
          break;

        case "guest":
          this.showRol = "Invitado";
          break;
        default:
          this.showRol = null;
          break;
      }
    },

    tryLogin() {
      // Get data from localStorage
      const name = localStorage.getItem("nombre");
      const username = localStorage.getItem("usuario");
      const userRole = localStorage.getItem("rol");

      if (username && userRole && userRole !== "" && username !== "") {
        this.nombre = name;
        this.usuario = username;
        this.rol = userRole;

        switch (this.rol) {
          case "admin":
            this.showRol = "Administrador";
            break;
          case "librarian":
            this.showRol = "Bibliotecario";
            break;

          case "guest":
            this.showRol = "Invitado";
            break;
          default:
            this.showRol = null;
            break;
        }
      } else {
        this.logout();
        this.didAutoLogout = true;
      }
    },

    logout() {
      // Reset data
      this.nombre = null;
      this.usuario = null;
      this.rol = null;
      this.didAutoLogout = false;

      this.showRol = null;

      // Remove token and user data from the browser's local storage
      localStorage.removeItem("usuario");
      localStorage.removeItem("nombre");
      localStorage.removeItem("rol");
    },
  },
});
