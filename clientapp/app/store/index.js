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
      gameData: null,
      playerState: {
        posX: 0,
        posY: 0,
      },
    };
  },
  mutations: {
    setContextId(state, { contextid }) {
      state.contextid = contextid;
    },
    setCourseModuleId(state, { coursemoduleid }) {
      state.coursemoduleid = coursemoduleid;
    },
    setGameData(state, { gameData }) {
      state.gameData = gameData;
    },
  },
  actions: {
    async requestTest({ commit, state }) {
      let data = await requestTest(state.coursemoduleid);
      console.log(data);
    },
    async requestStartGame({ commit, state }) {
      let data = await requestStartGame(state.coursemoduleid);
      commit("setGameData", { gameData: [] });
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
    movePlayer({ state }, { posX, posY }) {
      state.playerState.posX = posX;
      state.playerState.posY = posY;
    },
  },
  getters: {
    isGameActive: (state) => state.gameData !== null,
    isGameError: () => false,
    isQuestionActive: (state) => false,
    isPlayerPos: (state) => (posX, posY) =>
      state.playerState.posX == posX && state.playerState.posY == posY,
    isMovementAllowed: (state) => (fieldType, posX, posY) => {
      const playerX = state.playerState.posX;
      const playerY = state.playerState.posY;

      return (
        fieldType == "question" &&
        ((posY == playerY && (posX == playerX - 1 || posX == playerX + 1)) ||
          (posX == playerX && (posY == playerY - 1 || posY == playerY + 1)))
      );
    },
  },
});

export default store;
