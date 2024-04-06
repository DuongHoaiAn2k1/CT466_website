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

  async getProductsWithReviews() {
    return (await this.api.get("/review/list")).data;
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
  async getProductByCondition(data) {
    return (await this.api.post("/condition/list/product", data)).data;
  }
  async updateQuantity(data, id) {
    return (await this.api.patch(`/${id}`, data)).data;
  }
}

export default new ProductService();
