<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Settings\ThemeSettings;
use App\Settings\GeneralSettings;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function print(Invoice $invoice)
    {
        $settings = app(GeneralSettings::class);
        $theme = app(ThemeSettings::class);
        return view('invoices.print', [
            'invoice' => $invoice,
            'settings' => $settings,
            'primaryColor' => $theme->primary_color ?? '#000000',
        ]);
    }
}
