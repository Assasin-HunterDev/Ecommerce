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

export function setProducts(state: any, [loading, response = null]: any) {
    if (response) {
        state.products = {
            data: response.data,
            links: response.meta.links,
            from: response.meta.from,
            to: response.meta.to,
            page: response.meta.current_page,
            limit: response.meta.per_page,
            total: response.meta.total
        }
    }
    state.products.loading = loading;
}

export function setOrders(state: any, [loading, response = null]: any) {
    if (response) {
        state.orders = {
            data: response.data,
            links: response.meta.links,
            from: response.meta.from,
            to: response.meta.to,
            page: response.meta.current_page,
            limit: response.meta.per_page,
            total: response.meta.total
        }
    }
    state.orders.loading = loading;
}
