import { createStore } from "vuex";
import { requestGameStart } from "@/app/utils/requests";

const store = createStore({
  state() {
    return {
      contextid: null,
      coursemoduleid: null,
    };
  },
  mutations: {
    setContextId(state, { contextid }) {
      state.contextid = contextid;
    },
    setCourseModuleId(state, { coursemoduleid }) {
      state.coursemoduleid = coursemoduleid;
    },
  },
  actions: {
    async requestGameStart({ commit, state }) {
      let data = await requestGameStart(state.coursemoduleid);
    },
  },
  getters: {
    isGameActive: () => false,
  },
});

export default store;
