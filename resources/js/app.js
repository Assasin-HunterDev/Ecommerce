import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'
import { post } from "./http.js";

Alpine.plugin(collapse)

window.Alpine = Alpine;

document.addEventListener("alpine:init", async () => {

    Alpine.data("toast", () => ({
        visible: false,
        delay: 5000,
        percent: 0,
        interval: null,
        timeout: null,
        message: null,
        close() {
            this.visible = false;
            clearInterval(this.interval);
        },
        show(message) {
            this.visible = true;
            this.message = message;

            if (this.interval) {
                clearInterval(this.interval);
                this.interval = null;
            }
            if (this.timeout) {
                clearTimeout(this.timeout);
                this.timeout = null;
            }

            this.timeout = setTimeout(() => {
                this.visible = false;
                this.timeout = null;
            }, this.delay);
            const startDate = Date.now();
            const futureDate = Date.now() + this.delay;
            this.interval = setInterval(() => {
                const date = Date.now();
                this.percent = ((date - startDate) * 100) / (futureDate - startDate);
                if (this.percent >= 100) {
                    clearInterval(this.interval);
                    this.interval = null;
                }
            }, 30);
        },
    }));

    Alpine.data("productItem", (product) => {
        return {
            product,
            addToCart(quantity = 1) {
                post(this.product.addToCartUrl, {quantity})
                    .then(result => {
                        this.$dispatch('cart-change', {count: result.count})
                        this.$dispatch("notify", {
                            message: "The item was added into the cart",
                        });
                    })
                    .catch(response => console.log(response));
            },
            removeItemFromCart() {
                post(this.product.removeUrl)
                    .then(result => {
                        this.$dispatch("notify", {
                            message: "The item was removed from cart",
                        });
                        this.$dispatch('cart-change', {count: result.count})
                        this.cartItems = this.cartItems.filter(p => p.id !== product.id)
                    })
                    .catch(response => console.log(response));
            },
            removeAllItemsFromCart() {
                Promise.all(this.cartItems.map(product =>
                    post(product.removeUrl)
                        .catch(response => console.log(response))
                ))
                    .then(() => {
                        this.$dispatch("notify", {
                            message: "All items were removed from cart",
                        });
                        this.cartItems = [];
                    });
            },
            changeQuantity() {
                post(this.product.updateQuantityUrl, {quantity: product.quantity})
                    .then(result => {
                        this.$dispatch('cart-change', {count: result.count})
                        this.$dispatch("notify", {
                            message: "The item quantity was updated",
                        });
                    })
                    .catch(response => console.log(response));
            },
            validateQuantityInput(event) {
                const inputEl = event.target;
                const inputValue = parseInt(inputEl.value);

                if (isNaN(inputValue) || inputValue < 1) inputEl.value = 1;
            },
            changeQuantityInputWidthStyle() {
                const minWidth = 3; // Minimum width in em units
                const maxLength = 17; // Maximum length before stopping width increase
                const length = product.quantity.toString().length;
                const width = Math.min(length + 1, maxLength) + minWidth;
                return `width: ${width}em`;
            },
        };
    });
});

Alpine.start();
