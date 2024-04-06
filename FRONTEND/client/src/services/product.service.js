import createApiClient from "./api.service";

class ProductService {
  constructor(baseUrl = "/api/product") {
    this.api = createApiClient(baseUrl);
  }

  async create(data) {
    return (await this.api.post("/", data)).data;
  }

  async getAll() {
    return (await this.api.get("/")).data;
  }

  async getGroupByCategory() {
    return (await this.api.get("/category/group")).data;
  }

  async getProductFromCategoryName(data) {
    return (await this.api.post("/name", data)).data;
  }

  async getProductFromCategoryId(id) {
    return (await this.api.get(`/category/${id}`)).data;
  }

  async get(id) {
    return (await this.api.get(`/${id}`)).data;
  }

  async update(id, data) {
    return (await this.api.post(`/${id}`, data)).data;
  }
  async delete(id) {
    return (await this.api.delete(`${id}`)).data;
  }

  async increaseView(id) {
    return (await this.api.post(`/increase/view/${id}`)).data;
  }

  async getProductByName(data) {
    return (await this.api.post(`/get/name/list`, data)).data;
  }

  async decreaseProductQuantity(data) {
    return (await this.api.post("/decrease/product/quantity", data)).data;
  }
  async increaseProductQuantity(data) {
    return (await this.api.post("/increase/product/quantity", data)).data;
  }
}

export default new ProductService();
