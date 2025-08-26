<?php

namespace App\Http\Controllers;

use App\Models\EmployeeSignature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller as BaseController;

class SignatureController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $signature = $user->employee->signature;
        
        return view('signature.index', compact('signature'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'signature' => 'required|string',
        ]);

        try {
            $user = Auth::user();
            $employee = $user->employee;
            
            $signatureData = $request->input('signature');
            $isUpload = $request->input('is_upload', false);
            
            // If it's a direct upload (not from canvas)
            if ($isUpload) {
                // Check if the signature is already in base64 format
                if (strpos($signatureData, 'base64') !== false) {
                    // Extract the base64 data
                    list($type, $data) = explode(';', $signatureData);
                    list(, $data) = explode(',', $data);
                    $imageData = base64_decode($data);
                    
                    // Get the file extension from the mime type
                    $mimeType = str_replace('data:', '', $type);
                    $extension = $this->getExtensionFromMime($mimeType);
                    
                    // Generate a unique filename
                    $filename = 'signatures/' . Str::random(40) . '.' . $extension;
                    
                    // Store the file
                    Storage::disk('public')->put($filename, $imageData);
                    
                    // Save to database
                    $signature = $employee->signature()->updateOrCreate(
                        ['employee_id' => $employee->id],
                        [
                            'signature_data' => $signatureData,
                            'signature_path' => $filename,
                            'mime_type' => $mimeType,
                            'is_active' => true
                        ]
                    );
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Signature uploaded successfully',
                        'signature' => $signature
                    ]);
                }
            }
            
            // Handle canvas signature (original code)
            $image = str_replace('data:image/png;base64,', '', $signatureData);
            $image = str_replace(' ', '+', $image);
            $imageData = base64_decode($image);
            
            // Generate a unique filename
            $filename = 'signatures/' . Str::random(40) . '.png';
            
            // Store the file
            Storage::disk('public')->put($filename, $imageData);
            
            // Save to database
            $signature = $employee->signature()->updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'signature_data' => $request->input('signature'),
                    'signature_path' => $filename,
                    'mime_type' => 'image/png',
                    'is_active' => true
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Signature saved successfully',
                'signature' => $signature
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving signature: ' . $e->getMessage()
            ], 500);
        }
    }

    public function clear()
    {
        try {
            $user = Auth::user();
            $signature = $user->employee->signature;
            
            if ($signature) {
                // Delete the file if it exists
                if ($signature->signature_path && Storage::disk('public')->exists($signature->signature_path)) {
                    Storage::disk('public')->delete($signature->signature_path);
                }
                
                // Delete the record
                $signature->delete();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Signature cleared successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing signature: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get file extension from mime type
     */
    private function getExtensionFromMime($mimeType)
    {
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            'image/bmp'  => 'bmp',
            'image/svg+xml' => 'svg',
        ];
        
        return $extensions[$mimeType] ?? 'png';
    }
}
