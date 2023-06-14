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
      playerPositions: null,
      questions: null,
      status: null,
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
    setQuestionsData(state, { questions }) {
      state.questions = questions;
    },
    setPlayersData(state, { players }) {
      state.players = players;
    },
    setPlayerPositionsData(state, { playerPositions }) {
      state.playerPositions = playerPositions;
    },
    setSessionPlayerId(state, { id }) {
      state.sessionPlayerId = id;
    },
    setActivePlayerId(state, { id }) {
      state.activePlayerId = id;
    },
    setGameStatus(state, { status }) {
      state.status = status;
    },
  },
  actions: {
    async requestStartGame({ commit, state }) {
      let data = await requestStartGame(state.coursemoduleid);

      if (data) {
        data = JSON.parse(data);
        commit("setTilesData", { tiles: data.tiles });
        commit("setQuestionsData", { questions: data.questions });
        commit("setActivePlayerId", { id: data.activeplayer });
        commit("setPlayersData", { players: data.playerlist });
        commit("setPlayerPositionsData", { playerPositions: data.playerpositions });
        commit("setGameStatus", { status: data.status });
      }

      console.log(data);
    },
    async requestGetState({ commit, state }) {
      let data = await requestGetState(state.coursemoduleid);

      if (data) {
        data = JSON.parse(data);
        commit("setTilesData", { tiles: data.tiles });
        commit("setQuestionsData", { questions: data.questions });
        commit("setActivePlayerId", { id: data.activeplayer });
        commit("setPlayersData", { players: data.playerlist });
        commit("setPlayerPositionsData", { playerPositions: data.playerpositions });
        commit("setGameStatus", { status: data.status });
      }

      console.log(data);
    },
    async requestPerformAction({ commit, state }) {
      let data = await requestPerformAction(state.coursemoduleid);
      console.log(data);
    },
    async startGame({ dispatch }) {
      await dispatch("requestStartGame");
    },
    async getState({ dispatch }) {
      await dispatch("requestGetState");
    },
    movePlayer({ state }, { posX, posY }) {
      state.playerState.posX = posX;
      state.playerState.posY = posY;
    },
  },
  getters: {
    isGameActive: (state) => state.status == "initializing",
    isGameError: () => false,
    isQuestionActive: (state) => false,
    isPlayerPos: (state) => (posX, posY) =>
      state.playerState.posX == posX && state.playerState.posY == posY,
    isMovementAllowed: (state) => (fieldType, posX, posY) => {
      const playerX = state.playerState.posX;
      const playerY = state.playerState.posY;

      return (
        (fieldType == "question" ||
          fieldType == "empty" ||
          fieldType == "goal") &&
        ((posY == playerY && (posX == playerX - 1 || posX == playerX + 1)) ||
          (posX == playerX && (posY == playerY - 1 || posY == playerY + 1)))
      );
    },
  },
});

export default store;
