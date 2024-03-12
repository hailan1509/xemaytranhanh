<?php

namespace App\Http\Controllers\Api;

use App\Laravue\Models\NhomHang;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class NhomHangController extends BaseController
{
    const ITEM_PER_PAGE = 15;
    //
    public function index(Request $request) {
        $searchParams = $request->all();
        $currentUser = Auth::user();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        if(isset($searchParams['id'])) {
            $query = NhomHang::find($searchParams['id']);
            return response()->json(['data' => $query], 200);
        }
        $query = NhomHang::where('user_id', $currentUser->id)->when(!empty($searchParams['title']), function ($q) use ($searchParams) {
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
                $model = NhomHang::find($searchParams['id']);
                if ($model) {
                    $model->name = $searchParams['name'];
                    $model->user_id = $currentUser->id;
                    $model->note = $searchParams['note'];
                    $model->save();
                    return response()->json(['success' => true,'message' => 'Sửa thành công!'], 200);
                }
                else return response()->json(['success' => false,'message' => 'Tham số không chính xác!'], 200);
            }
            else {
                $model = new NhomHang();
                $model->name = $searchParams['name'];
                $model->user_id = $currentUser->id;
                $model->note = $searchParams['note'];
                $model->save();
                return response()->json(['success' => true,'message' => 'Thêm thành công!'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Đã có lỗi vui lòng thao tác lại!'], 500);
        }
    }

    public function delete(Request $request) {
        $searchParams = $request->all();
        $currentUser = Auth::user();
        $id = $searchParams['id'];
        try {

            $model = NhomHang::find($id);
            if ($model->user_id != $currentUser->id) {
                return response()->json(['success' => false,'message' => 'Không đủ quyền với thao tác trên!'], 200);
            }
            $model->delete();
            return response()->json(['success' => true,'message' => 'Thêm thành công!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Đã có lỗi vui lòng thao tác lại!'], 500);
        }
    }
}
