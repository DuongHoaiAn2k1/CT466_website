import { defineStore } from "pinia";
export const useFavoriteStore = defineStore("favorite", {
  state: () => ({
    listFavorite: [],
  }),

  actions: {
    setFavorite(data) {
      this.listFavorite = [...data];
    },

    showListFavorite() {
      console.log("List favorite: ", this.listFavorite);
    },
    getListFavorite() {
      return this.listFavorite;
    },
  },
});
