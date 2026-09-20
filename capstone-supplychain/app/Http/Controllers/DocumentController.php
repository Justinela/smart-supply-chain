<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\PurchaseOrder;
use App\Services\AuditLoggerService;
use App\Services\DocumentStorageService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    protected DocumentStorageService $docService;

    public function __construct(DocumentStorageService $docService)
    {
        $this->docService = $docService;
    }

    public function index()
    {
        $documents = Document::with(['uploadedBy', 'links.linkable'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $purchaseOrders = PurchaseOrder::get();

        return view('documents.index', compact('documents', 'purchaseOrders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'document_type' => 'required|in:PO,DELIVERY_RECEIPT,INVOICE,SUPPLIER_DOC,LOGISTICS_DOC,OTHER',
            'file' => 'required|file|max:10240', // 10MB
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
        ]);

        try {
            $linkableModel = null;
            if ($request->purchase_order_id) {
                $linkableModel = PurchaseOrder::find($request->purchase_order_id);
            }

            $doc = $this->docService->uploadDocument(
                $request->file('file'),
                $request->document_type,
                $request->title,
                Auth::id(),
                $linkableModel
            );

            AuditLoggerService::log('UPLOAD_DOCUMENT', 'DocumentTracking', null, $doc->toArray());

            return redirect()->route('documents.index')->with('success', "Document '{$doc->title}' uploaded securely into DTRS!");
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function download(Document $document)
    {
        if (!Storage::exists($document->file_path)) {
            abort(404, "Requested document file does not exist on disk.");
        }

        AuditLoggerService::log('DOWNLOAD_DOCUMENT', 'DocumentTracking', null, ['document_id' => $document->id]);

        return Storage::download($document->file_path, $document->file_name, [
            'Content-Type' => $document->mime_type,
        ]);
    }
}
