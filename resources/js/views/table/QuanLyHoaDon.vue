<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.title" :placeholder="'Tên khách hàng hoặc số điện thoại'" style="display: inline-block;width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-select v-model="listQuery.month" placeholder="Chọn tháng" style="display: inline-block;width: 150px;" class="filter-item" @change="getList()">
        <el-option :key="1" :label="'Theo tháng'" :value="1" />
        <el-option :key="0" :label="'Theo ngày'" :value="0" />
      </el-select>
      <el-select v-model="listQuery.type" placeholder="Chọn loại hóa đơn" style="display: inline-block;width: 150px;" class="filter-item" @change="getList()">
        <el-option :key="1" :label="'Bán lẻ'" :value="1" />
        <el-option :key="0" :label="'Bán buôn'" :value="0" />
      </el-select>
      <el-date-picker v-model="listQuery.date" type="date" format="dd/MM/yyyy" value-format="yyyy-MM-dd" placeholder="Xem theo ngày" style="width: 200px;" class="filter-item" />
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        {{ $t('table.search') }}
      </el-button>
    </div>

    <el-table
      :key="tableKey"
      v-loading="listLoading"
      :data="list"
      fit
      border
      highlight-current-row
      style="width: 100%;"
    >
      <el-table-column v-if="listQuery.type" :label="'Tên KH'" width="150px" align="left">
        <template slot-scope="scope">
          <span>{{ scope.row.ten_khach_hang }}</span>
        </template>
      </el-table-column>
      <el-table-column v-if="listQuery.type" :label="'Số điện thoại'" width="150px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.sdt }}</span>
        </template>
      </el-table-column>
      <el-table-column v-if="!listQuery.type" :label="'Tên nhà xuất bán'" min-width="250px" align="left">
        <template slot-scope="scope">
          <span>{{ scope.row.nxb ? scope.row.nxb.name : '' }}</span>
        </template>
      </el-table-column>
      <el-table-column v-if="!listQuery.type" :label="'Số lượng'" min-width="120px" align="center">
        <template slot-scope="scope">
          <span>{{ chiTietInfo(scope.row.chi_tiet) }}</span>
        </template>
      </el-table-column>
      <el-table-column v-if="listQuery.type" :label="'Tên SP'" min-width="300px" align="left">
        <template slot-scope="scope">
          <span>{{ scope.row.chi_tiet.length && scope.row.chi_tiet[0].san_pham ? scope.row.chi_tiet[0].san_pham.name : '' }}</span>
        </template>
      </el-table-column>
      <el-table-column v-if="listQuery.type" :label="'NCC'" width="150px" align="left">
        <template slot-scope="scope">
          <span>{{ scope.row.chi_tiet.length && scope.row.chi_tiet[0].san_pham && scope.row.chi_tiet[0].san_pham.nha_cung_cap_info ? scope.row.chi_tiet[0].san_pham.nha_cung_cap_info.name : '' }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="'Ngày mua'" width="100px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.ngay_ban | convertDateFromTimestamp }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="'Tổng tiền'" width="120px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.tong_tien | toThousandFilter }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="'Ghi chú'" width="150px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.note }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.actions')" align="center" width="200px" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button v-if="row.file" size="small" type="success" @click="handleView(row)">
            Xem GBN
          </el-button>
          <el-button v-if="!row.file" size="small" type="primary" @click="handleCreateGBN(row)">
            Viết GBN
          </el-button>
          <el-button size="small" type="danger" @click="handleDelete(row)">
            {{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />

    <el-dialog :title="'Chi tiết hóa đơn'" :visible.sync="dialogFormVisible">
      <el-table
        v-if="chiTiet.length > 0"
        ref="dragTable"
        border
        fit highlight-current-row
        :data="chiTiet"
      >
        <el-table-column label="Tên SP" min-width="250px">
          <template slot-scope="scope">
            {{ scope.row.san_pham.name }}
          </template>
        </el-table-column>
        <el-table-column label="NCC" width="150px">
          <template slot-scope="scope">
            {{ scope.row.san_pham.nha_cung_cap_info ? scope.row.san_pham.nha_cung_cap_info.name : '' }}
          </template>
        </el-table-column>
        <el-table-column label="SLượng" width="100px" align="center">
          <template slot-scope="scope">
            {{ scope.row.so_luong }}
          </template>
        </el-table-column>
        <el-table-column label="Giá nhập" width="120px" align="center">
          <template slot-scope="scope">
            {{ scope.row.san_pham.gia_nhap | toThousandFilter }}
          </template>
        </el-table-column>
        <el-table-column label="Giá bán" width="120px" align="center">
          <template slot-scope="scope">
            {{ scope.row.gia_ban | toThousandFilter }}
          </template>
        </el-table-column>
        <el-table-column label="TTiền" width="120px" align="center">
          <template slot-scope="scope">
            {{ scope.row.gia_ban * scope.row.so_luong | toThousandFilter }}
          </template>
        </el-table-column>
      </el-table>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">
          {{ $t('table.cancel') }}
        </el-button>
      </div>
    </el-dialog>
    <el-dialog :visible.sync="dialogPvVisibleHD" title="Xem thông tin hóa đơn" width="650px !important" backdrop-static>
      <el-row class="line">
        <el-col :span="24">
          <h2>{{ user.ten_cua_hang }}</h2>
        </el-col>
      </el-row>
      <el-row class="line">
        <el-col :span="24">
          <b>{{ user.dia_chi }}</b>
        </el-col>
      </el-row>
      <el-row class="line">
        <el-col :span="24">
          <b>Điện thoại : {{ user.phone }}</b>
        </el-col>
      </el-row>
      <el-row class="line">
        <el-col :span="24">
          <h3>HOÁ ĐƠN THANH TOÁN</h3>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="12">
          <p>Ngày mua : {{ form.ngay_ban | convertDateFromTimestamp }}</p>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="12">
          <p>Khách hàng : <b>{{ form.ten_khach_hang }}</b></p>
        </el-col>
        <el-col :span="12">
          <p>Số điện thoại : <b>{{ form.sdt }}</b></p>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="12">
          <p>CCCD hoặc CMT : <b>{{ form.cccd }}</b></p>
        </el-col>
        <el-col :span="12">
          <p>Địa chỉ : <b>{{ form.dia_chi }}</b></p>
        </el-col>
      </el-row>
      <table class="service-table text-center">
        <thead class="text-center">
          <th>Tên SP</th>
          <th>SK - SM</th>
          <th width="50px">SL</th>
          <th width="120px">Giá</th>
          <th width="150px">TTiền</th>
        </thead>
        <tbody class="text-center">
          <tr v-for="item of chiTiet" :key="item.id">
            <td>{{ item.san_pham ? item.san_pham.name : '' }}</td>
            <td>{{ item.san_pham ? item.san_pham.short_name : '' }}</td>
            <td>{{ item.so_luong }}</td>
            <td>{{ item.gia_ban | toThousandFilter }}</td>
            <td>{{ item.so_luong * item.gia_ban | toThousandFilter }} VNĐ</td>
          </tr>
          <tr>
            <td colspan="4" class="text-right"><b>{{ form.delivery ? 'Chuyển khoản' : 'Tiền mặt' }}</b></td>
            <td colspan="1"><b>{{ form.tong_tien | toThousandFilter }} VNĐ</b></td>
          </tr>
          <tr>
            <td colspan="5">{{ _convert_number_to_words(form.tong_tien) }}</td>
          </tr>
          <tr>
            <td colspan="5">{{ form.note }}</td>
          </tr>
        </tbody>
      </table>
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
// import { fetchList, store, del } from '@/api/san-pham';
import { fetchList as lstHang } from '@/api/hang-xe';
import { fetchList as lstNCC } from '@/api/ncc';
import { fetchList as lstNhomHang } from '@/api/nhom-hang';
import { fetchList as lstHD, del } from '@/api/hoa-don';
import { getInfo } from '@/api/users';
import waves from '@/directive/waves'; // Waves directive
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
// import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

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
        type: 1,
        sort: '+id',
        month: 0,
        hang_xe: '',
        ncc: '',
        nhom_hang: '',
      },
      importanceOptions: [1, 2, 3],
      calendarTypeOptions,
      sortOptions: [{ label: 'ID Ascending', key: '+id' }, { label: 'ID Descending', key: '-id' }],
      statusOptions: ['published', 'draft', 'deleted'],
      showReviewer: false,
      temp: {
        id: undefined,
        name: '',
        short_name: '',
        hang_xe: '',
        nha_cung_cap: '',
        nhom_hang: '',
        gia_ban: '',
        gia_nhap: '',
        so_luong_con_lai: '',
        so_luong_nhap: '',
        phuong_thuc_nhap: '',
        ngay_nhap: '',
        img: '',
        image: '',
        note: '',
        img_path: '',
      },
      dialogFormVisible: false,
      dialogPvVisibleHD: false,
      dialogStatus: '',
      textMap: {
        update: 'Sửa',
        create: 'Thêm',
      },
      dialogPvVisible: false,
      pvData: [],
      downloadLoading: false,
      hangXe: [],
      nhomHang: [],
      nhaCungCap: [],
      additionalData: {},
      imgUrl: '',
      imagePost: null,
      activeTab: 'first',
      chiTiet: [],
      user: {},
      form: {
        sdt: '',
        ten_khach_hang: '',
        ngay_ban: '',
        note: '',
        cccd: '',
        dia_chi: '',
        delivery: '',
      },
    };
  },
  created() {
    // this.getHangXe();
    // this.getNhomHang();
    // this.getNCC();
    this.getList();
    this.getInfo();
  },
  methods: {
    async getInfo() {
      const { data } = await getInfo();
      this.user = data;
    },
    getImgUrl(imgPath) {
      // Chuyển đổi đường dẫn đầy đủ thành URL có thể truy cập được
      if (!imgPath) {
        return '';
      }
      return `${window.location.origin}/${imgPath.replace(/\\/g, '/').split('public/')[1]}`;
    },
    handleView(row) {
      // if (!this.listQuery.type) {
      //   this.dialogFormVisible = true;
      // } else {
      //   this.dialogPvVisibleHD = true;
      //   this.form = { ...this.form, ...row };
      // }
      // this.chiTiet = row.chi_tiet;
      window.open('files/' + this.user.id + '/' + row.file);
    },
    beforeUpload(file) {
      this.imagePost = file;
    },
    handleImageSuccess(file) {
      this.imgUrl = file.files.file;
    },
    async getHangXe() {
      const { data } = await lstHang({ viewSelect: 1 });
      this.hangXe = data.data;
    },
    async getNhomHang() {
      const { data } = await lstNhomHang({ viewSelect: 1 });
      this.nhomHang = data.data;
    },
    async getNCC() {
      const { data } = await lstNCC({ viewSelect: 1 });
      this.nhaCungCap = data.data;
    },
    async getList() {
      this.listLoading = true;
      const { data } = await lstHD(this.listQuery);
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
    handleCreateGBN(row) {
      window.open('giay-bien-nhan?id=' + row.id);
    },
    handleInput(value) {
      // console.log(value);
    },
    handleSelect(value) {
      // console.log(value);
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
    chiTietInfo(data) {
      let str = '';
      str = data.reduce(function(rs, item) {
        rs += item.so_luong;
        return rs;
      }, 0);
      return str;
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
<style scoped>
  .line{
    text-align: center;
  }
  .service-table {
  width: 100%;
  border-collapse: collapse;
}

.service-table th,
.service-table td {
  padding: 12px;
  text-align: center;
  border: 1px solid #ddd;
}

.service-table th {
  background-color: #f2f2f2;
  font-weight: bold;
  color: #333;
}

.service-table tbody tr:nth-child(even) {
  background-color: #f9f9f9;
}

.service-table tbody tr:hover {
  background-color: #e6e6e6;
}

.service-table td:last-child {
  text-align: center;
}

.service-table .delete-button {
  padding: 6px 10px;
  background-color: #e74c3c;
  color: #fff;
  border: none;
  cursor: pointer;
  border-radius: 4px;
  transition: background-color 0.3s ease;
}

.service-table .delete-button:hover {
  background-color: #c0392b;
}

.service-table .delete-button:active {
  background-color: #962d22;
}
.text-left {
  text-align: left;
}
.text-right {
  text-align: right !important;
}
.text-center {
  text-align: center;
}
</style>
