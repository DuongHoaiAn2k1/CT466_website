<template>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <h1 class="mt-4">THỐNG KÊ</h1>

        <div class="row">
          <div class="col-xl-3 col-md-6" @click="showDetail('customer')">
            <div class="card bg-dark text-white mb-4">
              <div class="card-body">Khách hàng</div>
              <div
                class="card-footer d-flex align-items-center justify-content-between"
              >
                <a class="small text-white stretched-link" href="#"
                  >Xem chi tiết</a
                >
                <div class="small text-white">
                  <i class="fas fa-angle-right"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6" @click="showDetail('product')">
            <div class="card bg-dark text-white mb-4">
              <div class="card-body">Sản phẩm</div>
              <div
                class="card-footer d-flex align-items-center justify-content-between"
              >
                <a class="small text-white stretched-link" href="#"
                  >Xem chi tiết</a
                >
                <div class="small text-white">
                  <i class="fas fa-angle-right"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6" @click="showDetail('order')">
            <div class="card bg-dark text-white mb-4">
              <div class="card-body">Đơn hàng</div>
              <div
                class="card-footer d-flex align-items-center justify-content-between"
              >
                <a class="small text-white stretched-link" href="#"
                  >Xem chi tiết</a
                >
                <div class="small text-white">
                  <i class="fas fa-angle-right"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6" @click="showDetail('income')">
            <div class="card bg-dark text-white mb-4">
              <div class="card-body">Doanh Thu</div>
              <div
                class="card-footer d-flex align-items-center justify-content-between"
              >
                <a class="small text-white stretched-link" href="#"
                  >Xem chi tiết</a
                >
                <div class="small text-white">
                  <i class="fas fa-angle-right"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Customer View -->
        <div class="card mb-4" v-show="showCustomer">
          <el-row class="mx-2">
            <el-col :span="6">
              <el-statistic title="Tổng số khách hàng" :value="268500" />
            </el-col>
            <el-col :span="6">
              <el-statistic :value="138">
                <template #title>
                  <div style="display: inline-flex; align-items: center">
                    Ratio of men to women
                    <el-icon style="margin-left: 4px" :size="12">
                      <Male />
                    </el-icon>
                  </div>
                </template>
                <template #suffix>/100</template>
              </el-statistic>
            </el-col>
            <el-col :span="6">
              <el-statistic title="Total Transactions" :value="outputValue" />
            </el-col>
            <el-col :span="6">
              <el-statistic title="Feedback number" :value="562">
                <template #suffix>
                  <el-icon style="vertical-align: -0.125em">
                    <ChatLineRound />
                  </el-icon>
                </template>
              </el-statistic>
            </el-col>
          </el-row>
        </div>
        <!-- Product View -->
        <div class="card mb-4" v-show="showProduct">
          <el-row :gutter="16">
            <el-col :span="8">
              <div class="statistic-card">
                <el-statistic :value="98500">
                  <template #title>
                    <div style="display: inline-flex; align-items: center">
                      Daily active users
                      <el-tooltip
                        effect="dark"
                        content="Number of users who logged into the product in one day"
                        placement="top"
                      >
                        <el-icon style="margin-left: 4px" :size="12">
                          <Warning />
                        </el-icon>
                      </el-tooltip>
                    </div>
                  </template>
                </el-statistic>
                <div class="statistic-footer">
                  <div class="footer-item">
                    <span>than yesterday</span>
                    <span class="green">
                      24%
                      <el-icon>
                        <CaretTop />
                      </el-icon>
                    </span>
                  </div>
                </div>
              </div>
            </el-col>
            <el-col :span="8">
              <div class="statistic-card">
                <el-statistic :value="693700">
                  <template #title>
                    <div style="display: inline-flex; align-items: center">
                      Monthly Active Users
                      <el-tooltip
                        effect="dark"
                        content="Number of users who logged into the product in one month"
                        placement="top"
                      >
                        <el-icon style="margin-left: 4px" :size="12">
                          <Warning />
                        </el-icon>
                      </el-tooltip>
                    </div>
                  </template>
                </el-statistic>
                <div class="statistic-footer">
                  <div class="footer-item">
                    <span>month on month</span>
                    <span class="red">
                      12%
                      <el-icon>
                        <CaretBottom />
                      </el-icon>
                    </span>
                  </div>
                </div>
              </div>
            </el-col>
            <el-col :span="8">
              <div class="statistic-card">
                <el-statistic :value="72000" title="New transactions today">
                  <template #title>
                    <div style="display: inline-flex; align-items: center">
                      New transactions today
                    </div>
                  </template>
                </el-statistic>
                <div class="statistic-footer">
                  <div class="footer-item">
                    <span>than yesterday</span>
                    <span class="green">
                      16%
                      <el-icon>
                        <CaretTop />
                      </el-icon>
                    </span>
                  </div>
                </div>
              </div>
            </el-col>
          </el-row>
        </div>

        <!-- Orders View -->
        <div class="card mb-4" v-show="showOrder">
          <el-row :gutter="16">
            <el-col :span="8">
              <div class="statistic-card">
                <el-statistic :value="98500">
                  <template #title>
                    <div style="display: inline-flex; align-items: center">
                      Daily active users
                      <el-tooltip
                        effect="dark"
                        content="Number of users who logged into the product in one day"
                        placement="top"
                      >
                        <el-icon style="margin-left: 4px" :size="12">
                          <Warning />
                        </el-icon>
                      </el-tooltip>
                    </div>
                  </template>
                </el-statistic>
                <div class="statistic-footer">
                  <div class="footer-item">
                    <span>than yesterday</span>
                    <span class="green">
                      24%
                      <el-icon>
                        <CaretTop />
                      </el-icon>
                    </span>
                  </div>
                </div>
              </div>
            </el-col>
            <el-col :span="8">
              <div class="statistic-card">
                <el-statistic :value="693700">
                  <template #title>
                    <div style="display: inline-flex; align-items: center">
                      Monthly Active Users
                      <el-tooltip
                        effect="dark"
                        content="Number of users who logged into the product in one month"
                        placement="top"
                      >
                        <el-icon style="margin-left: 4px" :size="12">
                          <Warning />
                        </el-icon>
                      </el-tooltip>
                    </div>
                  </template>
                </el-statistic>
                <div class="statistic-footer">
                  <div class="footer-item">
                    <span>month on month</span>
                    <span class="red">
                      12%
                      <el-icon>
                        <CaretBottom />
                      </el-icon>
                    </span>
                  </div>
                </div>
              </div>
            </el-col>
            <el-col :span="8">
              <div class="statistic-card">
                <el-statistic :value="72000" title="New transactions today">
                  <template #title>
                    <div style="display: inline-flex; align-items: center">
                      New transactions today
                    </div>
                  </template>
                </el-statistic>
                <div class="statistic-footer">
                  <div class="footer-item">
                    <span>than yesterday</span>
                    <span class="green">
                      16%
                      <el-icon>
                        <CaretTop />
                      </el-icon>
                    </span>
                  </div>
                </div>
              </div>
            </el-col>
          </el-row>
        </div>

        <!-- Income View -->
        <div class="card mb-4" v-show="showIncome">
          <el-row :gutter="16">
            <el-col :span="8">
              <div class="statistic-card">
                <el-statistic :value="98500">
                  <template #title>
                    <div style="display: inline-flex; align-items: center">
                      Daily active users
                      <el-tooltip
                        effect="dark"
                        content="Number of users who logged into the product in one day"
                        placement="top"
                      >
                        <el-icon style="margin-left: 4px" :size="12">
                          <Warning />
                        </el-icon>
                      </el-tooltip>
                    </div>
                  </template>
                </el-statistic>
                <div class="statistic-footer">
                  <div class="footer-item">
                    <span>than yesterday</span>
                    <span class="green">
                      24%
                      <el-icon>
                        <CaretTop />
                      </el-icon>
                    </span>
                  </div>
                </div>
              </div>
            </el-col>
            <el-col :span="8">
              <div class="statistic-card">
                <el-statistic :value="693700">
                  <template #title>
                    <div style="display: inline-flex; align-items: center">
                      Monthly Active Users
                      <el-tooltip
                        effect="dark"
                        content="Number of users who logged into the product in one month"
                        placement="top"
                      >
                        <el-icon style="margin-left: 4px" :size="12">
                          <Warning />
                        </el-icon>
                      </el-tooltip>
                    </div>
                  </template>
                </el-statistic>
                <div class="statistic-footer">
                  <div class="footer-item">
                    <span>month on month</span>
                    <span class="red">
                      12%
                      <el-icon>
                        <CaretBottom />
                      </el-icon>
                    </span>
                  </div>
                </div>
              </div>
            </el-col>
            <el-col :span="8">
              <div class="statistic-card">
                <el-statistic :value="72000" title="New transactions today">
                  <template #title>
                    <div style="display: inline-flex; align-items: center">
                      New transactions today
                    </div>
                  </template>
                </el-statistic>
                <div class="statistic-footer">
                  <div class="footer-item">
                    <span>than yesterday</span>
                    <span class="green">
                      16%
                      <el-icon>
                        <CaretTop />
                      </el-icon>
                    </span>
                  </div>
                </div>
              </div>
            </el-col>
          </el-row>
        </div>
      </div>
    </main>
  </div>
</template>
<script setup>
import { ref } from "vue";
import {
  ArrowRight,
  CaretBottom,
  CaretTop,
  Warning,
} from "@element-plus/icons-vue";

const showCustomer = ref(true);
const showProduct = ref(false);
const showOrder = ref(false);
const showIncome = ref(false);

const showDetail = (type) => {
  switch (type) {
    case "customer":
      showCustomer.value = true;
      showProduct.value = false;
      showOrder.value = false;
      showIncome.value = false;
      break;
    case "product":
      showCustomer.value = false;
      showProduct.value = true;
      showOrder.value = false;
      showIncome.value = false;
      break;
    case "order":
      showCustomer.value = false;
      showProduct.value = false;
      showOrder.value = true;
      showIncome.value = false;
      break;
    case "income":
      showCustomer.value = false;
      showProduct.value = false;
      showOrder.value = false;
      showIncome.value = true;
      break;
    default:
      break;
  }
};
</script>

<style scoped>
:global(h2#card-usage ~ .example .example-showcase) {
  background-color: var(--el-fill-color) !important;
}

.el-statistic {
  --el-statistic-content-font-size: 28px;
}

.statistic-card {
  height: 100%;
  padding: 20px;
  border-radius: 4px;
  background-color: var(--el-bg-color-overlay);
}

.statistic-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  font-size: 12px;
  color: var(--el-text-color-regular);
  margin-top: 16px;
}

.statistic-footer .footer-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.statistic-footer .footer-item span:last-child {
  display: inline-flex;
  align-items: center;
  margin-left: 4px;
}

.green {
  color: var(--el-color-success);
}
.red {
  color: var(--el-color-error);
}
</style>
