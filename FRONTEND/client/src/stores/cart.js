import { defineStore } from "pinia";
import cartService from "@/services/cart.service";
export const useCartStore = defineStore("cart", {
  state: () => ({
    totalCart: 0,
  }),

  actions: {
    count() {
      const countCart = async () => {
        try {
          const response = await cartService.count(
            localStorage.getItem("user_id")
          );

          this.totalCart = response.number;
        } catch (error) {
          console.log(error.response);
        }
      };
      countCart();
    },
  },
});
