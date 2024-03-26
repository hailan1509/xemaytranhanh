<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Giấy biên nhận</title>
    
    
</head>
<body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" integrity="sha512-qZvrmS2ekKPF2mSznTQsxqPgnpkI4DNTlrdUmTzrDgektczlKNRRhy5X5AAOnx5S09ydFYWWNSfcEqDTTHgtNA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.3.3/purify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        * {
            font-family: 'Times New Roman';
            font-size: 14px;
        }
        .bold {
            font-weight: bold !important;
        }
        .section {
            margin-left: 10px !important;
            margin-right: 10px !important;
        }
        .text-left {
            text-align: left !important;
        }
        .w150 {
            width: 150px !important;
        }
        .only-print {
            display: none;
        }
        input.print-hidden, .print-hidden input[type="text"] {
            border: 1px dotted !important;
            color: #7ac54b;
            width: 100%;
        }
        .print-hidden {
            display: inline;
        }
        .ml-10 {
            margin-left: 10px;
        }
    </style>
   <div class="section">
    <div class="row ">
        <div class="col-md-12">
            <button class="btn btn-success ml-10" onclick="saveFile()">Lưu lại</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <span class="h2 bold" id="ten_cua_hang"></span><br>
            <span class="h3" id="dia_chi">Đ/c: </span><br>
            <span class="h3" id="sdt">ĐT: </span><br>
            <span class="h3" id="ngan_hang">Ngân hàng </span><br>
            <span class="h2 bold">GIẤY BIÊN NHẬN BÁN HÀNG</span>
        </div>
        <div class="col-md-12">
            <span><b>Sau khi 2 bên thoả thuận mua, bán xe xong cửa hàng chúng tôi nhất trí:</b></span>
        </div>
        <div class="col-md-6 col-sm-6">
            <span class="text-left">Bán cho ông(bà): <input id="ip_ten_khach_hang" class="print-hidden" onchange="changeInput(this, 'ten_khach_hang')" type="text" placeholder="Nhập tên khách hàng">
                <span id="ten_khach_hang" class="only-print"></span></span>
        </div>
        <div class="col-md-6 col-sm-6">
            <span class=" mr0">Điện thoại: <input id="ip_so_dien_thoai" type="text" class="print-hidden" onchange="changeInput(this, 'so_dien_thoai')"  placeholder="Nhập số điện thoại">
                <span id="so_dien_thoai" class="only-print"></span></span>
        </div>
        <div class="col-md-12 col-sm-12">
            <span class="text-left">Địa chỉ: <input id="ip_address" type="text" class="print-hidden" onchange="changeInput(this, 'address')"  placeholder="Nhập địa chỉ">
                <span id="address" class="only-print"></span></span>
        </div>
        <div class="col-md-6 col-sm-6">
            <span class=" mr0">Nhãn hiệu xe: <input id="ip_nhan_xe" type="text" class="print-hidden" onchange="changeInput(this, 'nhan_xe')"  placeholder="Nhập nhãn hiệu xe">
                <span id="nhan_xe" class="only-print"></span></span>
        </div>
        <div class="col-md-6 col-sm-6">
            <span class="text-left">Màu sơn: <input type="text" class="print-hidden" onchange="changeInput(this, 'mau_son')"  placeholder="Nhập màu sơn">
                <span id="mau_son" class="only-print"></span></span>
        </div>
        <div class="col-md-6 col-sm-6">
            <span class=" mr0">Số khung - Số máy: <input id="ip_sk_sm" type="text" class="print-hidden" onchange="changeInput(this, 'sk_sm')"  placeholder="Nhập số khung số máy">
                <span id="sk_sm" class="only-print"></span></span>
        </div>
        <div class="col-md-6 col-sm-6">
            <span class="text-left">Biển số: <input type="text" class="print-hidden" onchange="changeInput(this, 'bien_so')"  placeholder="Nhập biển số">
                <span id="bien_so" class="only-print"></span></span>
        </div>
        <div class="col-md-12 col-sm-12">
            <span class=" mr0">Đăng ký: <input type="text" class="print-hidden" onchange="changeInput(this, 'dang_ky')"  placeholder="Nhập đăng ký">
                <span id="dang_ky" class="only-print"></span></span>
        </div>
        <div class="col-md-12 col-sm-12">
            <span class=" mr0">Giá bán: <input type="text" id="ip_gia_ban" class="print-hidden" oninput="changeInputGia(this, 'gia_ban')"  placeholder="Nhập giá bán">
                <span id="gia_ban" class="only-print"></span></span>
        </div>
        <div class="col-md-12 col-sm-12">
            <span class=" mr0">(Bằng chữ): <span id="str_gia_ban"></span></span>
        </div>
        <div class="col-md-12 col-sm-12">
            <span class=" mr0">Đã thanh toán: <input type="text" id="ip_da_thanh_toan" class="print-hidden" oninput="changeInputGia(this, 'da_thanh_toan')"  placeholder="Nhập số tiền đã thanh toán">
                <span id="da_thanh_toan" class="only-print"></span></span>
        </div>
        <div class="col-md-12 col-sm-12">
            <span class=" mr0">(Bằng chữ): <span id="str_da_thanh_toan"></span></span>
        </div>
        <div class="col-md-12 col-sm-12">
            <span class=" mr0">Còn thiếu: <input id="ip_con_thieu" type="text" class="print-hidden" onchange="changeInputGia(this, 'con_thieu', false)"  placeholder="Nhập số tiền còn thiếu">
                <span id="con_thieu" class="only-print"></span></span>
        </div>
        <div class="col-md-12 col-sm-12">
            <span class=" mr0">Hẹn lấy giấy tờ (đăng ký) <input type="text" class="print-hidden w150" onchange="changeInput(this, 'ngay_hen')" value="ngày .... tháng .... năm ....    "> <span class="only-print" id="ngay_hen">ngày .... tháng .... năm .... &nbsp;&nbsp;</span> thanh toán hết số tiền còn lại. Nếu quý khách không đúng hẹn, cửa hàng tính lãi suất 2%/ngày theo thoả thuận.</span>
        </div>
        <div class="col-md-12">
            <span><b><u>Quý khách lưu ý: </u></b> Trước khi bàn giao xe, quý khách vui lòng kiểm tra lại thông tin. Nếu sau này có gì sai sót cửa hàng không chịu trách nhiệm!</span>
        </div>
        <div class="col-md-6 col-sm-6"></div>
        <div class="col-md-6 col-sm-6 text-center">
            <span>
                <i><input class="print-hidden w150" oninput="changeInput(this, 'location')" type="text" value="Hải Dương,"><span id="location" class="only-print">Hải Dương,</span></i>
            </span>
            <span>
                <input class="print-hidden w150" placeholder="ngày ... tháng ... năm ..." oninput="changeInput(this, 'sig_date')" type="text" value="ngày .... tháng .... năm .... "><span id="sig_date" class="only-print">ngày .... tháng .... năm .... </span>
            </span>
        </div>
        <div class="col-md-6 col-sm-6 text-center"><span><b>ĐẠI DIỆN BÊN MUA</b></span></div>
        <div class="col-md-6 col-sm-6 text-center"><span><b>ĐẠI DIỆN CỬA HÀNG</b></span></div>
        <div class="col-md-12">&nbsp;</div>
        <div class="col-md-12">&nbsp;</div>
        <div class="col-md-12">&nbsp;</div>
        <div class="col-md-12">&nbsp;</div>
        <div class="col-md-12">
            <span><b>GIẤY TỜ GỒM CÓ:</b></span>
        </div>
        <div class="col-md-6 col-sm-6">
            <span>
                <input class="print-hidden" oninput="changeInput(this, 'note_1')" type="text" value="1. ...............................................................">
                <span id="note_1" class="only-print">1. ...............................................................</span>
            </span>
        </div>
        <div class="col-md-6 col-sm-6">
            <span>
                <input class="print-hidden" oninput="changeInput(this, 'note_2')" type="text" value="2. ...............................................................">
                <span id="note_2" class="only-print">2. ...............................................................</span>
            </span>
        </div>
        <div class="col-md-6 col-sm-6">
            <span>
                <input class="print-hidden" oninput="changeInput(this, 'note_3')" type="text" value="3. ...............................................................">
                <span id="note_3" class="only-print">3. ...............................................................</span>
            </span>
        </div>
        <div class="col-md-6 col-sm-6">
            <span>
                <input class="print-hidden" oninput="changeInput(this, 'note_4')" type="text" value="4. ...............................................................">
                <span id="note_4" class="only-print">4. ...............................................................</span>
            </span>
        </div>
        <div class="col-md-6 col-sm-6">
            <span>
                <input class="print-hidden" oninput="changeInput(this, 'note_5')" type="text" value="5. ...............................................................">
                <span id="note_5" class="only-print">5. ...............................................................</span>
            </span>
        </div>
        <div class="col-md-6 col-sm-6">
            <span>
                <input class="print-hidden" oninput="changeInput(this, 'note_6')" type="text" value="6. ...............................................................">
                <span id="note_6" class="only-print">6. ...............................................................</span>
            </span>
        </div>
        <div class="col-md-12 text-center">
            <span><i>Kính chúc quý khách thượng lộ - bình an</i></span>
        </div>
        <div class="col-md-12 text-center">
            <span><b>QUÝ KHÁCH CÓ NHU CẦU THAY ĐỔI XE, CỬA HÀNG XIN MUA LẠI VỚI GIÁ ƯU ĐÃI!</b></span>
        </div>
    </div>
   </div>
</body>
<style>
    @media print {
        .only-print {
            display: inline !important;
        }
        .print-hidden {
            display: none !important;
        }
        * {
            font-size: 17px !important;
        }
        button {
            display: none !important;
        }
    }
</style>
<script>
    const { jsPDF } = window.jspdf;
    var banks = [
      { value: 'ICB', name: '(970415) VietinBank' }, { value: 'VCB', name: '(970436) Vietcombank' }, { value: 'BIDV', name: '(970418) BIDV' }, { value: 'VBA', name: '(970405) Agribank' }, { value: 'OCB', name: '(970448) OCB' }, { value: 'MB', name: '(970422) MBBank' }, { value: 'TCB', name: '(970407) Techcombank' }, { value: 'ACB', name: '(970416) ACB' }, { value: 'VPB', name: '(970432) VPBank' }, { value: 'TPB', name: '(970423) TPBank' }, { value: 'STB', name: '(970403) Sacombank' }, { value: 'HDB', name: '(970437) HDBank' }, { value: 'VCCB', name: '(970454) VietCapitalBank' }, { value: 'SCB', name: '(970429) SCB' }, { value: 'VIB', name: '(970441) VIB' }, { value: 'SHB', name: '(970443) SHB' }, { value: 'EIB', name: '(970431) Eximbank' }, { value: 'MSB', name: '(970426) MSB' }, { value: 'CAKE', name: '(546034) CAKE' }, { value: 'Ubank', name: '(546035) Ubank' }, { value: 'TIMO', name: '(963388) Timo' }, { value: 'VTLMONEY', name: '(971005) ViettelMoney' }, { value: 'VNPTMONEY', name: '(971011) VNPTMoney' }, { value: 'SGICB', name: '(970400) SaigonBank' }, { value: 'BAB', name: '(970409) BacABank' }, { value: 'PVCB', name: '(970412) PVcomBank' }, { value: 'Oceanbank', name: '(970414) Oceanbank' }, { value: 'NCB', name: '(970419) NCB' }, { value: 'SHBVN', name: '(970424) ShinhanBank' }, { value: 'ABB', name: '(970425) ABBANK' }, { value: 'VAB', name: '(970427) VietABank' }, { value: 'NAB', name: '(970428) NamABank' }, { value: 'PGB', name: '(970430) PGBank' }, { value: 'VIETBANK', name: '(970433) VietBank' }, { value: 'BVB', name: '(970438) BaoVietBank' }, { value: 'SEAB', name: '(970440) SeABank' }, { value: 'COOPBANK', name: '(970446) COOPBANK' }, { value: 'LPB', name: '(970449) LienVietPostBank' }, { value: 'KLB', name: '(970452) KienLongBank' }, { value: 'KBank', name: '(668888) KBank' }, { value: 'KBHN', name: '(970462) KookminHN' }, { value: 'KEBHANAHCM', name: '(970466) KEBHanaHCM' }, { value: 'KEBHANAHN', name: '(970467) KEBHANAHN' }, { value: 'MAFC', name: '(977777) MAFC' }, { value: 'CITIBANK', name: '(533948) Citibank' }, { value: 'KBHCM', name: '(970463) KookminHCM' }, { value: 'VBSP', name: '(999888) VBSP' }, { value: 'WVN', name: '(970457) Woori' }, { value: 'VRB', name: '(970421) VRB' }, { value: 'UOB', name: '(970458) UnitedOverseas' }, { value: 'SCVN', name: '(970410) StandardChartered' }, { value: 'PBVN', name: '(970439) PublicBank' }, { value: 'NHB HN', name: '(801011) Nonghyup' }, { value: 'IVB', name: '(970434) IndovinaBank' }, { value: 'IBK - HCM', name: '(970456) IBKHCM' }, { value: 'IBK - HN', name: '(970455) IBKHN' }, { value: 'HSBC', name: '(458761) HSBC' }, { value: 'HLBVN', name: '(970442) HongLeong' }, { value: 'GPB', name: '(970408) GPBank' }, { value: 'DOB', name: '(970406) DongABank' }, { value: 'DBS', name: '(796500) DBSBank' }, { value: 'CIMB', name: '(422589) CIMB' }, { value: 'CBB', name: '(970444) CBBank' },
    ];
    $(document).ready(function () {
        $.get('api/user/getInfo', function (resp) {
            if (resp.data) {
                const data = resp.data;
                $('#ten_cua_hang').html(data.ten_cua_hang.toUpperCase());
                $('#dia_chi').append(data.dia_chi.toUpperCase());
                $('#sdt').append(data.phone.toUpperCase());
                const bank = banks.find(v => v.value == data.loai_ngan_hang);
                if (bank && data.loai_ngan_hang) {
                    $('#ngan_hang').append(bank.name.split(' ')[1] + ': ' + data.stk);
                }

            }
        });
        var urlParams = new URLSearchParams(window.location.search);
        var id = urlParams.get('id');
        $.get('api/hoa-don/chi-tiet?id=' + id, function (resp) {
            const data = resp.data;
            $('#ip_ten_khach_hang').val(data.ten_khach_hang);
            $('#ten_khach_hang').html(data.ten_khach_hang);
            $('#ip_so_dien_thoai').val(data.sdt);
            $('#so_dien_thoai').html(data.sdt);
            $('#ip_address').val(data.dia_chi);
            $('#address').html(data.dia_chi);
            if (data.chi_tiet.length > 0) {
                const infoSP = data.chi_tiet[0];
                $('#ip_nhan_xe').val(infoSP.san_pham.name);
                $('#nhan_xe').html(infoSP.san_pham.name);
                $('#ip_sk_sm').val(infoSP.san_pham.short_name);
                $('#sk_sm').html(infoSP.san_pham.short_name);
                $('#ip_gia_ban').val(infoSP.gia_ban);
                $('#gia_ban').html(formatMoney(infoSP.gia_ban));
                $("#str_gia_ban").html(_convert_number_to_words(infoSP.gia_ban));
            }

        })
    });
    function changeInput(e, idSpan) {
        $("#"+ idSpan).html(e.value);
    }
    function changeInputGia(e, idSpan, hasStrMoney = true) {
        $("#"+ idSpan).html(formatMoney(e.value));
        if(hasStrMoney) {
            $("#str_"+ idSpan).html(_convert_number_to_words(e.value));
            if ($("#ip_gia_ban").val().toString().length > 0 && $("#ip_da_thanh_toan").val().toString().length > 0 ) {
                $("#ip_con_thieu").val(parseInt($("#ip_gia_ban").val()) - parseInt($("#ip_da_thanh_toan").val()));
                $("#con_thieu").html(formatMoney($("#ip_con_thieu").val()))
            }
        }
    }
    function _convert_number_to_words(number) {
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

      return toUpperCaseFirst(string.replace(['mươi năm', 'mươi một'], ['mươi lăm', 'mươi mốt']));
    }
    function toUpperCaseFirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
    function formatMoney(money) {
        const config = { style: 'currency', currency: 'VND', maximumFractionDigits: 9}
        const formated = new Intl.NumberFormat('vi-VN', config).format(money);
        return formated;
    }
    function saveFile() {
        document.querySelectorAll('button').forEach(function(button) {
            button.style.display = 'none'; // Ẩn button
        });

        document.querySelectorAll('.print-hidden').forEach(function(ele) {
            ele.style.display = 'none';
        });
        document.querySelectorAll('.only-print').forEach(function(ele) {
            ele.style.display = 'inline';
        });

        // Tạo một instance của jsPDF
        var doc = new jsPDF();

        // Lấy nội dung HTML của trang web
        var htmlContent = document.documentElement.outerHTML;

        html2canvas(document.documentElement).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF('p', 'pt', 'a4');
            const imgWidth = pdf.internal.pageSize.getWidth();
            const imgHeight = (canvas.height * imgWidth) / canvas.width;
            pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
            const pdfBlob = pdf.output('blob');

            // Tạo URL Blob từ Blob đã tạo
            const blobUrl = URL.createObjectURL(pdfBlob);
            const formData = new FormData();
            formData.append('file', pdfBlob);

            $.ajax({
                url: 'api/hoa-don/save-file',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    document.querySelectorAll('.print-hidden').forEach(function(ele) {
                        ele.style.display = 'none';
                    });
                    document.querySelectorAll('.only-print').forEach(function(ele) {
                        ele.style.display = 'inline';
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Failed to send PDF:', error);
                }
            });
        });

        // Thêm nội dung HTML vào tệp PDF
        // doc.html(htmlContent, {
        //     callback: function(doc) {
        //         // Lấy dữ liệu của tệp PDF dưới dạng dữ liệu URL
        //         // var pdfDataUri = doc.output('blob');
        //         doc.save('abc.pdf');
        //         var blobPDF =  new Blob([ doc.output() ], { type : 'application/pdf'});
        //         var blobUrl = URL.createObjectURL(blobPDF);  //<--- THE ERROR APPEARS HERE

        //         window.open(blobUrl); 
        //         // Gửi tệp PDF lên API bằng axios hoặc thực hiện các thao tác khác tùy thuộc vào yêu cầu của bạn
        //         axios.post('api/hoa-don/save-file', { file: pdfDataUri })
        //             .then(function(response) {
        //                 // Xử lý phản hồi từ API hoặc thực hiện các thao tác khác tùy thuộc vào yêu cầu của bạn
        //                 console.log('PDF đã được gửi thành công.');
        //                 document.querySelectorAll('.print-hidden').forEach(function(ele) {
        //                     ele.style.display = 'none';
        //                 });
        //                 document.querySelectorAll('.only-print').forEach(function(ele) {
        //                     ele.style.display = 'inline';
        //                 });
        //             })
        //             .catch(function(error) {
        //                 // Xử lý lỗi nếu có hoặc thực hiện các thao tác khác tùy thuộc vào yêu cầu của bạn
        //                 console.error('Đã xảy ra lỗi khi gửi PDF:', error);
        //             });
        //     }
        // });
    }
</script>
</html>