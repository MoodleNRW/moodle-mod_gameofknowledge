import { createApp } from "vue";
import App from "@/app/main";

const init = () => {
  const app = createApp(App);
  app.mount("#mod-gameofknowledge-app");
};

export { init };
