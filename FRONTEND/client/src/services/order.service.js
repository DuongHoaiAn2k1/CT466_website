import createApiClient from "./api.service";

class OrderService {
  constructor(baseUrl = "/api/order") {
    this.api = createApiClient(baseUrl);
  }

  async getAll() {
    return (await this.api.get("/getAll")).data;
  }

  async create(data) {
    return (await this.api.post("/", data)).data;
  }
  async delete() {
    return (await this.api.delete("/")).data;
  }
  async count() {
    return (await this.api.get("/count")).data;
  }
}

export default new OrderService();
