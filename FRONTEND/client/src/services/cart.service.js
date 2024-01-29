import createApiClient from "./api.service";

class CartService {
  constructor(baseUrl = "/api/cart") {
    this.api = createApiClient(baseUrl);
  }

  async get(id) {
    return (await this.api.get(`/${id}`)).data;
  }
  async create(data) {
    return (await this.api.post("/", data)).data;
  }

  async delete(id) {
    return (await this.api.delete(`${id}`)).data;
  }
  async decrease(id) {
    return (await this.api.patch(`/decrease/${id}`)).data;
  }
  async increase(id) {
    return (await this.api.patch(`/increase/${id}`)).data;
  }
  async count(id) {
    return (await this.api.get(`/count/${id}`)).data;
  }
}

export default new CartService();
