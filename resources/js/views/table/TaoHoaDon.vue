<template>
  <div class="app-container">
    <div class="filter-container">
      <div class="container">
        <div class="row line">
          <h1>Tạo hóa đơn</h1>
        </div>
      </div>
      <el-form ref="dataForm" :rules="rules" :model="form" label-width="150px">
        <el-row>
          <el-col :xs="24" :sm="12" :lg="6">
            <label class="label" for="">Thông tin khách hàng:</label>
          </el-col>
        </el-row>
        <el-row>
          <el-col :xs="24" :sm="12" :lg="6">
            <el-form-item label="Họ và tên" prop="name">
              <el-col :span="24">
                <el-input v-model="form.name" placeholder="Tên khách hàng" />
              </el-col>
            </el-form-item>
          </el-col>
          <el-col :xs="24" :sm="12" :lg="6">
            <el-form-item label="Số điện thoại" prop="phone">
              <el-col :span="24">
                <el-input v-model="form.phone" placeholder="Số điện thoại" />
              </el-col>
            </el-form-item>
          </el-col>
          <el-col :xs="24" :sm="12" :lg="6">
            <el-form-item label="Ngày sinh">
              <el-col :span="24">
                <el-date-picker v-model="form.ngay_sinh" type="date" format="dd/MM/yyyy" value-format="yyyy-MM-dd" placeholder="Ngày sinh" style="width: 100%;" />
              </el-col>
            </el-form-item>
          </el-col>
          <el-col :xs="24" :sm="12" :lg="6">
            <el-form-item label="Địa chỉ">
              <el-col :span="24">
                <el-input v-model="form.dia_chi" placeholder="Địa chỉ" />
              </el-col>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :xs="24" :sm="12" :lg="6">
            <label class="label" for="">Thông tin hoá đơn:</label>
          </el-col>
        </el-row>
        <el-row>
          <el-col :xs="24" :sm="12" :lg="12">
            <el-form-item label="Chọn sản phẩm">
              <el-col :span="24">
                <v-select v-model="dv_id" :options="lstDichVu" label="name" placeholder="Tìm kiếm sản phẩm" :reduce="option => option.id" @input="handleInput" @select="handleSelect" />
              </el-col>
            </el-form-item>
          </el-col>
          <el-col :xs="12" :sm="6" :lg="3">
            <el-button style="margin-left: 10px;" class="filter-item" type="success" @click="add_dv(dv_id)">
              Thêm sản phẩm
            </el-button>
          </el-col>
          <el-col :xs="12" :sm="6" :lg="3">
            <el-button style="margin-left: 10px;" class="filter-item" type="primary" :disabled="!form.phone || newData.length == 0" @click="dialogPvVisible = true">
              Xem hoá đơn
            </el-button>
          </el-col>
        </el-row>
      </el-form>
      <el-table
        v-if="newData.length > 0"
        ref="dragTable"
        border
        fit highlight-current-row
        :data="newData"
      >
        <el-table-column label="Tên SP">
          <template slot-scope="scope">
            {{ scope.row.name }}
          </template>
        </el-table-column>
        <el-table-column label="SLượng" width="100px">
          <template slot-scope="scope">
            <el-input v-model="scope.row.soluong" :min="min" placeholder="Số lượng" style="width: 100%" @input="handleSoLuong(scope.row)" />
          </template>
        </el-table-column>
        <el-table-column label="Giá" width="120px">
          <template slot-scope="scope">
            <el-input v-model="scope.row.gia_ban" />
          </template>
        </el-table-column>
        <el-table-column label="KMãi" width="100px">
          <template slot-scope="scope">
            <el-input v-model="scope.row.gia_khuyen_mai" :min="min" placeholder="KMãi" style="width: 100%" />
          </template>
        </el-table-column>
        <el-table-column label="TTiền" width="120px">
          <template slot-scope="scope">
            {{ parseInt(scope.row.gia_khuyen_mai) ? Math.floor((scope.row.soluong * scope.row.gia_ban) - (scope.row.soluong * scope.row.gia_ban) * parseInt(scope.row.gia_khuyen_mai) / 100) : scope.row.soluong * scope.row.gia_ban | toThousandFilter }}
          </template>
        </el-table-column>
        <el-table-column label="Xoá" width="50px">
          <template slot-scope="scope">
            <i class="el-icon-error" @click="deleteDV(scope.row.id)" />
          </template>
        </el-table-column>
      </el-table>
      <!-- <el-form-item label="Chuyển khoản"> -->
      <el-form v-if="newData.length > 0" label-width="150px" style="margin-top: 10px">
        <el-form-item label="Ghi chú">
          <el-input v-model="form.note" placeholder="Chú thích thêm" />
        </el-form-item>
      </el-form>
      <el-form v-if="newData.length > 0" label-width="150px">
        <el-form-item label="Chuyển khoản">
          <el-switch v-model="form.delivery" />
        </el-form-item>
      </el-form>
      <el-row v-if="form.delivery">
        <el-col :xs="24" :sm="12" :lg="6">
          <img :src="returnQR()" alt="" width="100%">
        </el-col>
      </el-row>
      <el-button type="primary" :disabled="!form.phone || newData.length == 0" style="width:100%" @click="save()">
        Tạo hóa đơn
      </el-button>
      <!-- </el-form-item> -->
      <el-dialog :visible.sync="dialogPvVisible" title="Xem thông tin hóa đơn" width="550px !important" backdrop-static>
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
            <p>Ngày mua : {{ currentDate() }}</p>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <p>Khách hàng : <b>{{ form.name }}</b></p>
          </el-col>
          <el-col :span="12">
            <p>Số điện thoại : <b>{{ form.phone }}</b></p>
          </el-col>
        </el-row>
        <table class="service-table text-center">
          <thead class="text-center">
            <th>Tên SP</th>
            <th width="50px">SL</th>
            <th width="120px">Giá</th>
            <th width="50px">KM</th>
            <th width="150px">TTiền</th>
          </thead>
          <tbody class="text-center">
            <tr v-for="item of newData" :key="item.id">
              <td>{{ item.name }}</td>
              <td>{{ item.soluong }}</td>
              <td>{{ item.gia_ban | toThousandFilter }}</td>
              <td>{{ item.gia_khuyen_mai ? item.gia_khuyen_mai + '%' : '' }}</td>
              <td>{{ parseInt(item.gia_khuyen_mai) ? Math.floor((item.soluong * item.gia_ban) - (item.soluong * item.gia_ban) * parseInt(item.gia_khuyen_mai) / 100) : item.soluong * item.gia_ban | toThousandFilter }} VNĐ</td>
            </tr>
            <tr>
              <td colspan="4" class="text-right"><b>{{ form.delivery ? 'Chuyển khoản' : 'Tiền mặt' }}</b></td>
              <td colspan="1"><b>{{ tongTien() | toThousandFilter }} VNĐ</b></td>
            </tr>
            <tr>
              <td colspan="5">{{ _convert_number_to_words(tongTien()) }}</td>
            </tr>
          </tbody>
        </table>
        <hr>
        <el-row class="line">
          <el-col :span="24">
            <h2>XIN CẢM ƠN - HẸN GẶP LẠI!</h2>
          </el-col>
        </el-row>
        <span slot="footer" class="dialog-footer">
          <el-button type="primary" @click="dialogPvVisible = false">{{ $t('table.confirm') }}</el-button>
        </span>
      </el-dialog>
    </div>
  </div>
</template>
<script>
import { fetchList } from '@/api/san-pham';
import { store } from '@/api/hoa-don';
import { getInfo } from '@/api/users';
import waves from '@/directive/waves'; // Waves directive
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
export default {
  name: 'TaoHoaDon',
  directives: { waves },
  components: {
    'v-select': vSelect,
  },
  filters: {
    statusFilter(status) {
      const statusMap = {
        published: 'success',
        draft: 'info',
        deleted: 'danger',
      };
      return statusMap[status];
    },
  },
  data() {
    return {
      form: {
        phone: '',
        name: '',
        delivery: false,
        nam_sinh: null,
        note: '',
      },
      lstDichVu: [],
      dv_id: '',
      newData: [],
      lstKhachHang: [],
      min: 0,
      dialogPvVisible: false,
      mangso: ['không', 'một', 'hai', 'ba', 'bốn', 'năm', 'sáu', 'bảy', 'tám', 'chín'],
      rules: {
        name: [{ required: true, message: 'Vui lòng nhập tên khách hàng!', trigger: 'blur' }],
        phone: [{ required: true, message: 'Vui lòng số điện thoại!', trigger: 'blur' }],
      },
      user: {},
    };
  },
  mounted() {
    // $(this.$refs.selectElement).select2();
  },
  created() {
    this.getList();
    this.getInfo();
  },
  methods: {
    async getInfo() {
      this.listLoading = true;
      const { data } = await getInfo();
      this.user = data;
      this.listLoading = false;
    },
    async getList() {
      this.listLoading = true;
      const { data } = await fetchList({ viewSelect: 1 });
      this.lstDichVu = data;
      this.listLoading = false;
    },
    handleSoLuong(row) {
      if (row.soluong > row.so_luong_con_lai) {
        this.$notify({
          title: 'Cảnh báo',
          message: 'Cửa hàng chỉ còn lại: ' + row.so_luong_con_lai + ' sản phẩm này, vui lòng kiểm tra lại!',
          type: 'error',
          duration: 3000,
        });
        row.soluong = 1;
      }
    },
    handleInput(value) {},
    handleSelect(value) {
      // console.log(value);
    },
    handleInputKH(value) {
      const item = this.lstKhachHang.find(v => v.sdt === value);
      if (!item) {
        return;
      }
      this.form.name = item.ten;
      this.form.ngay_sinh = item.ngay_sinh;
    },
    handleSelectKH(value) {
    },
    add_dv(dv_id) {
      const item = this.newData.find(x => x.id === dv_id);
      if (!item) {
        const addItem = this.lstDichVu.find(x => x.id === dv_id);
        if (!addItem) {
          return;
        }
        if (addItem.so_luong_con_lai <= 0) {
          this.$notify({
            title: 'Cảnh báo',
            message: 'Cửa hàng đã hết sản phẩm này, vui lòng kiểm tra lại!',
            type: 'error',
            duration: 3000,
          });
          return;
        }
        this.newData = [
          ...this.newData,
          {
            ...addItem,
            soluong: 1,
          },
        ];
      } else {
        if (item.soluong + 1 > item.so_luong_con_lai) {
          this.$notify({
            title: 'Cảnh báo',
            message: 'Cửa hàng chỉ còn lại: ' + item.so_luong_con_lai + ' sản phẩm này, vui lòng kiểm tra lại!',
            type: 'error',
            duration: 3000,
          });
          return;
        }
        item.soluong += 1;
      }
    },
    deleteDV(dv_id) {
      const index = this.newData.findIndex(x => x.id === dv_id);
      this.newData.splice(index, 1);
    },
    tongTien() {
      let tt = 0;
      this.newData.forEach(item => {
        tt += parseInt(item.gia_khuyen_mai) ? Math.floor((item.soluong * item.gia_ban) - (item.soluong * item.gia_ban) * parseInt(item.gia_khuyen_mai) / 100) : (item.soluong * item.gia_ban);
      });
      return tt;
    },
    async save() {
      if (this.form.name.length === 0 || this.form.phone.length === 0 || this.newData.length === 0) {
        this.$notify({
          title: 'Cảnh báo',
          message: 'Hãy nhập đầy đủ thông tin!',
          type: 'error',
          duration: 2000,
        });
        return;
      }
      const tmp = {
        data: this.newData,
        total: this.tongTien(),
      };
      const params = {
        ... this.form,
        ...tmp,
      };
      const { success, message } = await store(params);
      if (success) {
        this.$notify({
          title: 'Thông báo',
          message: message,
          type: 'success',
          duration: 2000,
        });
        this.dialogPvVisible = true;
      } else {
        this.$notify({
          title: 'Cảnh báo',
          message: message,
          type: 'error',
          duration: 2000,
        });
      }
    },
    currentDate() {
      const today = new Date();
      const yyyy = today.getFullYear();
      let mm = today.getMonth() + 1; // Months start at 0!
      let dd = today.getDate();

      if (dd < 10) {
        dd = '0' + dd;
      }
      if (mm < 10) {
        mm = '0' + mm;
      }

      return dd + '/' + mm + '/' + yyyy;
    },
    _convert_number_to_words(number) {
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
        return false;
      }

      if ((number >= 0 && parseInt(number) < 0) || parseInt(number) < 0 - Number.MAX_SAFE_INTEGER) {
        // overflow
        console.warn('convert_number_to_words only accepts numbers between -' + Number.MAX_SAFE_INTEGER + ' and ' + Number.MAX_SAFE_INTEGER);
        return false;
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
    returnQR() {
      return 'https://img.vietqr.io/image/' + this.user.loai_ngan_hang + '-' + this.user.stk + '-compact2.jpg?amount=' + this.tongTien() + '&addInfo=' + this.user.noi_dung_ck;
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
