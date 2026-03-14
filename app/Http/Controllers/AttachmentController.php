<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttachmentRequest;
use App\Models\Attachment;
use App\Models\Task;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function __construct(private ActivityLogService $logService) {}

    public function store(StoreAttachmentRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('attachments/' . $task->id, $fileName, 'public');

        $task->attachments()->create([
            'user_id'   => auth()->id(),
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'disk'      => 'public',
        ]);

        $this->logService->log(auth()->user(), $task, 'attached', "Uploaded file: {$file->getClientOriginalName()}");
        return back()->with('success', 'File uploaded successfully!');
    }

    public function download(Attachment $attachment)
    {
        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }

    public function destroy(Attachment $attachment)
    {
        $task = $attachment->task;
        $this->authorize('update', $task);
        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();
        return back()->with('success', 'Attachment removed.');
    }
}