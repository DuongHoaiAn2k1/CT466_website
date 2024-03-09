import createApiClient from "./api.service";

class OrderService {
  constructor(baseUrl = "/api/order") {
    this.api = createApiClient(baseUrl);
  }

  async get(id) {
    return (await this.api.get(`/${id}`)).data;
  }
  async getByUser() {
    return (await this.api.get("/user/get")).data;
  }
  async getByUserId(id) {
    return (await this.api.get(`/user/${id}`)).data;
  }
  async getAll() {
    return (await this.api.get("/get/all")).data;
  }

  async create(data) {
    return (await this.api.post("/", data)).data;
  }
  async delete(id) {
    return (await this.api.delete(`/${id}`)).data;
  }
  async count() {
    return (await this.api.get("/count/order")).data;
  }
  async cancel(id) {
    return (await this.api.patch(`/${id}`)).data;
  }

  async updateStatus(data, id) {
    return (await this.api.patch(`/update/${id}`, data)).data;
  }

  async getToday() {
    return (await this.api.get("/today/all")).data;
  }
  async getByDate(data) {
    return (await this.api.post("/bydate/all", data)).data;
  }
}

export default new OrderService();
