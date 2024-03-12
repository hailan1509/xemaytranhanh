<template>
  <div class="app-container">
    <el-form ref="dataForm" :rules="rules" :model="form" label-width="150px">
      <el-row>
        <el-col :xs="24" :sm="12" :lg="24">
          <label class="label" for="">Thông tin người dùng:</label>
        </el-col>
      </el-row>
      <el-row>
        <el-col :xs="24" :sm="12" :lg="24">
          <el-form-item label="Tên cửa hàng" prop="ten_cua_hang">
            <el-col :span="20">
              <el-input v-model="form.ten_cua_hang" placeholder="Tên cửa hàng" />
            </el-col>
          </el-form-item>
        </el-col>
        <el-col :xs="24" :sm="12" :lg="24">
          <el-form-item label="Số điện thoại" prop="phone">
            <el-col :span="20">
              <el-input v-model="form.phone" placeholder="Số điện thoại" />
            </el-col>
          </el-form-item>
        </el-col>
        <el-col :xs="24" :sm="12" :lg="24">
          <el-form-item label="Địa chỉ cửa hàng" prop="dia_chi">
            <el-col :span="20">
              <el-input v-model="form.dia_chi" placeholder="Địa chỉ" />
            </el-col>
          </el-form-item>
        </el-col>
        <el-col :xs="24" :sm="12" :lg="24">
          <el-form-item label="Ngân hàng" prop="loai_ngan_hang">
            <el-col :span="20">
              <v-select v-model="form.loai_ngan_hang" class="el-select filter-item el-select--medium" style="width: 300px" :options="banks" :menu-props="{ contentClass: 'filter-item' }" label="name" placeholder="Tìm kiếm ngân hàng" :reduce="option => option.value" />
            </el-col>
          </el-form-item>
        </el-col>
        <el-col :xs="24" :sm="12" :lg="24">
          <el-form-item label="Số tài khoản" prop="stk">
            <el-col :span="20">
              <el-input v-model="form.stk" placeholder="Số tài khoản" />
            </el-col>
          </el-form-item>
        </el-col>
        <el-col :xs="24" :sm="12" :lg="24">
          <el-form-item label="Nội dung CK">
            <el-col :span="20">
              <el-input v-model="form.noi_dung_ck" placeholder="Nội dung chuyển khoản ghi không dấu" />
            </el-col>
          </el-form-item>
        </el-col>
        <el-col :xs="24" :sm="24" :lg="24">
          <el-form-item label="">
            <el-button type="primary" size="mini" @click="save()">
              Thay đổi
            </el-button>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :xs="24" :sm="12" :lg="24">
          <label class="label" for="">Đổi mật khẩu:</label>
        </el-col>
        <el-col :xs="24" :sm="12" :lg="24">
          <el-form-item label="Mật khẩu mới" prop="password">
            <el-col :span="20">
              <el-input v-model="form.password" placeholder="Mật khẩu mới" />
            </el-col>
          </el-form-item>
        </el-col>
        <el-col :xs="24" :sm="12" :lg="24">
          <el-form-item label="Nhập lại mật khẩu" prop="confirmPassword">
            <el-col :span="20">
              <el-input v-model="form.confirmPassword" placeholder="Nhập lại mật khẩu" />
            </el-col>
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
  </div>
</template>

<script>

import { getInfo, editProfile } from '@/api/users';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
export default {
  name: 'SelfProfile',
  components: { vSelect },
  data() {
    return {
      form: {
        phone: '',
        ten_cua_hang: '',
        dia_chi: '',
        note: '',
        loai_ngan_hang: '',
        noi_dung_ck: '',
        stk: '',
        password: '',
        confirmPassword: '',
      },
      user: {},
      banks: [],
      rules: {
        ten_cua_hang: [{ required: true, message: 'Vui lòng nhập tên cửa hàng!', trigger: 'input' }],
        loai_ngan_hang: [{ required: true, message: 'Vui lòng chọn ngân hàng!', trigger: 'change' }],
        stk: [{ required: true, message: 'Vui lòng nhập số tài khoản!', trigger: 'input' }],
        dia_chi: [{ required: true, message: 'Vui lòng nhập địa chỉ!', trigger: 'input' }],
        phone: [{ required: true, message: 'Vui lòng nhập số điện thoại!', trigger: 'input' }],
      },
    };
  },
  watch: {
    '$route': 'getUser',
  },
  created() {
    this.banks = [
      { value: 'ICB', name: '(970415) VietinBank' }, { value: 'VCB', name: '(970436) Vietcombank' }, { value: 'BIDV', name: '(970418) BIDV' }, { value: 'VBA', name: '(970405) Agribank' }, { value: 'OCB', name: '(970448) OCB' }, { value: 'MB', name: '(970422) MBBank' }, { value: 'TCB', name: '(970407) Techcombank' }, { value: 'ACB', name: '(970416) ACB' }, { value: 'VPB', name: '(970432) VPBank' }, { value: 'TPB', name: '(970423) TPBank' }, { value: 'STB', name: '(970403) Sacombank' }, { value: 'HDB', name: '(970437) HDBank' }, { value: 'VCCB', name: '(970454) VietCapitalBank' }, { value: 'SCB', name: '(970429) SCB' }, { value: 'VIB', name: '(970441) VIB' }, { value: 'SHB', name: '(970443) SHB' }, { value: 'EIB', name: '(970431) Eximbank' }, { value: 'MSB', name: '(970426) MSB' }, { value: 'CAKE', name: '(546034) CAKE' }, { value: 'Ubank', name: '(546035) Ubank' }, { value: 'TIMO', name: '(963388) Timo' }, { value: 'VTLMONEY', name: '(971005) ViettelMoney' }, { value: 'VNPTMONEY', name: '(971011) VNPTMoney' }, { value: 'SGICB', name: '(970400) SaigonBank' }, { value: 'BAB', name: '(970409) BacABank' }, { value: 'PVCB', name: '(970412) PVcomBank' }, { value: 'Oceanbank', name: '(970414) Oceanbank' }, { value: 'NCB', name: '(970419) NCB' }, { value: 'SHBVN', name: '(970424) ShinhanBank' }, { value: 'ABB', name: '(970425) ABBANK' }, { value: 'VAB', name: '(970427) VietABank' }, { value: 'NAB', name: '(970428) NamABank' }, { value: 'PGB', name: '(970430) PGBank' }, { value: 'VIETBANK', name: '(970433) VietBank' }, { value: 'BVB', name: '(970438) BaoVietBank' }, { value: 'SEAB', name: '(970440) SeABank' }, { value: 'COOPBANK', name: '(970446) COOPBANK' }, { value: 'LPB', name: '(970449) LienVietPostBank' }, { value: 'KLB', name: '(970452) KienLongBank' }, { value: 'KBank', name: '(668888) KBank' }, { value: 'KBHN', name: '(970462) KookminHN' }, { value: 'KEBHANAHCM', name: '(970466) KEBHanaHCM' }, { value: 'KEBHANAHN', name: '(970467) KEBHANAHN' }, { value: 'MAFC', name: '(977777) MAFC' }, { value: 'CITIBANK', name: '(533948) Citibank' }, { value: 'KBHCM', name: '(970463) KookminHCM' }, { value: 'VBSP', name: '(999888) VBSP' }, { value: 'WVN', name: '(970457) Woori' }, { value: 'VRB', name: '(970421) VRB' }, { value: 'UOB', name: '(970458) UnitedOverseas' }, { value: 'SCVN', name: '(970410) StandardChartered' }, { value: 'PBVN', name: '(970439) PublicBank' }, { value: 'NHB HN', name: '(801011) Nonghyup' }, { value: 'IVB', name: '(970434) IndovinaBank' }, { value: 'IBK - HCM', name: '(970456) IBKHCM' }, { value: 'IBK - HN', name: '(970455) IBKHN' }, { value: 'HSBC', name: '(458761) HSBC' }, { value: 'HLBVN', name: '(970442) HongLeong' }, { value: 'GPB', name: '(970408) GPBank' }, { value: 'DOB', name: '(970406) DongABank' }, { value: 'DBS', name: '(796500) DBSBank' }, { value: 'CIMB', name: '(422589) CIMB' }, { value: 'CBB', name: '(970444) CBBank' },
    ];
    this.getUser();
  },
  methods: {
    async getUser() {
      this.listLoading = true;
      const { data } = await getInfo();
      this.form = data;
      this.listLoading = false;
    },
    save() {
      this.$confirm('Bạn có chắc muốn thay đổi?', 'Cảnh báo', {
        confirmButtonText: 'Có',
        cancelButtonText: 'Không',
        type: 'warning',
      })
        .then(async() => {
          editProfile(this.form).then((res) => {
            this.$notify({
              title: res.success ? 'Xong' : 'Lỗi',
              message: res.message,
              type: res.success ? 'success' : 'error',
              duration: 2000,
            });
          });
        });
    },
    changePass() {
      this.$confirm('Bạn có chắc muốn thay đổi?', 'Cảnh báo', {
        confirmButtonText: 'Có',
        cancelButtonText: 'Không',
        type: 'warning',
      })
        .then(async() => {
          editProfile({ is_change_pass: 1, ...this.form }).then((res) => {
            this.$notify({
              title: res.success ? 'Xong' : 'Lỗi',
              message: res.message,
              type: res.success ? 'success' : 'error',
              duration: 2000,
            });
          });
        });
    },
  },
};
</script>
