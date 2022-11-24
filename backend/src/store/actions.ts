import {AxiosResponse} from "axios";
import axiosClient from "../axios";
import {PRODUCTS_PER_PAGE} from "../constants";

interface GetProductsInterface {
    url: string | null,
    search: String | null,
    perPage: Number,
    sort_field: String,
    sort_direction: String
}

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

export function getProducts({commit}: any, {
    url = null,
    search = "",
    perPage = PRODUCTS_PER_PAGE,
    sort_field,
    sort_direction
}: GetProductsInterface): Promise<void | AxiosResponse> {
    commit("setProducts", [true]);
    url = url || "/product";
    return axiosClient.get(url, {
        params: {
            search,
            per_page: perPage,
            sort_field,
            sort_direction
        }
    })
        .then((response) => {
            commit('setProducts', [false, response.data])
        })
        .catch(() => {
            commit('setProducts', [false])
        });
}
