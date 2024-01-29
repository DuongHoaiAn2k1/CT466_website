import createApiClient from "./api.service";

class AuthService {
  constructor(baseUrl = "/api/auth") {
    this.api = createApiClient(baseUrl);
  }

  async login(data) {
    return (await this.api.post("/login", data)).data;
  }

  async refresh(data) {
    return (await this.api.post("/refresh", data)).data;
  }

  async logout() {
    return (await this.api.post("/logout")).data;
  }
}

export default new AuthService();
