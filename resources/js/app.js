import "./bootstrap";

import App from "./components/App.vue";
import { createApp } from "vue";
import router from "./router";
import store from "./store";
import axios from "axios";

const app = createApp(App);
app.config.globalProperties.$axios = axios;
app.use(router);
app.use(store);
app.mount("#app");
