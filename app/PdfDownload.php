<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PdfDownload extends Model
{
    protected $table = 'pdf_downloads';

    protected $fillable = [
    	'client_id',
    	'pdf_id'
    ];

  //   public function getPdfNotes($clientId, $pdfId){
		// return self::where('client_id', $clientId)->where('pdf_id', $pdfId)->get();
  //   }
}
