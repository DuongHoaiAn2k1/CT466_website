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

  async createAddress(data) {
    return (await this.api.patch("/address", data)).data;
  }

  async deleteAddress(index) {
    return (await this.api.delete(`/address/${index}`)).data;
  }
}

export default new UserService();
