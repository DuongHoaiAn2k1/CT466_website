import createApiClient from "./api.service.js";

class UserService {
  constructor(baseUrl = "/api/user") {
    this.api = createApiClient(baseUrl);
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
  async deleteUser(id) {
    return (await this.api.delete(`${id}`)).data;
  }

  async filterUser(data) {
    return (await this.api.post("/filter/users", data)).data;
  }
}

export default new UserService();
