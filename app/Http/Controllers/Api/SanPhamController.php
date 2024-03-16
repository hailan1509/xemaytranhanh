<?php

namespace App\Http\Controllers\Api;

use App\Laravue\Models\ChiTietHoaDon;
use App\Laravue\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SanPhamController extends BaseController
{
    const ITEM_PER_PAGE = 15;
    //
    public function index(Request $request) {
        $searchParams = $request->all();
        $currentUser = Auth::user();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        if(isset($searchParams['id'])) {
            $query = SanPham::find($searchParams['id']);
            return response()->json(['data' => $query], 200);
        }
        $query = SanPham::where('user_id', $currentUser->id)
        ->when(!empty($searchParams['title']), function ($q) use ($searchParams) {
            $q->where(function ($sq) use ($searchParams) {
                $sq->where('name', 'like', '%'.$searchParams['title'].'%')->orWhere('short_name', 'like', '%'.$searchParams['title'].'%');
            });
        })
        ->when(!empty($searchParams['ncc']), function ($q) use ($searchParams) {
            $q->where('nha_cung_cap', $searchParams['ncc']);
        })
        ->when(!empty($searchParams['hang_xe']), function ($q) use ($searchParams) {
            $q->where('hang_xe', $searchParams['hang_xe']);
        })
        ->when(!empty($searchParams['nhom_hang']), function ($q) use ($searchParams) {
            $q->where('nhom_hang', $searchParams['nhom_hang']);
        })
        ->when(!empty($searchParams['date']), function ($q) use ($searchParams) {
            if (!empty(($searchParams['month']))) {
                $arr = explode('-', $searchParams['date']);
                $q->whereMonth('ngay_nhap', $arr[1])->whereYear('ngay_nhap', $arr[0]);
            }
            else
                $q->where('ngay_nhap', $searchParams['date']);
        });

        $query = $query->orderBy('ngay_nhap', 'desc');

        if (!empty($searchParams['viewSelect'])) {
            $query = $query->get();
        }
        else {
            $query = $query->with('nhaCungCapInfo')->paginate($limit);
        }
        // dd();
        return response()->json(['data' => $query], 200);
    }

    public function store(Request $request) {
        $searchParams = $request->all();
        $messages = [
            'name.required' => 'Tên sản phẩm không để trống!',
            'short_name.required' => 'Số khung số ,máy không để trống!',
            'image.image' => 'File tải lên không ở dạng ảnh!',
            'image.mimes' => 'Ảnh không hợp lệ!',
            'image.max' => 'Kích thước ảnh phải nhỏ hơn 2048 KB!',
            'gia_ban.numeric' => 'Giá bán phải ở dạng số!',
            'gia_nhap.numeric' => 'Giá nhập phải ở dạng số!',
            'so_luong_con_lai.numeric' => 'Số lượng còn lại phải ở dạng số!',
            'so_luong_nhap.numeric' => 'Số lượng nhập phải ở dạng số!',
        ];
        $validator = Validator::make($searchParams, [
            'name' => 'required',
            'short_name' => 'required',
            'image'=>'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'gia_ban' => 'numeric',
            'gia_nhap' => 'numeric',
            'so_luong_con_lai' => 'nullable|numeric',
            'so_luong_nhap' => 'numeric',
        ], $messages);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors(), 'error']);
        }
        $id = $searchParams['id'];
        $currentUser = Auth::user();
        try {
            $imagePath = "";
            $imageName = "";
            if (!empty($request->image)) {
                $getImage = $request->image;
                $imageName = time().'.'.$getImage->extension();
                $imagePath = public_path().'/images/objects';
                $getImage->move($imagePath, $imageName);
            }
            if ($id) {
                $model = SanPham::find($searchParams['id']);
                if ($model) {
                    $model->name = $searchParams['name'];
                    $model->user_id = $currentUser->id;
                    $model->short_name = $searchParams['short_name'];
                    $model->hang_xe = $searchParams['hang_xe'];
                    $model->nha_cung_cap = $searchParams['nha_cung_cap'];
                    $model->nhom_hang = $searchParams['nhom_hang'];
                    $model->gia_ban = $searchParams['gia_ban'];
                    $model->gia_nhap = $searchParams['gia_nhap'];
                    $model->so_luong_con_lai = $searchParams['so_luong_con_lai'];
                    $model->so_luong_nhap = $searchParams['so_luong_nhap'];
                    $model->phuong_thuc_nhap = $searchParams['phuong_thuc_nhap'];
                    $model->ngay_nhap = $searchParams['ngay_nhap'];
                    $model->note = $searchParams['note'];
                    if (!empty($request->image)) {
                        if (!empty($model->img)) {
                            if (File::exists($imagePath.'/'.$model->img)) {
                                File::delete($imagePath.'/'.$model->img);
                            }
                        }
                        $model->img = $imageName;
                    }
                    $model->save();
                    return response()->json(['success' => true,'message' => 'Sửa thành công!'], 200);
                }
                else return response()->json(['success' => false,'message' => 'Tham số không chính xác!'], 200);
            }
            else {
                $model = new SanPham();
                $model->name = $searchParams['name'];
                $model->user_id = $currentUser->id;
                $model->short_name = $searchParams['short_name'];
                $model->hang_xe = $searchParams['hang_xe'];
                $model->nha_cung_cap = $searchParams['nha_cung_cap'];
                $model->nhom_hang = $searchParams['nhom_hang'];
                $model->gia_ban = $searchParams['gia_ban'];
                $model->gia_nhap = $searchParams['gia_nhap'];
                $model->so_luong_con_lai = $searchParams['so_luong_nhap'];
                $model->so_luong_nhap = $searchParams['so_luong_nhap'];
                $model->phuong_thuc_nhap = $searchParams['phuong_thuc_nhap'];
                $model->ngay_nhap = $searchParams['ngay_nhap'];
                $model->note = $searchParams['note'];
                if (!empty($request->image)) {
                    $model->img = $imageName;
                }
                $model->save();
                return response()->json(['success' => true,'message' => 'Thêm thành công!'], 200);
            }
        } catch (\Exception $e) {
            dd($e);
            return response()->json(['message' => 'Đã có lỗi vui lòng thao tác lại!'], 500);
        }
    }

    public function delete(Request $request) {
        $searchParams = $request->all();
        $currentUser = Auth::user();
        $id = $searchParams['id'];
        try {

            $model = SanPham::find($id);
            if ($model->user_id != $currentUser->id) {
                return response()->json(['success' => false,'message' => 'Không đủ quyền với thao tác trên!'], 200);
            }
            $model->delete();
            return response()->json(['success' => true,'message' => 'Xoá thành công!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Đã có lỗi vui lòng thao tác lại!'], 500);
        }
    }

    public function viewHoaDon(Request $request) {
        $currentUser = Auth::user();
        $searchParams = $request->all();
        if (!isset($searchParams['id'])) {
            return response()->json(['data' => []], 200);
        }
        $id = $searchParams['id'];
        $query = ChiTietHoaDon::where(['user_id' => $currentUser->id, 'ma_san_pham' => $id])->orderBy('created_at', 'desc')->with('hoaDon')->get();
        return response()->json(['data' => $query], 200);
    }
}
