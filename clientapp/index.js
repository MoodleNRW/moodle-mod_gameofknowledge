import { createApp } from "vue";
import App from "@/app/main";
import Store from "@/app/store";

const init = () => {
  const app = createApp(App);
  app.use(Store);
  app.mount("#mod-gameofknowledge-app");
};

export { init };
