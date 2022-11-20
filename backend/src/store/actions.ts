import axiosClient from "../axios";
import {AxiosResponse} from "axios";

export function getUser({commit}: any): Promise<AxiosResponse> {
    return axiosClient.get("/user")
        .then(({data}) => {
            commit("setUser", data);
            return data;
        });
}

export function login({commit}: any, data: any): Promise<any> {
    return axiosClient.post("/login", data)
        .then(({data}) => {
            commit("setUser", data.user);
            commit("setToken", data.token);
            return data;
        });
}

export function logout({commit}: any): Promise<AxiosResponse> {
    return axiosClient.post("/logout")
        .then((response) => {
            commit("setToken", null);
            return response;
        });
}
