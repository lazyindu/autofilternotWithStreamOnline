<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TelegramController extends Controller
{
    public function watch(Request $request){
        return view('template/req');
    }
    public function stream(Request $request, $path)
    {
        try {
            // Extract ID and secure_hash from the path
            if (preg_match('/^([a-zA-Z0-9_-]{6})(\d+)$/', $path, $matches)) {
                $secure_hash = $matches[1];
                $id = (int) $matches[2];
            } else {
                if (preg_match('/(\d+)(?:\/\S+)?/', $path, $matches)) {
                    $id = (int) $matches[1];
                } else {
                    abort(404);
                }
                $secure_hash = $request->query('hash');
            }

            // Validate hash and fetch file details (replace this with your actual logic)
            $fileDetails = $this->validateHashAndFetchFileDetails($id, $secure_hash);
            if (!$fileDetails) {
                abort(403, 'Invalid hash');
            }

            $filePath = $fileDetails['path'];
            $fileSize = Storage::size($filePath);
            $mimeType = $fileDetails['mime_type'];
            $fileName = $fileDetails['name'];

            $start = 0;
            $end = $fileSize - 1;

            if ($request->headers->has('Range')) {
                $range = $request->header('Range');
                $range = explode('=', $range, 2)[1];
                $range = explode('-', $range);
                $start = intval($range[0]);
                if (isset($range[1]) && is_numeric($range[1])) {
                    $end = intval($range[1]);
                }
            }

            $length = $end - $start + 1;

            $response = new StreamedResponse(function () use ($filePath, $start, $length) {
                $stream = Storage::readStream($filePath);
                fseek($stream, $start);
                echo fread($stream, $length);
                fclose($stream);
            });

            $response->headers->set('Content-Type', $mimeType);
            $response->headers->set('Content-Length', $length);
            $response->headers->set('Content-Range', "bytes $start-$end/$fileSize");
            $response->headers->set('Accept-Ranges', 'bytes');
            $response->headers->set('Content-Disposition', "inline; filename=\"$fileName\"");

            if ($request->headers->has('Range')) {
                $response->setStatusCode(206); // Partial Content
            }

            return $response;

        } catch (\Exception $e) {
            // Handle errors (log them, show a custom error page, etc.)
            abort(500, $e->getMessage());
        }
    }

    private function validateHashAndFetchFileDetails($id, $secure_hash)
    {
        // Implement your validation logic and fetch file details from the database
        // Example: Check if the hash is valid and retrieve file details
        return [
            'path' => 'path/to/your/file.mp4', // Update this to the actual file path
            'name' => 'Your File Name',
            'mime_type' => 'video/mp4'
        ];
    }
}
