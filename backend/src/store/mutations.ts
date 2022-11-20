export function setUser(state: any, user: any) {
    state.user.data = user;
}

export function setToken(state: any, token: any) {
    state.user.token = token;
    if (token) {
        sessionStorage.setItem("TOKEN", token);
    } else {
        sessionStorage.removeItem("TOKEN");
    }
}
