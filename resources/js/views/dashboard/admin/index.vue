<template>
  <div class="dashboard-editor-container">
    <github-corner style="position: absolute; top: 0px; border: 0; right: 0;" />
    <div class="filter-container">
      <el-date-picker v-model="listQuery.date" type="date" format="dd/MM/yyyy" value-format="yyyy-MM-dd" placeholder="Xem theo ngày" style="width: 200px;" class="filter-item" @change="getData()" />
      <el-select v-model="listQuery.month" placeholder="Chọn tháng" style="display: inline-block;width: 150px;" class="filter-item" @change="getData()">
        <el-option :key="1" :label="'Theo tháng'" :value="1" />
        <el-option :key="0" :label="'Theo ngày'" :value="0" />
      </el-select>
    </div>

    <panel-group :data="data.panel_group" @handleSetLineChartData="handleSetLineChartData" />

    <!-- <el-row style="background:#fff;padding:16px 16px 0;margin-bottom:32px;">
      <line-chart :chart-data="lineChartData" />
    </el-row>

    <el-row :gutter="32">
      <el-col :xs="24" :sm="24" :lg="8">
        <div class="chart-wrapper">
          <raddar-chart />
        </div>
      </el-col>
      <el-col :xs="24" :sm="24" :lg="8">
        <div class="chart-wrapper">
          <pie-chart />
        </div>
      </el-col>
      <el-col :xs="24" :sm="24" :lg="8">
        <div class="chart-wrapper">
          <bar-chart />
        </div>
      </el-col>
    </el-row>

    <el-row :gutter="8">
      <el-col :xs="{span: 24}" :sm="{span: 24}" :md="{span: 24}" :lg="{span: 12}" :xl="{span: 12}" style="padding-right:8px;margin-bottom:30px;">
        <transaction-table />
      </el-col>
      <el-col :xs="{span: 24}" :sm="{span: 12}" :md="{span: 12}" :lg="{span: 6}" :xl="{span: 6}" style="margin-bottom:30px;">
        <todo-list />
      </el-col>
      <el-col :xs="{span: 24}" :sm="{span: 12}" :md="{span: 12}" :lg="{span: 6}" :xl="{span: 6}" style="margin-bottom:30px;">
        <box-card />
      </el-col>
    </el-row> -->
  </div>
</template>

<script>
import GithubCorner from '@/components/GithubCorner';
import PanelGroup from './components/PanelGroup';
import { get } from '@/api/dashboard';
// import LineChart from './components/LineChart';
// import RaddarChart from './components/RaddarChart';
// import PieChart from './components/PieChart';
// import BarChart from './components/BarChart';
// import TransactionTable from './components/TransactionTable';
// import TodoList from './components/TodoList';
// import BoxCard from './components/BoxCard';

const lineChartData = {
  newVisitis: {
    expectedData: [100, 120, 161, 134, 105, 160, 165],
    actualData: [120, 82, 91, 154, 162, 140, 145],
  },
  messages: {
    expectedData: [200, 192, 120, 144, 160, 130, 140],
    actualData: [180, 160, 151, 106, 145, 150, 130],
  },
  purchases: {
    expectedData: [80, 100, 121, 104, 105, 90, 100],
    actualData: [120, 90, 100, 138, 142, 130, 130],
  },
  shoppings: {
    expectedData: [130, 140, 141, 142, 145, 150, 160],
    actualData: [120, 82, 91, 154, 162, 140, 130],
  },
};

export default {
  name: 'DashboardAdmin',
  components: {
    GithubCorner,
    PanelGroup,
    // LineChart,
    // RaddarChart,
    // PieChart,
    // BarChart,
    // TransactionTable,
    // TodoList,
    // BoxCard,
  },
  data() {
    const today = new Date();
    const day = String(today.getDate()).padStart(2, '0');
    const month = String(today.getMonth() + 1).padStart(2, '0'); // Tháng bắt đầu từ 0
    const year = today.getFullYear();

    const dateStr = `${year}-${month}-${day}`;
    return {
      lineChartData: lineChartData.newVisitis,
      listQuery: {
        date: dateStr,
        month: 0,
      },
      data: {
        panel_group: {
          so_xe_nhap: 0,
          tien_xe_nhap: 0,
          so_xe_ban: 0,
          tien_xe_ban: 0,
        },
      },
    };
  },
  created() {
    this.getData();
  },
  methods: {
    handleSetLineChartData(type) {
      this.lineChartData = lineChartData[type];
    },
    async getData() {
      const { data } = await get(this.listQuery);
      this.data = data;
      console.log(data);
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.dashboard-editor-container {
  padding: 32px;
  background-color: rgb(240, 242, 245);
  .chart-wrapper {
    background: #fff;
    padding: 16px 16px 0;
    margin-bottom: 32px;
  }
}
</style>
