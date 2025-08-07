<?php
namespace App\Mail;

use App\Models\PdfPurchaseRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PdfPurchaseRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $pdfPurchaseRequest;
    public $requesterName;

    public function __construct(PdfPurchaseRequest $pdfPurchaseRequest)
    {
        $pdfPurchaseRequest->load('requester');
        $this->pdfPurchaseRequest = $pdfPurchaseRequest;
        $this->requesterName = $pdfPurchaseRequest->requester->name ?? 'N/A';
    }

   public function build()
    {
        $piaCode = $this->pdfPurchaseRequest->pia_code;
        $subject = "Thông báo: Có phiếu đề nghị mua hàng cần xử lý (PDF - {$piaCode})";

        return $this->subject($subject)
                    ->markdown('emails.purchase_request_notification_pdf')
                    ->with([
                        'pdfPurchaseRequest' => $this->pdfPurchaseRequest,
                        'requesterName' => $this->requesterName,
                        'requestType' => 'PDF', // THÊM DÒNG NÀY VÀO
                    ]);
    }
}
