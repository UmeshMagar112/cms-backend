<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
  
    // List all published subscription plans
   public function index()
    {
        $subscriptions = SubscriptionPlan::latest()->get();
        return response()->json($subscriptions);
    }

    // Show single plan
    public function show($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        return response()->json($plan);
    }

    // Create new subscription plan
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'status' => 'required|boolean',
        ]);

        $subscription = SubscriptionPlan::create($validated);

        return response()->json([
            'message' => 'Subscription plan created successfully',
            'data' => $subscription
        ], 201);
    }

    // Update a subscription plan
    public function update(Request $request, $id)
    {
        $subscription = SubscriptionPlan::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'status' => 'required|boolean',
        ]);

        $subscription->update($validated);

        return response()->json([
            'message' => 'Subscription plan updated successfully',
            'data' => $subscription
        ]);
    }

    // Delete a plan
     public function destroy($id)
    {
        $subscription = SubscriptionPlan::findOrFail($id);
        $subscription->delete();

        return response()->json([
            'message' => 'Subscription plan deleted successfully'
        ]);
    }

    // Show payment page info
    public function paymentPage($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);

        return response()->json([
            'plan' => $plan,
            'title' => 'Complete Payment'
        ]);
    }

    // Submit payment for a subscription
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
