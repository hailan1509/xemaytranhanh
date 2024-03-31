<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.title" :placeholder="'Tìm tên hoặc số khung số máy'" style="display: inline-block;width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-select v-model="listQuery.month" placeholder="Chọn tháng" style="display: inline-block;width: 150px;" class="filter-item">
        <el-option :key="1" :label="'Theo tháng'" :value="1" />
        <el-option :key="0" :label="'Theo ngày'" :value="0" />
      </el-select>
      <v-select v-model="listQuery.hang_xe" class="el-select filter-item el-select--medium" :options="hangXe" style="display: inline-block; width: 200px" :menu-props="{ contentClass: 'filter-item' }" label="name" placeholder="Tìm kiếm hãng" :reduce="option => option.id" />
      <v-select v-model="listQuery.nhom_hang" class="el-select filter-item el-select--medium" :options="nhomHang" style="display: inline-block; width: 200px" :menu-props="{ contentClass: 'filter-item' }" label="name" placeholder="Tìm kiếm nhóm hàng" :reduce="option => option.id" />
      <v-select v-model="listQuery.ncc" class="el-select filter-item el-select--medium" :options="nhaCungCap" style="display: inline-block; width: 200px" :menu-props="{ contentClass: 'filter-item' }" label="name" placeholder="Tìm kiếm nhà cung cấp" :reduce="option => option.id" />
      <el-date-picker v-model="listQuery.date" type="date" format="dd/MM/yyyy" value-format="yyyy-MM-dd" placeholder="Xem theo ngày" style="width: 200px;" class="filter-item" />
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
      <el-table-column :label="'Tên SP'" min-width="250px" align="left">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="'SK - SM'" width="150px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.short_name }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="'Ngày nhập'" width="150px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.ngay_nhap | convertDateFromTimestamp }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="'NCC'" width="150px" align="left">
        <template slot-scope="scope">
          <span>{{ scope.row.nha_cung_cap_info.name }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="'Giá nhập'" width="120px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.gia_nhap | toThousandFilter }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="'Giá bán'" width="120px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.gia_ban | toThousandFilter }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="'Còn'" width="50px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.so_luong_con_lai }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="'Ghi chú'" width="120px" align="left">
        <template slot-scope="scope">
          <span>{{ scope.row.note }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.actions')" align="center" width="300px" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button type="primary" size="mini" @click="handleUpdate(row)">
            {{ $t('table.edit') }}
          </el-button>
          <el-button v-if="row.status!='deleted'" size="mini" type="danger" @click="handleDelete(row)">
            {{ $t('table.delete') }}
          </el-button>
          <el-button type="success" size="mini" @click="viewHoaDon(row)">
            Xem
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />

    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="150px" style="width: 100%; margin-left:10px;">
        <el-tabs v-model="activeTab" style="margin-top:15px;" type="border-card">
          <el-tab-pane :label="'Thông tin sản phẩm'" name="first">
            <el-form-item :label="'Tên sản phẩm'" prop="name">
              <el-input v-model="temp.name" />
            </el-form-item>
            <el-form-item :label="'Màu sơn'" prop="mau">
              <el-input v-model="temp.mau" />
            </el-form-item>
            <el-form-item :label="'Số khung - máy'" prop="short_name">
              <el-input v-model="temp.short_name" />
            </el-form-item>
            <el-form-item :label="'Hãng xe'" prop="hang_xe">
              <v-select v-model="temp.hang_xe" :options="hangXe" style="display: inline-block; width: 200px" :menu-props="{ contentClass: 'filter-item' }" label="name" placeholder="Chọn hãng xe" :reduce="option => option.id" />
            </el-form-item>
            <el-form-item :label="'Nhóm hàng'" prop="nhom_hang">
              <v-select v-model="temp.nhom_hang" :options="nhomHang" style="display: inline-block; width: 200px" :menu-props="{ contentClass: 'filter-item' }" label="name" placeholder="Chọn nhóm" :reduce="option => option.id" />
            </el-form-item>
            <el-form-item :label="'Nhà cung cấp'" prop="nha_cung_cap">
              <v-select v-model="temp.nha_cung_cap" :options="nhaCungCap" style="display: inline-block; width: 200px" :menu-props="{ contentClass: 'filter-item' }" label="name" placeholder="Chọn nhà cung cấp" :reduce="option => option.id" />
            </el-form-item>
            <el-form-item :label="'Ngày nhập'" prop="ngay_nhap">
              <el-date-picker v-model="temp.ngay_nhap" type="date" format="dd/MM/yyyy" value-format="yyyy-MM-dd" placeholder="Chọn ngày nhập hàng" style="width: 200px;" class="filter-item" />
            </el-form-item>
          </el-tab-pane>
          <el-tab-pane :label="'Giá - Số lượng'">
            <el-form-item :label="'Giá nhập'" prop="gia_nhap">
              <el-input v-model="temp.gia_nhap" />
              <div>
                <span>{{ _convert_number_to_words(temp.gia_nhap) }}</span>
              </div>
            </el-form-item>
            <el-form-item :label="'Giá bán'" prop="gia_ban">
              <el-input v-model="temp.gia_ban" />
              <div>
                <span>{{ _convert_number_to_words(temp.gia_ban) }}</span>
              </div>
            </el-form-item>
            <el-form-item :label="'Số lượng nhập'" prop="so_luong_nhap">
              <el-input v-model="temp.so_luong_nhap" />
            </el-form-item>
            <el-form-item v-if="dialogStatus!=='create'" :label="'Số lượng còn lại'" prop="so_luong_con_lai">
              <el-input v-model="temp.so_luong_con_lai" />
            </el-form-item>

          </el-tab-pane>
          <el-tab-pane :label="'Thêm'">
            <el-form-item :label="'Biển số'" prop="phuong_thuc_nhap">
              <el-input v-model="temp.bien_so" />
            </el-form-item>
            <el-form-item :label="'Thông tin chủ cũ'" prop="phuong_thuc_nhap">
              <el-input v-model="temp.ten_chu_cu" />
            </el-form-item>
            <el-form-item :label="'Phương thức nhập'" prop="phuong_thuc_nhap">
              <el-input v-model="temp.phuong_thuc_nhap" />
            </el-form-item>
            <el-form-item :label="'Ghi chú'" prop="note">
              <el-input v-model="temp.note" />
            </el-form-item>
            <el-form-item :label="'Hình ảnh'" prop="title">
              <el-upload
                id="imgObject"
                :data="additionalData"
                :multiple="false"
                :show-file-list="true"
                :on-success="handleImageSuccess"
                :before-upload="beforeUpload"
                class="image-uploader"
                drag
                action="https://httpbin.org/post"
              >
                <i class="el-icon-upload" />
                <div class="el-upload__text">
                  Kéo file ảnh hoặc <em>Bấm vào đây</em>
                </div>
              </el-upload>
              <img v-if="imgUrl.length > 1" width="300px" height="auto" :src="imgUrl">
            </el-form-item>

          </el-tab-pane>
        </el-tabs>
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

    <el-dialog :visible.sync="dialogPvVisible" :title="'Danh sách hóa đơn'">
      <el-label>{{ 'Sản phẩm : ' + currentSP.name }}</el-label>
      <el-table :data="hoaDon" border fit highlight-current-row style="width: 100%">
        <el-table-column :label="'Tên KH'" align="center">
          <template slot-scope="scope">
            <span>{{ scope.row.hoa_don.type == 1 ? scope.row.hoa_don.ten_khach_hang : (scope.row.hoa_don.nxb ? scope.row.hoa_don.nxb.name : '') }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="'SĐT'" align="center">
          <template slot-scope="scope">
            <span>{{ scope.row.hoa_don.sdt }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="'Loại HD'" align="center">
          <template slot-scope="scope">
            <span>{{ scope.row.hoa_don.type == 1 ? 'Bán lẻ' : 'Bán buôn' }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="'Ngày mua'" align="center">
          <template slot-scope="scope">
            <span>{{ scope.row.hoa_don.ngay_ban | convertDateFromTimestamp }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="'SLượng'" align="center">
          <template slot-scope="scope">
            <span>{{ scope.row.so_luong }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="'Giá bán'" align="center">
          <template slot-scope="scope">
            <span>{{ scope.row.gia_ban | toThousandFilter }}</span>
          </template>
        </el-table-column>
      </el-table>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">
          {{ $t('table.cancel') }}
        </el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { fetchList, store, del, viewHoaDon } from '@/api/san-pham';
import { fetchList as lstHang } from '@/api/hang-xe';
import { fetchList as lstNCC } from '@/api/ncc';
import { fetchList as lstNhomHang } from '@/api/nhom-hang';
import waves from '@/directive/waves'; // Waves directive
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import vSelect from 'vue-select';
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
  components: { Pagination, vSelect },
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
        bien_so: '',
        ten_chu_cu: '',
        mau: '',
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
        name: [{ required: true, message: 'Vui lòng nhập tên sản phẩm!', trigger: 'input' }],
        mau: [{ required: true, message: 'Vui lòng nhập màu sơn!', trigger: 'input' }],
        short_name: [{ required: true, message: 'Vui lòng nhập số khung số máy!', trigger: 'input' }],
        nha_cung_cap: [{ required: true, message: 'Vui lòng chọn nhà cung cấp!', trigger: 'change' }],
        ngay_nhap: [{ required: true, message: 'Vui lòng chọn ngày nhập!', trigger: 'change' }],
        so_luong_nhap: [{ required: true, message: 'Vui lòng nhập số lượng nhập vào!', trigger: 'input' }],
        gia_nhap: [{ required: true, message: 'Vui lòng nhập giá nhập vào!', trigger: 'input' }],
        gia_ban: [{ required: true, message: 'Vui lòng nhập giá bán!', trigger: 'input' }],
      },
      downloadLoading: false,
      hangXe: [],
      nhomHang: [],
      nhaCungCap: [],
      additionalData: {},
      imgUrl: '',
      imagePost: null,
      activeTab: 'first',
      hoaDon: [],
      currentSP: {},
    };
  },
  created() {
    this.getHangXe();
    this.getNhomHang();
    this.getNCC();
    this.getList();
  },
  methods: {
    getImgUrl(imgPath) {
      // Chuyển đổi đường dẫn đầy đủ thành URL có thể truy cập được
      if (!imgPath) {
        return '';
      }
      return `${window.location.origin}/${imgPath.replace(/\\/g, '/').split('public/')[1]}`;
    },
    async viewHoaDon(row) {
      this.currentSP = row;
      this.listLoading = true;
      this.dialogPvVisible = true;
      const { data } = await viewHoaDon({ id: row.id });
      console.log(data);
      this.hoaDon = data;
      this.listLoading = false;
    },
    beforeUpload(file) {
      this.imagePost = file;
    },
    handleImageSuccess(file) {
      this.imgUrl = file.files.file;
    },
    async getHangXe() {
      const { data } = await lstHang({ limit: 1000 });
      this.hangXe = data.data;
    },
    async getNhomHang() {
      const { data } = await lstNhomHang({ limit: 1000 });
      this.nhomHang = data.data;
    },
    async getNCC() {
      const { data } = await lstNCC({ limit: 1000 });
      this.nhaCungCap = data.data;
    },
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
      };
    },
    handleCreate() {
      this.resetTemp();
      this.dialogStatus = 'create';
      this.activeTab = 'first';
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate();
      });
    },
    createData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          this.temp.id = ''; // mock a id
          if (this.imagePost) {
            this.temp.image = this.imagePost;
          }
          var formData = new FormData();
          for (var key in this.temp) {
            formData.append(key, this.temp[key]);
          }
          store(formData).then((res) => {
            this.getList();
            if (res.success) {
              this.dialogFormVisible = false;
              this.$notify({
                title: 'Success',
                message: res.message,
                type: 'success',
                duration: 2000,
              });
            } else {
              if (res[0] === 'error') {
                let messages = '';
                Object.values(res.message).forEach((error) => {
                  messages += '<div><strong>' + error[0] + '</strong></div>';
                });
                this.$notify({
                  title: 'Warning',
                  dangerouslyUseHTMLString: true,
                  message: messages,
                  type: 'warning',
                  duration: 7000,
                });
              } else {
                this.$notify({
                  title: 'Error',
                  message: res.message,
                  type: 'error',
                  duration: 5000,
                });
              }
            }
          });
        } else {
          this.$notify({
            title: 'Warning',
            dangerouslyUseHTMLString: true,
            message: '<b>1 số thông tin chưa đúng. Hãy nhập lại!</b>',
            type: 'warning',
            duration: 7000,
          });
        }
      });
    },
    handleUpdate(row) {
      this.temp = Object.assign({}, row); // copy obj
      this.temp.timestamp = new Date(this.temp.timestamp);
      this.imgUrl = this.getImgUrl(this.temp.img_path);
      this.dialogStatus = 'update';
      this.activeTab = 'first';
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate();
      });
    },
    updateData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          if (this.imagePost) {
            this.temp.image = this.imagePost;
          }
          var formData = new FormData();
          for (var key in this.temp) {
            formData.append(key, this.temp[key] ? this.temp[key] : '');
          }
          store(formData).then((res) => {
            this.getList();
            if (res.success) {
              this.dialogFormVisible = false;
              this.$notify({
                title: 'Success',
                message: res.message,
                type: 'success',
                duration: 2000,
              });
            } else {
              if (res[0] === 'error') {
                let messages = '';
                Object.values(res.message).forEach((error) => {
                  messages += '<div><strong>' + error[0] + '</strong></div>';
                });
                this.$notify({
                  title: 'Warning',
                  dangerouslyUseHTMLString: true,
                  message: messages,
                  type: 'warning',
                  duration: 7000,
                });
              } else {
                this.$notify({
                  title: 'Error',
                  message: res.message,
                  type: 'error',
                  duration: 5000,
                });
              }
            }
          });
        } else {
          this.$notify({
            title: 'Warning',
            dangerouslyUseHTMLString: true,
            message: '<b>1 số thông tin chưa đúng. Hãy nhập lại!</b>',
            type: 'warning',
            duration: 7000,
          });
        }
      });
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
