import { createStore } from "vuex";
import {
  requestStartGame,
  requestGetState,
  requestPerformAction,
} from "@/app/utils/requests";

const store = createStore({
  state() {
    return {
      contextid: null,
      coursemoduleid: null,
      tiles: null,
      sessionPlayerId: null,
      activePlayerId: null,
      players: null,
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
    setTilesData(state, { tiles }) {
      state.tiles = tiles;
    },
    setPlayersData(state, { players }) {
      state.players = players;
    },
    setSessionPlayerId(state, { id }) {
      state.sessionPlayerId = id;
    },
    setActivePlayerId(state, { id }) {
      state.activePlayerId = id;
    },
  },
  actions: {
    async requestStartGame({ commit, state }) {
      let data = await requestStartGame(state.coursemoduleid);
      commit("setGameData", { gameData: [] });
      console.log(data);
    },
    async requestGetState({ commit, state }) {
      let data = await requestGetState(state.coursemoduleid);

      if (data) {
        data = JSON.parse(data);
        commit("setTilesData", { tiles: data.tiles });
        commit("setActivePlayerId", { id: data.activeplayer });
        commit("setPlayersData", { players: data.playerlist });
      }

      console.log(data);
    },
    async requestPerformAction({ commit, state }) {
      let data = await requestPerformAction(state.coursemoduleid);
      console.log(data);
    },
    async startGame({ commit, dispatch }) {
      await dispatch("requestStartGame");
      await dispatch("requestGetState");
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
        (fieldType == "question" || fieldType == "empty" || fieldType == "goal") &&
        ((posY == playerY && (posX == playerX - 1 || posX == playerX + 1)) ||
          (posX == playerX && (posY == playerY - 1 || posY == playerY + 1)))
      );
    },
  },
});

export default store;
