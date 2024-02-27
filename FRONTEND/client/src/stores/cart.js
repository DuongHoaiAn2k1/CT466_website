import { defineStore } from "pinia";
import cartService from "@/services/cart.service";
export const useCartStore = defineStore("cart", {
  state: () => ({
    addressToPay: 0,
    count: 0,
  }),

  actions: {
    setAddress(index) {
      this.addressToPay = index;
    },
    async fetchCartCount() {
      try {
        const response = await cartService.count();
        this.count = response.number;
      } catch (error) {
        console.error("Failed to fetch cart count:", error);
      }
    },
    deleteCart() {
      this.count = 0;
    },
  },
});
