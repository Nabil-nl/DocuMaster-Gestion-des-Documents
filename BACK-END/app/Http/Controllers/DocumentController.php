<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with('category', 'user')->get();
        return response()->json($documents, 200); // Return documents as JSON
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'file' => 'required|mimes:pdf,doc,docx,xlsx,xls|max:2048',
            'category_id' => 'required',
        ]);

        // Handle file upload
        $file = $request->file('file');
        $filePath = $file->store('documents', 'public');
        $fileType = $file->getClientOriginalExtension();

        $document = Document::create([
            'title' => $request->title,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
        ]);

        return response()->json($document, 201); // Return created document as JSON
    }

    public function show(Document $document)
    {
        return response()->json($document->load('category', 'user'), 200); // Return specific document as JSON
    }

    public function update(Request $request, Document $document)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'User is not logged in.'], 401);
        }

        // Validate the request
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
        ]);

        // Logging the document update process
        Log::info('Attempting to update document', ['document_id' => $document->id, 'request_data' => $request->all()]);

        // Check if file needs updating
        if ($request->hasFile('file')) {
            // Delete old file
            Storage::disk('public')->delete($document->file_path);

            // Upload new file
            $file = $request->file('file');
            $filePath = $file->store('documents', 'public');
            $fileType = $file->getClientOriginalExtension();

            $document->update([
                'title' => $request->title,
                'file_path' => $filePath,
                'file_type' => $fileType,
                'category_id' => $request->category_id,
            ]);
        } else {
            // Only update fields that are provided
            $document->update($request->only(['title', 'category_id']));
        }

        Log::info('Document updated successfully', ['document' => $document]);

        return response()->json($document, 200); // Return updated document as JSON
    }


    public function destroy(Document $document)
    {
        Log::info('Deleting document', ['document' => $document]);

        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        } else {
            Log::warning('Attempted to delete a document with no file path.', ['document_id' => $document->id]);
        }

        $document->delete();

        // Return JSON with a message and an emoji
        return response()->json(['message' => '🗑️ Document deleted successfully!'], 200);
    }
}
