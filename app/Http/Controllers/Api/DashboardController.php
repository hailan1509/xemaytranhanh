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
class DashboardController extends BaseController
{
    const ITEM_PER_PAGE = 15;
    
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $currentUser = Auth::user();
        $sanPhams = SanPham::where('user_id', $currentUser->id)
        ->when(!empty($searchParams['date']), function ($q) use ($searchParams) {
            if (!empty(($searchParams['month']))) {
                $arr = explode('-', $searchParams['date']);
                $q->whereMonth('ngay_nhap', $arr[1])->whereYear('ngay_nhap', $arr[0]);
            }
            else
                $q->where('ngay_nhap', $searchParams['date']);
        })->get();
        $hoaDon = HoaDon::where('user_id', $currentUser->id)
        ->when(!empty($searchParams['date']), function ($q) use ($searchParams) {
            if (!empty(($searchParams['month']))) {
                $arr = explode('-', $searchParams['date']);
                $q->whereMonth('ngay_ban', $arr[1])->whereYear('ngay_ban', $arr[0]);
            }
            else
                $q->where('ngay_ban', $searchParams['date']);
        })->with('chiTiet')->get();
        $data = [
            'panel_group' => [
                'so_xe_nhap' => 0,
                'tien_xe_nhap' => 0,
                'so_xe_ban' => 0,
                'tien_xe_ban' => 0,
            ],
        ];
        foreach ($sanPhams as $sp) {
            $data['panel_group']['so_xe_nhap'] += $sp->so_luong_nhap;
            $data['panel_group']['tien_xe_nhap'] += $sp->so_luong_nhap * $sp->gia_nhap;
        }
        foreach ($hoaDon as $hd) {
            $data['panel_group']['tien_xe_ban'] += $hd->tong_tien;
            foreach ($hd->chiTiet as $ct) {
                $data['panel_group']['so_xe_ban'] += $ct->so_luong;
            }
        }
        return response()->json(['data' => $data], 200);
    }
}
