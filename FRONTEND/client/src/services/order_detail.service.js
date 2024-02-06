import createApiClient from "./api.service";

class OrderDetailService {
  constructor(baseUrl = "/api/orderDetail") {
    this.api = createApiClient(baseUrl);
  }

  async get() {
    return (await this.api.get("")).data;
  }

  async create(data) {
    return (await this.api.post("/", data)).data;
  }
}

export default new OrderDetailService();
