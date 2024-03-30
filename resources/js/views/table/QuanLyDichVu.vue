<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.title" :placeholder="'Tên dịch vụ - phụ kiện'" style="width: 300px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <!-- <el-select v-model="listQuery.importance" :placeholder="$t('table.importance')" clearable style="width: 90px" class="filter-item">
        <el-option v-for="item in importanceOptions" :key="item" :label="item" :value="item" />
      </el-select> -->
      <!-- <el-select v-model="listQuery.type" :placeholder="$t('table.type')" clearable class="filter-item" style="width: 130px">
        <el-option v-for="item in calendarTypeOptions" :key="item.key" :label="item.display_name+'('+item.key+')'" :value="item.key" />
      </el-select>
      <el-select v-model="listQuery.sort" style="width: 140px" class="filter-item" @change="handleFilter">
        <el-option v-for="item in sortOptions" :key="item.key" :label="item.label" :value="item.key" />
      </el-select> -->
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        {{ $t('table.search') }}
      </el-button>
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
    </div>

    <el-table
      :key="tableKey"
      v-loading="listLoading"
      :data="list"
      border
      highlight-current-row
      style="width: 100%;"
    >
      <el-table-column :label="'Dịch vụ - Phụ kiện'" prop="id" align="center" style="width: 30%;">
        <template slot-scope="scope">
          <span>{{ scope.row.ten_dich_vu }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="'Giá'" style="width: 10%;" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.gia_ban | toThousandFilter }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="'Ghi chú'" style="width: 10%;" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.note }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.actions')" align="center" style="width: 30%;" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button type="primary" size="mini" @click="handleUpdate(row)">
            {{ $t('table.edit') }}
          </el-button>
          <el-button size="mini" type="danger" @click="handleDelete(row)">
            {{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />

    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="150px" style="width: 100%; margin-left:10px;">
        <el-form-item :label="'Tên dịch vụ'" prop="ten_dich_vu">
          <el-input v-model="temp.ten_dich_vu" />
        </el-form-item>
        <el-form-item :label="'Giá'" prop="gia_ban">
          <el-input v-model="temp.gia_ban" />
          <div>
            <span>{{ _convert_number_to_words(temp.gia_ban) }}</span>
          </div>
        </el-form-item>
        <el-form-item :label="'Ghi chú'" prop="note">
          <el-input v-model="temp.note" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">
          {{ $t('table.cancel') }}
        </el-button>
        <el-button type="primary" @click="dialogStatus==='create'?createData():updateData()">
          {{ $t('table.confirm') }}
        </el-button>
      </div>
    </el-dialog>

    <el-dialog :visible.sync="dialogPvVisible" title="Reading statistics">
      <el-table :data="pvData" border fit highlight-current-row style="width: 100%">
        <el-table-column prop="key" label="Channel" />
        <el-table-column prop="pv" label="Pv" />
      </el-table>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="dialogPvVisible = false">{{ $t('table.confirm') }}</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { fetchList, store, del } from '@/api/dich-vu';
import waves from '@/directive/waves'; // Waves directive
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination

const calendarTypeOptions = [
  { key: 'CN', display_name: 'China' },
  { key: 'US', display_name: 'USA' },
  { key: 'JA', display_name: 'Japan' },
  { key: 'VI', display_name: 'Vietnam' },
];

// arr to obj ,such as { CN : "China", US : "USA" }
const calendarTypeKeyValue = calendarTypeOptions.reduce((acc, cur) => {
  acc[cur.key] = cur.display_name;
  return acc;
}, {});

export default {
  name: 'ComplexTable',
  components: { Pagination },
  directives: { waves },
  filters: {
    statusFilter(status) {
      const statusMap = {
        published: 'success',
        draft: 'info',
        deleted: 'danger',
      };
      return statusMap[status];
    },
    typeFilter(type) {
      return calendarTypeKeyValue[type];
    },
  },
  data() {
    return {
      tableKey: 0,
      list: null,
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 10,
        importance: undefined,
        title: undefined,
        type: undefined,
        sort: '+id',
      },
      importanceOptions: [1, 2, 3],
      calendarTypeOptions,
      sortOptions: [{ label: 'ID Ascending', key: '+id' }, { label: 'ID Descending', key: '-id' }],
      statusOptions: ['published', 'draft', 'deleted'],
      showReviewer: false,
      temp: {
        id: undefined,
        ten_dich_vu: '',
        gia_ban: '',
        note: '',
      },
      dialogFormVisible: false,
      dialogStatus: '',
      textMap: {
        update: 'Sửa',
        create: 'Thêm',
      },
      dialogPvVisible: false,
      pvData: [],
      rules: {
        ten_dich_vu: [{ required: true, message: 'Tên dịch vụ không để trống!', trigger: 'input' }],
        gia_ban: [{ required: true, message: 'Giá dịch vụ không để trống!', trigger: 'input' }],
      },
      downloadLoading: false,
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      this.listLoading = true;
      const { data } = await fetchList(this.listQuery);
      this.list = data.data;
      this.total = data.total;

      // Just to simulate the time of the request
      this.listLoading = false;
    },
    handleFilter() {
      this.listQuery.page = 1;
      this.getList();
    },
    handleModifyStatus(row, status) {
      this.$message({
        message: 'Successful operation',
        type: 'success',
      });
      row.status = status;
    },
    sortChange(data) {
      const { prop, order } = data;
      if (prop === 'id') {
        this.sortByID(order);
      }
    },
    sortByID(order) {
      if (order === 'ascending') {
        this.listQuery.sort = '+id';
      } else {
        this.listQuery.sort = '-id';
      }
      this.handleFilter();
    },
    resetTemp() {
      this.temp = {
        id: undefined,
        name: '',
        note: '',
      };
    },
    handleCreate() {
      this.resetTemp();
      this.dialogStatus = 'create';
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate();
      });
    },
    createData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          this.temp.id = ''; // mock a id
          store(this.temp).then((res) => {
            this.getList();
            this.dialogFormVisible = false;
            this.$notify({
              title: res.success ? 'Xong' : 'Lỗi',
              message: res.message,
              type: res.success ? 'success' : 'error',
              duration: 2000,
            });
          });
        }
      });
    },
    handleUpdate(row) {
      this.temp = Object.assign({}, row); // copy obj
      this.temp.timestamp = new Date(this.temp.timestamp);
      this.dialogStatus = 'update';
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate();
      });
    },
    updateData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          const tempData = Object.assign({}, this.temp);
          store(tempData).then(() => {
            this.getList();
            this.dialogFormVisible = false;
            this.$notify({
              title: 'Success',
              message: 'Sửa thành công!',
              type: 'success',
              duration: 2000,
            });
          });
        }
      });
    },
    handleDelete(row) {
      this.$confirm('Bạn có chắc muốn xoá?', 'Cảnh báo', {
        confirmButtonText: 'Có',
        cancelButtonText: 'Không',
        type: 'warning',
      })
        .then(async() => {
          del({ 'id': row.id }).then((res) => {
            this.$notify({
              title: res.success ? 'Xong' : 'Lỗi',
              message: res.message,
              type: res.success ? 'success' : 'error',
              duration: 2000,
            });
            this.getList();
          });
        });
    },
    _convert_number_to_words(number) {
      if (!number) {
        return 'Không đồng';
      }
      const hyphen = ' ';
      const conjunction = ' ';
      const separator = ' ';
      const negative = 'âm ';
      const decimal = ' phảy ';
      const dictionary = {
        0: 'không',
        1: 'một',
        2: 'hai',
        3: 'ba',
        4: 'bốn',
        5: 'năm',
        6: 'sáu',
        7: 'bảy',
        8: 'tám',
        9: 'chín',
        10: 'mười',
        11: 'mười một',
        12: 'mười hai',
        13: 'mười ba',
        14: 'mười bốn',
        15: 'mười lăm',
        16: 'mười sáu',
        17: 'mười bảy',
        18: 'mười tám',
        19: 'mười chín',
        20: 'hai mươi',
        30: 'ba mươi',
        40: 'bốn mươi',
        50: 'năm mươi',
        60: 'sáu mươi',
        70: 'bảy mươi',
        80: 'tám mươi',
        90: 'chín mươi',
        100: 'trăm',
        1000: 'nghìn',
        1000000: 'triệu',
        1000000000: 'tỷ',
        1000000000000: 'nghìn tỷ',
        1000000000000000: 'triệu tỷ',
        1000000000000000000: 'tỷ tỷ',
      };

      if (isNaN(number)) {
        return 'Hãy nhập số!';
      }

      if ((number >= 0 && parseInt(number) < 0) || parseInt(number) < 0 - Number.MAX_SAFE_INTEGER) {
        return 'Hãy nhập số!';
      }

      if (number < 0) {
        return negative + this._convert_number_to_words(Math.abs(number));
      }

      let string = null;
      let fraction = null;

      if (number.toString().indexOf('.') !== -1) {
        [number, fraction] = number.toString().split('.');
      }

      const baseUnit = Math.pow(1000, Math.floor(Math.log(number) / Math.log(1000)));
      const numBaseUnits = Math.floor(number / baseUnit);
      const remainder1 = number % baseUnit;
      const tmp = remainder1.toString().split('.');
      switch (true) {
        case number < 21:
          string = dictionary[number];
          break;
        case number < 100:
          string = dictionary[Math.floor(number / 10) * 10];
          if (number % 10) {
            string += hyphen + dictionary[number % 10];
          }
          break;
        case number < 1000:
          string = dictionary[Math.floor(number / 100)] + ' ' + dictionary[100];
          if (number % 100) {
            let tmp_str = '';
            if (number % 100 < 10) {
              tmp_str = ' linh ';
            }
            string += conjunction + tmp_str + this._convert_number_to_words(number % 100);
          }
          break;
        default:
          string = this._convert_number_to_words(numBaseUnits) + ' ' + dictionary[baseUnit];
          if (baseUnit === 1000000 && tmp[0].length === 5) {
            string += ' không trăm ';
          }
          if (remainder1) {
            string += remainder1 < 100 ? conjunction : separator;
            string += this._convert_number_to_words(remainder1);
          }
          break;
      }

      if (fraction !== null && !isNaN(fraction)) {
        string += decimal;
        const words = [];
        for (const number of fraction) {
          words.push(dictionary[number]);
        }
        string += words.join(' ');
        string = string.charAt(0).toUpperCase() + string.slice(1);
      }

      return string.replace(['mươi năm', 'mươi một'], ['mươi lăm', 'mươi mốt']);
    },
  },
};
</script>
