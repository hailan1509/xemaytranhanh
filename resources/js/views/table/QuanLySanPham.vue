<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.title" :placeholder="'Tên sản phẩm'" style="display: inline-block;width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-select v-model="listQuery.month" placeholder="Chọn tháng" style="display: inline-block;width: 200px;" class="filter-item">
        <el-option :key="1" :label="'Theo tháng'" :value="1" />
        <el-option :key="0" :label="'Theo ngày'" :value="0" />
      </el-select>
      <v-select v-model="listQuery.hang_xe" :options="hangXe" style="display: inline-block; width: 200px" :menu-props="{ contentClass: 'filter-item' }" label="name" placeholder="Tìm kiếm hãng" :reduce="option => option.id" />
      <v-select v-model="listQuery.nhom_hang" :options="nhomHang" style="display: inline-block; width: 200px" :menu-props="{ contentClass: 'filter-item' }" label="name" placeholder="Tìm kiếm nhóm hàng" :reduce="option => option.id" />
      <v-select v-model="listQuery.ncc" :options="nhaCungCap" style="display: inline-block; width: 200px" :menu-props="{ contentClass: 'filter-item' }" label="name" placeholder="Tìm kiếm nhà cung cấp" :reduce="option => option.id" />
      <el-date-picker v-model="listQuery.date" type="date" format="dd/MM/yyyy" value-format="yyyy-MM-dd" placeholder="Xem theo ngày" style="width: 150px;" class="filter-item" />
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
      <el-table-column :label="''" prop="id" align="center" style="width: 40%;">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="'Ghi chú'" style="width: 20%;" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.note }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.actions')" align="center" style="width: 40%;" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button type="primary" size="mini" @click="handleUpdate(row)">
            {{ $t('table.edit') }}
          </el-button>
          <el-button v-if="row.status!='deleted'" size="mini" type="danger" @click="handleDelete(row)">
            {{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />

    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="150px" style="width: 100%; margin-left:10px;">
        <el-tabs style="margin-top:15px;" type="border-card">
          <el-tab-pane :label="'Thông tin sản phẩm'">
            <el-form-item :label="'Tên sản phẩm'" prop="title">
              <el-input v-model="temp.name" />
            </el-form-item>
            <el-form-item :label="'Hãng xe'" prop="title">
              <v-select v-model="temp.hang_xe" :options="hangXe" style="display: inline-block; width: 200px" :menu-props="{ contentClass: 'filter-item' }" label="name" placeholder="Chọn hãng xe" :reduce="option => option.id" />
            </el-form-item>
            <el-form-item :label="'Nhóm hàng'" prop="title">
              <v-select v-model="temp.nhom_hang" :options="nhomHang" style="display: inline-block; width: 200px" :menu-props="{ contentClass: 'filter-item' }" label="name" placeholder="Chọn nhóm" :reduce="option => option.id" />
            </el-form-item>
            <el-form-item :label="'Nhà cung cấp'" prop="title">
              <v-select v-model="temp.ncc" :options="nhaCungCap" style="display: inline-block; width: 200px" :menu-props="{ contentClass: 'filter-item' }" label="name" placeholder="Chọn nhà cung cấp" :reduce="option => option.id" />
            </el-form-item>
            <el-form-item :label="'Ngày nhập'" prop="title">
              <el-date-picker v-model="temp.ngay_nhap" type="date" format="dd/MM/yyyy" value-format="yyyy-MM-dd" placeholder="Chọn ngày nhập hàng" style="width: 200px;" class="filter-item" />
            </el-form-item>
          </el-tab-pane>
          <el-tab-pane :label="'Giá - Số lượng'">
            <el-form-item :label="'Giá nhập'" prop="title">
              <el-input v-model="temp.gia_nhap" />
            </el-form-item>
            <el-form-item :label="'Giá bán'" prop="title">
              <el-input v-model="temp.gia_ban" />
            </el-form-item>
            <el-form-item :label="'Số lượng nhập'" prop="title">
              <el-input v-model="temp.so_luong_nhap" />
            </el-form-item>
            <el-form-item :label="'Số lượng còn lại'" prop="title">
              <el-input v-model="temp.so_luong_con_lai" />
            </el-form-item>

          </el-tab-pane>
          <el-tab-pane :label="'Thêm'">
            <el-form-item :label="'Phương thức nhập'" prop="title">
              <el-input v-model="temp.phuong_thuc_nhap" />
            </el-form-item>
            <el-form-item :label="'Ghi chú'" prop="title">
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
import { fetchList, store, del } from '@/api/san-pham';
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
        name: [{ required: true, message: 'type is required', trigger: 'change' }],
      },
      downloadLoading: false,
      hangXe: [],
      nhomHang: [],
      nhaCungCap: [],
      additionalData: {},
      imgUrl: '',
      imagePost: {},
    };
  },
  created() {
    this.getHangXe();
    this.getNhomHang();
    this.getNCC();
    this.getList();
  },
  methods: {
    beforeUpload(file) {
      this.imagePost = file;
    },
    handleImageSuccess(file) {
      this.imgUrl = file.files.file;
    },
    async getHangXe() {
      const { data } = await lstHang({ limit: 1000 });
      this.hangXe = data.data;
      console.log(data.data);
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
          this.temp.image = this.imagePost;
          var formData = new FormData();
          for (var key in this.temp) {
            formData.append(key, this.temp[key]);
          }
          store(formData).then((res) => {
            this.list.unshift(this.temp);
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
            for (const v of this.list) {
              if (v.id === this.temp.id) {
                const index = this.list.indexOf(v);
                this.list.splice(index, 1, this.temp);
                break;
              }
            }
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
  },
};
</script>
