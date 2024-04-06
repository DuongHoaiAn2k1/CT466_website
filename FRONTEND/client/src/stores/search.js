import { defineStore } from "pinia";

export const useSearchStore = defineStore("search", {
  state: () => ({
    dataSearch: "",
  }),

  actions: {
    setDataSearch(data) {
      this.dataSearch = data;
    },

    searchHandle() {
      alert(this.dataSearch);
    },

    deleteDataSearch() {
      this.dataSearch = "";
    },
  },
});
