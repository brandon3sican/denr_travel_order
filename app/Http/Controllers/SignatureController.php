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
            $filename = 'signature_' . $employee->id . '_' . time() . '.png';
            $directory = 'signatures';
            $path = $directory . '/' . $filename;
            
            // Ensure the public/signatures directory exists
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }
            
            // If it's a direct upload (not from canvas)
            if ($isUpload) {
                // Check if the signature is already in base64 format
                if (strpos($signatureData, 'base64') !== false) {
                    // Extract the base64 data
                    $signatureData = explode(',', $signatureData)[1];
                }
                
                // Decode and save the base64 data
                Storage::disk('public')->put($path, base64_decode($signatureData));
            } else {
                // For canvas-based signatures
                $image = str_replace('data:image/png;base64,', '', $signatureData);
                $image = str_replace(' ', '+', $image);
                Storage::disk('public')->put($path, base64_decode($image));
            }
            
            // Create or update the signature
            $signature = $employee->signature()->updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'signature_data' => $request->input('signature'),
                    'signature_path' => $path,
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
                // Get the full path to the signature file
                $filePath = 'public/' . $signature->signature_path;
                
                // Delete the file if it exists in the public storage
                if ($signature->signature_path && Storage::exists($filePath)) {
                    Storage::delete($filePath);
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
