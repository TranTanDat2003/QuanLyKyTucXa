<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use App\Traits\CommonFunctions;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use CommonFunctions;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $services = Service::all();
            return response()->json([
                'success' => true,
                'services' => $services
            ], 200);
        }

        return view('admin.services.index');
    }

    public function store(ServiceRequest $request)
    {
        try {
            $validated = $request->validated();

            $name_image = $this->processFileName($validated['service_name']);
            $image = $request->file('service_img');
            if (!$image) {
                throw new \Exception('File ảnh không tồn tại.');
            }

            $generatedImageName = $this->processImageUpload(
                $image,
                'images/services',
                $name_image
            );

            Service::create([
                'service_name' => $validated['service_name'],
                'price' => $validated['price'],
                'is_active' => $request->has('is_active') ? 1 : 0,
                'service_img_path' => $generatedImageName,
                'service_description' => $validated['service_description'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thêm dịch vụ thành công'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Thêm dịch vụ thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(ServiceRequest $request, $serviceId)
    {
        try {
            $service = Service::findOrFail($serviceId);
            $validated = $request->validated();

            if ($request->hasFile('service_img')) {
                $name_image = $this->processFileName($validated['service_name']);
                $image = $request->file('service_img');
                $generatedImageName = $this->processImageUpload(
                    $image,
                    'images/services',
                    $name_image,
                    $service->service_img_path
                );
            } else {
                $generatedImageName = $service->service_img_path;
            }

            $service->update([
                'service_name' => $validated['service_name'],
                'price' => $validated['price'],
                'is_active' => $request->has('is_active') ? 1 : 0,
                'service_img_path' => $generatedImageName,
                'service_description' => $validated['service_description'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật dịch vụ thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật dịch vụ: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($serviceId)
    {
        try {
            $service = Service::findOrFail($serviceId);

            // Xóa file ảnh nếu tồn tại
            if (file_exists(public_path('images/services/' . $service->service_img_path))) {
                unlink(public_path('images/services/' . $service->service_img_path));
            }

            $service->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa dịch vụ thành công!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xóa dịch vụ thất bại: ' . $e->getMessage()
            ], 500);
        }
    }
}
