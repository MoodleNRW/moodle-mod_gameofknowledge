import { createApp } from "vue";
import App from "@/app/main";
import Store from "@/app/store";

const init = (coursemoduleid, contextid) => {
  const app = createApp(App, { coursemoduleid, contextid });
  app.use(Store);
  app.mount("#mod-gameofknowledge-app");
};

export { init };
