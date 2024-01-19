<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryRoom;
use App\Models\Room;
use App\Models\wards;

use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{

    public function createStep1(Request $request)
    {

        $category = CategoryRoom::where('name', $request->category)->first();
        if (isset($category->id)) {
            $request->validate([
                'name' => 'required|max:255',
                'quantity' => 'required|numeric',
                'area' => 'required',
                'price' => 'required',
                'unit' => 'required',
                'description' => 'required',
                'electric' => 'required',
                'water' => 'required',
            ]);
            if ($request->id == null) {
                $room = Room::create([
                    'name' => $request->name,
                    'category_id' => $category->id,
                    'chutro_id' => $request->user_id,
                    'price' => $request->price,
                    'area' => $request->area,
                    'unit' => $request->unit,
                    'describe_room' => $request->description,
                    'quantity' => $request->quantity,
                    'electric' => $request->electric,
                    'water' => $request->water,

                ]);
                return response()->json(['data' => $room]);
            } else {
                $result = Room::where('id', $request->id)->update([
                    'name' => $request->name,
                    'category_id' => $category->id,
                    'chutro_id' => $request->user_id,
                    'price' => $request->price,
                    'area' => $request->area,
                    'unit' => $request->unit,
                    'describe_room' => $request->description,
                    'quantity' => $request->quantity,
                    'electric' => $request->electric,
                    'water' => $request->water,

                ]);
                if ($result) {
                    return response()->json(['data' => "Thành công"]);
                } else {
                    return response()->json(['error' => "Lỗi cập nhật"], 400);
                }

            }
        } else {
            return response()->json(['error' => $request->all()], 400);
        }

    }
    public function getWards(Request $request)
    {

        $ward_list = wards::where('district_code', (int)$request->district_code)->get();
        return response()->json([
            'ward_list' => $ward_list
        ]);
    }
    public function createStep2(Request $request, $id)
    {

        $validated = $request->validate([
            'detail_address' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'ward_id' => 'required',
        ], [
                'lat.required' => "Bạn chưa chọn địa điểm khu trọ trên bản đồ",
            ]);
        $latlng = [
            'lat' => $request->lat,
            'long' => $request->long,
        ];
        $result = Room::where('id', $id)->update([
            'detail_address' => $request->detail_address,
            'latlng' => $latlng,
            'ward_id' => $request->ward_id,
        ]);
        if ($result) {
            return response()->json(['data' => "Thành công"]);
        } else {
            return response()->json(['error' => "Lỗi cập nhật"], 400);
        }
    }
    public function uploadMainImageRoom(Request $request)
    {
        $image = $request->file('file');
        $imageName = $image->getClientOriginalName();
        $image->move(public_path('images/main_room'), $imageName);
        return response()->json(['success' => $imageName]);
    }
    public function uploadMultiImageRoom(Request $request)
    {
        $image = $request->file('file');
        $imageName = $image->getClientOriginalName();
        $image->move(public_path('images/multi_room'), $imageName);
        return response()->json(['success' => $imageName]);
    }
    public function deleteImages(Request $request, $path)
    {

        $filename = $request->get('filename');
        //ImageUpload::where('filename',$filename)->delete();
        $path = public_path() . '\\images\\' . $path . '\\' . $filename;
        if (file_exists($path)) {
            unlink($path);
            return response()->json(['success' => $filename]);
        }
        return response()->json(['error' => $path]);
    }
    public function deleteImagesForUpdate(Request $request, $path)
    {

        $filename = $request->get('filename');
        $id_room = $request->id;
        //ImageUpload::where('filename',$filename)->delete();
        $path = public_path() . '\\images\\' . $path . '\\' . $filename;
        if (file_exists($path)) {
            unlink($path);
            // Xóa trong database
        }
          $result = Room::where('id', $id_room)->first();
            $list_img = json_decode($result->list_img);
            $key = array_search($filename, $list_img);
            
            if(array_search($filename, $list_img) !== false) {
                unset( $list_img[$key]);
               $list_img = array_values($list_img);
               $result = Room::where('id', $id_room)->update([
                'list_img' => $list_img,
                ]);
                if($result) {
                    return response()->json(['success' => $filename]);
                }else {
                    return response()->json(['key' => $key],400);
                }
            }else {
                return response()->json(['mess' => "Không tìm thấy trong database",
                                         'list_img' => $list_img,
                                         'filename' => $filename,
                                        ],400);
            }
           
            
        return response()->json(['error' => $path],400);
    }
    

    public function createStep3(Request $request, $id)
    {

        $validated = $request->validate([
            'main_img' => 'required',
        ], [
                'main_img.required' => "Bạn cần chọn ảnh chính",
            ]);
        
        $result = Room::where('id', $id)->update([
            'main_img' => $request->main_img,
            'list_img' => $request->list_img,
            'add_ons' => $request->add_ons,
            'video_link' => $request->video_link,
        ]);
        if ($result) {
            return response()->json(['data' => "Thành công"]);
        } else {
            return response()->json(['error' => "Lỗi cập nhật"], 400);
        }
    }
}