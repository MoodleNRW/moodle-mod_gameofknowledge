import { createStore } from "vuex";
import {
  requestTest,
  requestStartGame,
  requestGetState,
  requestPerformAction,
} from "@/app/utils/requests";

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
    async requestTest({ commit, state }) {
      let data = await requestTest(state.coursemoduleid);
      console.log(data);
    },
    async requestStartGame({ commit, state }) {
      let data = await requestStartGame(state.coursemoduleid);
      console.log(data);
    },
    async requestGetState({ commit, state }) {
      let data = await requestGetState(state.coursemoduleid);
      console.log(data);
    },
    async requestPerformAction({ commit, state }) {
      let data = await requestPerformAction(state.coursemoduleid);
      console.log(data);
    },
  },
  getters: {
    isGameActive: () => false,
  },
});

export default store;
