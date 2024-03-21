<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Giấy biên nhận</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
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
        .text-right {
            text-align: right !important;
        }
        .w50 {
            width: 50% !important;
        }
    </style>
</head>
<body>
   <div class="section">
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
        <div class="col-md-12">
            <span class="text-left w50">Bán cho ông(bà): </span>
            <span class="text-righ mr0">Điện thoại:</span>
        </div>
    </div>
   </div>
</body>
<script>
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
    });
</script>
</html>