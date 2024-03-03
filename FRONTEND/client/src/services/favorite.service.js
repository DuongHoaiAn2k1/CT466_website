import createApiClient from "./api.service";

class FavoriteService {
  constructor(baseUrl = "/api/favorite") {
    this.api = createApiClient(baseUrl);
  }

  async create(data) {
    return (await this.api.post("/", data)).data;
  }

  async getByUser() {
    return (await this.api.get("/")).data;
  }

  async delete(id) {
    return (await this.api.delete(`${id}`)).data;
  }
}

export default new FavoriteService();
