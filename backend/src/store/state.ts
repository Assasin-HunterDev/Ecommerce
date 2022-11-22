interface stateInterface {
    user: {
        token: String | null,
        data: {}
    }
}

const state: stateInterface = {
    user: {
        token: sessionStorage.getItem("TOKEN"),
        data: {}
    },
};

export default state;
