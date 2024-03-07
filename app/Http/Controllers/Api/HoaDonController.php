<?php
/**
 * File UserController.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version 1.0
 */

namespace App\Http\Controllers\Api;

use App\Laravue\Models\HoaDon;
use App\Laravue\Models\KhachHang;
use App\Laravue\Models\ChiTietHoaDon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Carbon\Carbon;

/**
 * Class UserController
 *
 * @package App\Http\Controllers\Api
 */
class HoaDonController extends BaseController
{
    const ITEM_PER_PAGE = 15;
    
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $currentUser = Auth::user();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $query = [];
        if(isset($searchParams['id'])) {
            $query = HoaDon::find($searchParams['id']);
            return response()->json(['data' => $query], 200);
        }
        else{
            $date = $searchParams['date'];
            $month = $searchParams['month'];
            $phone = $searchParams['phone'];
            $query = HoaDon::where('user_id', $currentUser->id);
            if (!empty($date)) {
                if($month) {
                    $arr_date = explode('/',$date);
                    $query->whereMonth('ngay', $arr_date[1])->whereYear('ngay', $arr_date[0]);
                }
                else {
                    $query->whereDate('ngay', $date);
                }
            }
            if(!empty($phone)) {
                $query->where('sdt', $phone);
            }
            $data = $query->orderBy('ngay_ban')->paginate($limit);
            return response()->json(['data' => $data], 200);
        }

    }

    public function store(Request $request) {
        $currentUser = Auth::user();
        $name = $request->get('name', '');
        $phone = $request->get('phone', '');
        $dia_chi = $request->get('dia_chi', '');
        $total = $request->get('total', '0');
        $delivery = $request->get('delivery', 'false');
        $data = $request->get('data', []);
        if(empty($data) || empty($name) || empty($phone)) {
            return response()->json(['message' => 'Tham số không đẩy đủ',"success" => false]);
        }
        $pass = false;
        DB::transaction(function() use($name, $phone, $data, $delivery, $total, &$pass, $currentUser, $dia_chi) {
            try {
                $hoa_don_new = new HoaDon();
                $hoa_don_new = $currentUser->id;
                $hoa_don_new->ten_khach_hang = $name;
                $hoa_don_new->sdt = $phone;
                $hoa_don_new->dia_chi = $dia_chi;
                $hoa_don_new->tong_tien = $total;
                $hoa_don_new->chuyen_khoan = $delivery == 'false' ? '0' : '1';
                $hoa_don_new->ngay = Carbon::now();
                $hoa_don_new->save();
                $id_new = $hoa_don_new->id;
                foreach($data as $v) {
                    $values = ['ma_hoa_don' => $id_new, 'user_id' => $currentUser->id, 'ma_san_pham' => $v['id'], 'gia_ban' => $v['gia'], 'soluong' => $v['soluong'], 'note' => $v['note']];
                    ChiTietHoaDon::create($values);
                }
                $pass = true;
                DB::commit();
            }
            catch (\Exception $e){
                dd($e);
                DB::rollback();
                return response()->json(['message' => "Đã có lỗi xảy ra! Hãy thao tác lại","success" => false]);
            }
        });
        if($pass)
            return response()->json(['message' => "Thành công !","success" => true]);
        else return response()->json(['message' => "Đã có lỗi xảy ra! Hãy thao tác lại","success" => false]);
    }

    public function delete(Request $request) {
        $id = $request->get("id","");
        try{
            if(!empty($id)) {
                $query = HoaDon::where('id',$id)->update(['deleted_at' => Carbon::now()]);
            }
            return response()->json(['message' => "Thành công !","success" => true]);
        }catch (\Exception $ex) {
            return response()->json(['message' => $ex,"success" => false]);
        }
        

    }

    public function getHoaDonByPhong(Request $request) {
        $ma_phong = $request->get("ma_phong",0);
        $month = $request->get("month",date('m'));

        $hd = HoaDon::select(['hoa_don.*','phong.ten_phong','nguoi_thue.ten'])->where(["ma_phong" => $ma_phong,"month" =>$month])->leftJoin('phong','hoa_don.ma_phong','phong.id')
        ->leftJoin('nguoi_thue','hoa_don.ma_nguoi_thue','nguoi_thue.id')->first();

        return response()->json(['data' => $hd], 200);

    }

    
}
