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
use App\Laravue\Models\SanPham;
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
            $date = isset($searchParams['date']) ? $searchParams['date'] : '';
            $month = isset($searchParams['month']) ? $searchParams['month'] : '';
            $title = isset($searchParams['title']) ? ($searchParams['title']) : '';
            $query = HoaDon::where(['user_id' => $currentUser->id])->whereNull('deleted_at');
            if (!empty($date)) {
                if($month) {
                    $arr_date = explode('-',$date);
                    if (count($arr_date) == 3) {
                        $query->whereMonth('ngay_ban', $arr_date[1])->whereYear('ngay_ban', $arr_date[0]);
                    }
                }
                else {
                    $query->whereDate('ngay_ban', $date);
                }
            }
            if(!empty($title)) {
                $query->where(function ($q) use ($title) {
                    $q->where('ten_khach_hang', 'like' , '%'.$title.'%')->orWhere('sdt', 'like', '%'.$title.'%');
                });
            }
            $data = $query->with(['chiTiet', 'chiTiet.sanPham'])->orderBy('ngay_ban', 'desc')->paginate($limit);
            return response()->json(['data' => $data], 200);
        }

    }

    public function store(Request $request) {
        $currentUser = Auth::user();
        $name = $request->get('name', '');
        $phone = $request->get('phone', '');
        $dia_chi = $request->get('dia_chi', '');
        $total = $request->get('total', '0');
        $delivery = $request->get('delivery', false);
        $data = $request->get('data', []);
        if(empty($data) || empty($name) || empty($phone)) {
            return response()->json(['message' => 'Tham số không đẩy đủ',"success" => false]);
        }
        $pass = false;
        DB::transaction(function() use($name, $phone, $data, $delivery, $total, &$pass, $currentUser, $dia_chi, $request) {
            try {
                $hoa_don_new = new HoaDon();
                $hoa_don_new->user_id = $currentUser->id;
                $hoa_don_new->ten_khach_hang = $name;
                $hoa_don_new->sdt = $phone;
                $hoa_don_new->dia_chi = $dia_chi;
                $hoa_don_new->tong_tien = $total;
                $hoa_don_new->ngay_sinh = $request->get('ngay_sinh', '');
                $hoa_don_new->note = $request->get('note', '');
                $hoa_don_new->chuyen_khoan = $delivery == false ? 0 : 1;
                $hoa_don_new->ngay_ban = Carbon::now();
                $hoa_don_new->save();
                $id_new = $hoa_don_new->id;
                foreach($data as $v) {
                    $sp = SanPham::find($v['id']);
                    if (!empty($sp)) {
                        $values = ['ma_hoa_don' => $id_new, 'user_id' => $currentUser->id, 'ma_san_pham' => $v['id'], 'gia_ban' => $v['gia_ban'], 'so_luong' => $v['soluong']];
                        ChiTietHoaDon::create($values);
                        $sp->so_luong_con_lai = $sp->so_luong_con_lai - (int)$v['soluong'];
                        $sp->save();
                    }
                    else {
                        DB::rollback();
                        return response()->json(['message' => "Đã có sản phẩm không tồn tại trong hệ thống!","success" => false]);
                    }
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
}
