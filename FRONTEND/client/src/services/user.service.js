import createApiClient from "./api.service.js";

class UserService {
  constructor(baseUrl = "/api/user") {
    this.api = createApiClient(baseUrl);
  }
  async get(id) {
    return (await this.api.get(`/${id}`)).data;
  }
  async getAll() {
    return (await this.api.get("/")).data;
  }
  async register(data) {
    return (await this.api.post("/register", data)).data;
  }
  async login(data) {
    return (await this.api.post("/login", data)).data;
  }

  async update(data) {
    return (await this.api.patch("/update", data)).data;
  }

  async updatePass(data) {
    return (await this.api.patch("/password", data)).data;
  }

  async createAddress(data) {
    return (await this.api.patch("/address", data)).data;
  }

  async deleteAddress(index) {
    return (await this.api.delete(`/address/${index}`)).data;
  }
  async pointIncrement() {
    return (await this.api.patch("/point/increase")).data;
  }
  async pointDecrement(data) {
    return (await this.api.patch("/point/decrease", data)).data;
  }
  async getCurrentPoint() {
    return (await this.api.get("/point/get")).data;
  }
  async restorePointPaid(data) {
    return (await this.api.patch("/point/restore", data)).data;
  }
}

export default new UserService();
