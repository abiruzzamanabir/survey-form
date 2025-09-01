<?php

namespace App\Http\Controllers;

use App\Mail\InvoicePaymentMail;
use App\Mail\MakePaymentMail;
use App\Mail\NominationSubmitMail;
use App\Models\Invoice;
use App\Models\Nomination;
use App\Models\Theme;
use App\Notifications\Notifications\NominationSubmit;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PDF;

class NominationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoice = Invoice::get()->unique('name');
        $theme = Theme::findOrFail(1);
        return view('nomination.index', [
            'form_type' => 'store',
            'invoices' => $invoice,
            'theme' => $theme,
        ]);
    }
    public function special()
    {
        $invoice = Invoice::get()->unique('name');
        $theme = Theme::findOrFail(1);
        return view('nomination.special', [
            'form_type' => 'store',
            'invoices' => $invoice,
            'theme' => $theme,
        ]);
    }
    public function student()
    {
        $invoice = Invoice::get()->unique('name');
        $theme = Theme::findOrFail(1);
        return view('nomination.index_student', [
            'form_type' => 'store',
            'invoices' => $invoice,
            'theme' => $theme,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Debug input data (remove after debugging)
        // dd($request->all());

        $rules = [
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'competencies' => 'required|array',
            'competencies.*.name' => 'required|string',
            'competencies.*.definition' => 'required|string',
            'competencies.*.industry' => 'required|array',
            'competencies.*.organization' => 'required|array',
            'competencies.*.industry.basic' => 'required|numeric|min:0|max:100',
            'competencies.*.industry.working' => 'required|numeric|min:0|max:100',
            'competencies.*.industry.advanced' => 'required|numeric|min:0|max:100',
            'competencies.*.industry.expert' => 'required|numeric|min:0|max:100',
            'competencies.*.organization.basic' => 'required|numeric|min:0|max:100',
            'competencies.*.organization.working' => 'required|numeric|min:0|max:100',
            'competencies.*.organization.advanced' => 'required|numeric|min:0|max:100',
            'competencies.*.organization.expert' => 'required|numeric|min:0|max:100',
            'priority_gaps' => 'required|array|min:3',
            'priority_gaps.*' => 'required|string|max:255',
            'final_thoughts' => 'required|string|max:1000',

        ];

        if ($request->input('category') === 'Others') {
            $rules['other_category'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);

        // Use 'other_category' if category is Others
        $category = $validated['category'] === 'Others' ? $validated['other_category'] : $validated['category'];

        // Generate unique keys
        $ukey = time() . rand(100, 999);
        $uid = uniqid('nom_');

        Nomination::create([
            'name' => $validated['name'],
            'designation' => $validated['designation'],
            'organization' => $validated['organization'],
            'category' => $category,
            'uid' => $uid,
            'ukey' => $ukey,
            'competency_name' => $validated['competencies'][0]['name'] ?? 'N/A',
            'definition' => $validated['competencies'][0]['definition'] ?? '',
            'competencies' => json_encode($validated['competencies']),
            'priority_gaps' => json_encode($validated['priority_gaps']),
            'final_thoughts' => $validated['final_thoughts'],

        ]);

        // Prepare email data if needed

        return redirect()->route('form.thank.free', $ukey);
    }





    public function updateinfo(Request $request, $id)
    {
        $update_date = Nomination::findOrFail($id);
        $update_date->update([
            'name' => $request->name,
            'alternative_name' => $request->alternative_name,
            'email' => $request->email,
            'alternative_email' => $request->alternative_email,
            'phone' => $request->phone,
            'alternative_phone' => $request->alternative_phone,
            'designation' => $request->designation,
            'organization' => $request->organization,
            'category' => $request->category,
            'address' => $request->address,
            'link' => $request->link,
        ]);

        return redirect()->route('dashboard.index')->with('success', 'Nomination Updated');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $nomination = Nomination::findOrFail($id);
        $invoice = Invoice::get()->unique('name');
        $theme = Theme::findOrFail(1);
        return view('nomination.index', [
            'form_type' => 'edit',
            'edit' => $nomination,
            'invoices' => $invoice,
            'theme' => $theme,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'invoice' => 'required',
        ]);
        $invoice = Invoice::where('invoice', $request->invoice)->first();
        if ($invoice) {
            if ($invoice->trash == 1) {
                return back()->with('warning', 'Your invoice is blocked. Please contact us to use this invoice again.');
            } else if ($invoice->available < 1) {
                return back()->with('danger', 'All Invoices are used');
            } else {
                $update_invoice = Invoice::where('invoice', $request->invoice)->first();
                $update_date = Nomination::where('ukey', $id)->first();
                $count = count(Nomination::where('invoice', $request->invoice)->get());
                $used = $count;
                $available = $update_invoice->total - $used;
                $update_invoice->update([
                    'used' => $used + 1,
                    'available' => $available - 1,
                ]);
                $update_date->update([
                    'invoice' => $request->invoice,
                    'pv' => true,
                ]);
                $user_data = $update_date;
                // $user_data->notify(new PaymentNotification($user_data));
                Mail::to($request->email)->send(new InvoicePaymentMail($user_data));

                // return redirect()->route('form.index')->with('success', 'Nomination Submitted');
                return back()->with('success', 'Nomination Submitted');
            }
        } else {
            return back()->with('warning', 'Invalid Invoice Number');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function hosted($ukey = null)
    {
        if ($ukey === null) {
            return redirect()->route('form.index')->with('danger', 'User Key Not Found');
        }
        if ($ukey) {
            $user_date = Nomination::where('ukey', $ukey)->first();
            $invoice = Invoice::where('invoice', $user_date->invoice)->first();
            $theme = Theme::first();
            $order_details = DB::table('orders')
                ->where('transaction_id', $ukey)
                ->select('transaction_id', 'tran_date', 'bank_tran_id', 'status', 'currency', 'amount', 'card_issuer')->orderBy('id', 'desc')->first();
            if ($user_date) {
                return view('nomination.checkout', [
                    'name' => $user_date->name,
                    'email' => $user_date->email,
                    'phone' => $user_date->phone,
                    'category' => $user_date->category,
                    'themeamount' => $theme->amount,
                    'payment' => $user_date->payment,
                    'invoice' => $user_date->invoice,
                    'ukey' => $user_date->ukey,
                    'card_issuer' => $order_details->card_issuer ?? '',
                    'transaction_id' => $order_details->transaction_id ?? '',
                    'tran_date' => $order_details->tran_date ?? '',
                    'bank_tran_id' => $order_details->bank_tran_id ?? '',
                    'amount' => $order_details->amount ?? '',
                    'invoice' => $invoice->invoice ?? '',
                ]);
            } else {
                return redirect()->route('form.index')->with('warning', 'User Key not matched');
            }
        }
    }
    public function thanks($ukey = null)
    {
        if ($ukey === null) {
            return redirect()->route('form.index')->with('danger', 'User Key Not Found');
        }
        if ($ukey) {
            $user_date = Nomination::where('ukey', $ukey)->first();
            $order_details = DB::table('orders')
                ->where('transaction_id', $ukey)
                ->select('transaction_id', 'tran_date', 'bank_tran_id', 'status', 'currency', 'amount', 'card_issuer')->orderBy('id', 'desc')->first();
            if ($user_date) {
                return view('dashboard.thankyou', [
                    'name' => $user_date->name,
                    'email' => $user_date->email,
                    'phone' => $user_date->phone,
                    'payment' => $user_date->payment,
                    'invoice' => $user_date->invoice,
                    'ukey' => $user_date->ukey,
                    'card_issuer' => $order_details->card_issuer ?? '',
                    'transaction_id' => $order_details->transaction_id ?? '',
                    'tran_date' => $order_details->tran_date ?? '',
                    'bank_tran_id' => $order_details->bank_tran_id ?? '',
                    'amount' => $order_details->amount ?? '',
                ]);
            } else {
                return redirect()->route('form.index')->with('warning', 'User Key not matched');
            }
        }
    }
    public function free($ukey = null)
    {
        if ($ukey === null) {
            return redirect()->route('form.index')->with('danger', 'User Key Not Found');
        }
        if ($ukey) {
            $user_date = Nomination::where('ukey', $ukey)->first();
            $theme = Theme::first();

            if ($user_date) {
                return view('dashboard.thankyoufree', [
                    'name' => $user_date->name,
                    'email' => $user_date->email,
                    'phone' => $user_date->phone,
                    'payment' => $user_date->payment,
                    'invoice' => $user_date->invoice,
                    'ukey' => $user_date->ukey,
                    'themeamount' => $theme['amount'],
                ]);
            } else {
                return redirect()->route('form.index')->with('warning', 'User Key not matched');
            }
        }
    }
    public function redirect()
    {
        return redirect()->route('form.index');
    }
}
