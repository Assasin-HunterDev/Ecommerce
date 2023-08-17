import {AxiosResponse} from "axios";
import axiosClient from "../axios";

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

export function getProducts({commit, state}: any, {
    url = null,
    search = '',
    per_page,
    sort_field,
    sort_direction
}: any = {}) {
    commit("setProducts", [true])
    url = url || "/products";
    const params = {
        per_page: state.products.limit,
    };
    return axiosClient.get(url, {
        params: {
            ...params,
            search, per_page, sort_field, sort_direction
        }
    })
        .then((response) => {
            commit("setProducts", [false, response.data])
        })
        .catch(() => {
            commit("setProducts", [false])
        });
}

export function getProduct({}, id: any) {
    return axiosClient.get(`/products/${id}`);
}

export function createProduct({commit}: any, product: any) {
    if (product.image instanceof File) {
        const form: FormData = new FormData();
        form.append("title", product.title);
        form.append("image", product.image);
        form.append("description", product.description);
        form.append("price", product.price);
        product = form;
    }
    return axiosClient.post("/products", product);
}

export function updateProduct({commit}: any, product: any) {
    const id = product.id;
    if (product.image instanceof File) {
        const form: FormData = new FormData();
        form.append("id", product.id);
        form.append("title", product.title);
        form.append("image", product.image);
        form.append("description", product.description);
        form.append("price", product.price);
        form.append("_method", "PUT");
        product = form;
    } else {
        product._method = "PUT";
    }
    return axiosClient.post(`/products/${id}`, product);
}

export function deleteProduct({commit}: any, id: any) {
    return axiosClient.delete(`/products/${id}`);
}

export function getOrders({commit, state}: any, {
    url = null,
    search = '',
    per_page,
    sort_field,
    sort_direction
}: any = {}) {
    commit("setOrders", [true])
    url = url || "/orders";
    const params = {
        per_page: state.orders.limit,
    };
    return axiosClient.get(url, {
        params: {
            ...params,
            search, per_page, sort_field, sort_direction
        }
    })
        .then((response) => {
            commit("setOrders", [false, response.data])
        })
        .catch(() => {
            commit("setOrders", [false])
        });
}
