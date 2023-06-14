import { call as fetchMany } from "core/ajax";

const REQUEST_BASE = "mod_gameofknowledge";
const REQUEST_TEST = `${REQUEST_BASE}_test`;
const REQUEST_START_GAME = `${REQUEST_BASE}_start_game`;
const REQUEST_GET_STATE = `${REQUEST_BASE}_get_state`;
const REQUEST_PERFORM_ACTION = `${REQUEST_BASE}_perform_action`;

const requestTest = async (coursemoduleid, args = null) => {
  const request = {
    methodname: REQUEST_TEST,
    args: Object.assign(
      {
        coursemoduleid: coursemoduleid,
      },
      args
    ),
  };

  try {
    return await fetchMany([request])[0];
  } catch (e) {
    console.log(e);
  }
};

const requestStartGame = async (coursemoduleid, args = null) => {
  const request = {
    methodname: "",
    args: Object.assign(
      {
        coursemoduleid: coursemoduleid,
      },
      args
    ),
  };

  try {
    return await fetchMany([request])[0];
  } catch (e) {
    console.log(e);
  }
};
const requestGetState = async (coursemoduleid, args = null) => {
  const request = {
    methodname: "",
    args: Object.assign(
      {
        coursemoduleid: coursemoduleid,
      },
      args
    ),
  };

  try {
    return await fetchMany([request])[0];
  } catch (e) {
    console.log(e);
  }
};
const requestPerformAction = async (coursemoduleid, args = null) => {
  const request = {
    methodname: "",
    args: Object.assign(
      {
        coursemoduleid: coursemoduleid,
      },
      args
    ),
  };

  try {
    return await fetchMany([request])[0];
  } catch (e) {
    console.log(e);
  }
};

export { requestTest, requestStartGame, requestGetState, requestPerformAction };
