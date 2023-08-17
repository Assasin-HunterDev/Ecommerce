interface StateInterface {
    user: {
        token: String | null;
        data: {};
    },
    products: {
        loading: boolean,
        data: [];
        links: [];
        from: null;
        to: null;
        page: 1;
        limit: null;
        total: null;
    },
    orders: {
        loading: boolean,
        data: [];
        links: [];
        from: null;
        to: null;
        page: 1;
        limit: null;
        total: null;
    };
}

const state: StateInterface = {
    user: {
        token: sessionStorage.getItem("TOKEN"),
        data: {}
    },
    products: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null,
    },
    orders: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null,
    },
};

export default state;
