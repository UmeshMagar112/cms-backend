<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    // List all published subscription plans
    public function index()
    {
        $plans = SubscriptionPlan::where('published', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return response()->json($plans);
    }

    // Show a single subscription plan
    public function show($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        return response()->json($plan);
    }

    // Show payment page info for a plan
    public function paymentPage($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        return response()->json([
            'plan'  => $plan,
            'title' => 'Complete Payment'
        ]);
    }

    // Submit a payment for a subscription
    public function submitPayment(Request $request)
    {
        $request->validate([
            'plan_id'        => 'required|exists:subscription_plans,id',
            'screenshot'     => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'transaction_id' => 'nullable|string|max:100',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        $path = $request->file('screenshot')->store('payments', 'public');

        $payment = Payment::create([
            'admin_id'             => auth('admin')->id(),
            'subscription_plan_id' => $plan->id,
            'amount'               => $plan->price,
            'currency'             => $plan->currency,
            'screenshot'           => $path,
            'transaction_id'       => $request->transaction_id,
            'status'               => 'pending',
        ]);

        return response()->json([
            'message' => 'Payment proof submitted. Admin will verify and activate your subscription soon.',
            'payment' => $payment
        ]);
    }
}
