import createApiClient from "./api.service";

class OrderDetailService {
  constructor(baseUrl = "/api/orderDetail") {
    this.api = createApiClient(baseUrl);
  }

  async get(id) {
    return (await this.api.get(`/${id}`)).data;
  }

  async create(data) {
    return (await this.api.post("/", data)).data;
  }

  async getStatistics() {
    return (await this.api.get("/statistics/sales")).data;
  }
}

export default new OrderDetailService();
