<?php

namespace App\Http\Controllers\Api;

use App\Laravue\Models\DichVu;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class DichVuController extends BaseController
{
    const ITEM_PER_PAGE = 15;
    //
    public function index(Request $request) {
        $searchParams = $request->all();
        $currentUser = Auth::user();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        if(isset($searchParams['id'])) {
            $query = DichVu::find($searchParams['id']);
            return response()->json(['data' => $query], 200);
        }
        $query = DichVu::where('user_id', $currentUser->id)->when(!empty($searchParams['title']), function ($q) use ($searchParams) {
            $q->where('name', 'like', '%'.$searchParams['title'].'%');
        });
        if (!empty($searchParams['viewSelect'])) {
            $query = $query->get();
        }
        else {
            $query = $query->paginate($limit);
        }
        return response()->json(['data' => $query], 200);
    }

    public function store(Request $request) {
        $searchParams = $request->all();
        $id = $searchParams['id'];
        $currentUser = Auth::user();
        try {
            if ($id) {
                $model = DichVu::find($searchParams['id']);
                if ($model) {
                    $model->ten_dich_vu = $searchParams['ten_dich_vu'];
                    $model->gia_ban = $searchParams['gia_ban'];
                    $model->note = $searchParams['note'];
                    $model->save();
                    return response()->json(['success' => true,'message' => 'Sửa thành công!'], 200);
                }
                else return response()->json(['success' => false,'message' => 'Tham số không chính xác!'], 200);
            }
            else {
                $model = new DichVu();
                $model->user_id = $currentUser->id;
                $model->ten_dich_vu = $searchParams['ten_dich_vu'];
                $model->gia_ban = $searchParams['gia_ban'];
                $model->note = $searchParams['note'];
                $model->save();
                return response()->json(['success' => true,'message' => 'Thêm thành công!'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Đã có lỗi vui lòng thao tác lại!'. $e->getMessage()], 500);
        }
    }

    public function delete(Request $request) {
        $searchParams = $request->all();
        $currentUser = Auth::user();
        $id = $searchParams['id'];
        try {

            $model = DichVu::find($id);
            $model->delete();
            return response()->json(['success' => true,'message' => 'Xoá thành công!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Đã có lỗi vui lòng thao tác lại!'], 500);
        }
    }
}
