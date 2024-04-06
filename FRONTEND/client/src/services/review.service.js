import createApiClient from "./api.service";

class ReviewService {
  constructor(baseUrl = "/api/review") {
    this.api = createApiClient(baseUrl);
  }

  async create(data) {
    return (await this.api.post("/", data)).data;
  }

  async getAll() {
    return (await this.api.get("/")).data;
  }

  async getByProduct(productId) {
    return (await this.api.get(`/${productId}`)).data;
  }

  async delete(id) {
    return (await this.api.delete(`${id}`)).data;
  }
  async userHasReviewedProduct(id) {
    return (await this.api.post(`/check/${id}`)).data;
  }
}

export default new ReviewService();
