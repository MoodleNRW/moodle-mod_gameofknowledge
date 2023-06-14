import { createStore } from "vuex";
import {
  requestStartGame,
  requestGetState,
  requestPerformAction,
  requestFinishGame,
} from "@/app/utils/requests";

const store = createStore({
  state() {
    return {
      contextid: null,
      coursemoduleid: null,
      tiles: null,
      sessionPlayerId: null,
      activePlayerId: null,
      winningPlayerId: null,
      players: null,
      playerPositions: null,
      questions: null,
      activeQuestion: null,
      activeQuestionPos: null,
      status: null,
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
    setWinningPlayerId(state, { id }) {
      state.winningPlayerId = id;
    },
    setGameStatus(state, { status }) {
      state.status = status;
    },
    setActiveQuestion(state, { question }) {
      state.activeQuestion = question;
    },
    setActiveQuestionPos(state, { posX, posY }) {
      state.activeQuestionPos = { posX, posY };
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
        commit("setSessionPlayerId", { id: data.player });
        commit("setPlayersData", { players: data.playerlist });
        commit("setPlayerPositionsData", {
          playerPositions: data.playerpositions,
        });
        commit("setGameStatus", { status: data.status });
      }
    },
    async requestFinishGame({ commit, state }) {
      let data = await requestFinishGame(state.coursemoduleid);
      commit("setGameStatus", { status: null });
    },
    async requestGetState({ commit, state }) {
      let data = await requestGetState(state.coursemoduleid);

      if (data) {
        data = JSON.parse(data);
        if (data) {
          commit("setTilesData", { tiles: data.tiles });
          commit("setQuestionsData", { questions: data.questions });
          commit("setActivePlayerId", { id: data.activeplayer });
          commit("setSessionPlayerId", { id: data.player });
          commit("setPlayersData", { players: data.playerlist });
          commit("setPlayerPositionsData", {
            playerPositions: data.playerpositions,
          });
          commit("setGameStatus", { status: data.status });
        }
      }
    },
    async requestPerformAction(
      { commit, dispatch, state },
      { data, posX, posY }
    ) {
      let response = await requestPerformAction(state.coursemoduleid, {
        data,
        posX,
        posY,
      });

      if (response) {
        response = JSON.parse(response);

        commit("setTilesData", { tiles: response.tiles });
        commit("setQuestionsData", { questions: response.questions });
        commit("setActivePlayerId", { id: response.activeplayer });
        commit("setSessionPlayerId", { id: response.player });
        commit("setPlayersData", { players: response.playerlist });
        commit("setPlayerPositionsData", {
          playerPositions: response.playerpositions,
        });
        commit("setGameStatus", { status: response.status });

        await dispatch("handleQuestionResponse", { success: true });
      } else {
        // Something illegal happened
        await dispatch("handleQuestionResponse", { success: false });
      }
    },
    async startGame({ dispatch }) {
      await dispatch("requestStartGame");
    },
    async finishGame({ dispatch }) {
      await dispatch("requestFinishGame");
    },
    async getState({ dispatch }) {
      await dispatch("requestGetState");
    },
    async activateQuestion({ state, commit, dispatch }, { index, posX, posY }) {
      if (index !== null) {
        let question = state.questions[index];

        if (question) {
          commit("setActiveQuestion", { question });
          commit("setActiveQuestionPos", { posX, posY });
        }
      } else {
        // Keep Going
        commit("setActiveQuestionPos", { posX, posY });
        await dispatch("submitQuestion", { data: null });
      }
    },
    async movePlayer({ state, dispatch }, { posX, posY }) {
      await dispatch("activateQuestion", { posX, posY });
    },
    async submitQuestion({ dispatch, state }, { data }) {
      await dispatch("requestPerformAction", {
        data,
        posX: state.activeQuestionPos.posX,
        posY: state.activeQuestionPos.posY,
      });
    },
    async handleQuestionResponse({ commit, state }, { success }) {
      if (success && state.players[state.sessionPlayerId].lastmark) {
        // success
        console.log("Succes");
      } else {
        console.log("Wrong");
      }

      commit("setActiveQuestion", { question: null });
      commit("setActiveQuestionPos", { posX: null, posY: null });
    },
  },
  getters: {
    sessionPlayerPos: (state) => {
      let pos = state.playerPositions[state.sessionPlayerId];
      return { posX: pos.x, posY: pos.y };
    },
    isSessionPlayerTurn: (state) =>
      state.sessionPlayerId == state.activePlayerId,
    isGameInitializing: (state) => state.status == "initializing",
    isGameActive: (state) => state.status == "running",
    isGameFinished: (state) => state.status == "finished",
    isGameError: () => false,
    isQuestionActive: (state) => state.activeQuestion !== null,
    isPlayerPos: (state, getters) => (posX, posY, id) => {
      let player = getters.getPlayerById(id);

      return player.x == posX && player.y == posY;
    },
    isMovementAllowed: (state, getters) => (fieldType, posX, posY) => {
      const playerX = getters.sessionPlayerPos.posX;
      const playerY = getters.sessionPlayerPos.posY;

      return (
        (fieldType == "question" ||
          fieldType == "empty" ||
          fieldType == "goal") &&
        ((posY == playerY && (posX == playerX - 1 || posX == playerX + 1)) ||
          (posX == playerX && (posY == playerY - 1 || posY == playerY + 1)))
      );
    },
    getPlayerById: (state) => (id) => state.players[id],
  },
});

export default store;
