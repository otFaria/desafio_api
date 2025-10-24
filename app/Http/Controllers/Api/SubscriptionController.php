<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\Plan;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
 
    public function store(Request $request)
    {
       
        $request->validate([
            'plan_id' => 'required|integer|exists:plans,id',
        ]);

      
        $userId = 1;
        $planId = $request->input('plan_id');

        
        $plan = Plan::find($planId);

       
        $subscription = new Subscription();
        $subscription->user_id = $userId;
        $subscription->plan_id = $planId;
        $subscription->start_date = Carbon::now();
        $subscription->status = 'active';
        $subscription->save();

        
        $payment = new Payment();
        $payment->subscription_id = $subscription->id;
        $payment->amount = $plan->price; 
        $payment->payment_date = Carbon::now();
        $payment->payment_method = 'pix_simulado';
        $payment->save();

      
        return response()->json([
            'message' => 'Plano contratado com sucesso!',
            'subscription' => $subscription,
            'payment' => $payment,
        ], 201);
    }


    public function showActive()
    {
        $userId = 1;

        
        $activeSubscription = Subscription::where('user_id', $userId)
                                            ->where('status', 'active')
                                            ->with('plan') 
                                            ->first();

        
        if ($activeSubscription) {
            
            return response()->json($activeSubscription);
        } else {
            
            return response()->json([
                'message' => 'Nenhum contrato ativo encontrado.'
            ], 404);
        }
    }

    public function update(Request $request)
    {
        
        $request->validate([
            'new_plan_id' => 'required|integer|exists:plans,id',
        ]);

       
        $userId = 1;
        $newPlanId = $request->input('new_plan_id');

        
        $activeSubscription = Subscription::where('user_id', $userId)
                                            ->where('status', 'active')
                                            ->with('plan')
                                            ->first();

        
        if (!$activeSubscription) {
            return response()->json(['message' => 'Nenhum contrato ativo para trocar.'], 404);
        }

        
        if ($activeSubscription->plan_id == $newPlanId) {
            return response()->json(['message' => 'Você já está neste plano.'], 400); 
        }

        
        $today = Carbon::now();
        $startDate = Carbon::parse($activeSubscription->start_date);

        
        $daysInMonth = 30;
        $oldPlanPrice = $activeSubscription->plan->price;

        
        $daysUsed = $startDate->diffInDays($today);

        
        if ($daysUsed >= $daysInMonth) {
             $daysUsed = 0; 
            
        }

        $daysRemaining = $daysInMonth - $daysUsed;

       
        $dailyCostOldPlan = $oldPlanPrice / $daysInMonth;

        
        $credit = $daysRemaining * $dailyCostOldPlan;

        
        $newPlan = Plan::find($newPlanId);
        $newPlanPrice = $newPlan->price;

        
        $finalPaymentAmount = $newPlanPrice - $credit;

       
        if ($finalPaymentAmount < 0) {
            $finalPaymentAmount = 0; 
        }

        

       
        $activeSubscription->status = 'cancelled';
        $activeSubscription->updated_at = $today;
        $activeSubscription->save();


        $newSubscription = new Subscription();
        $newSubscription->user_id = $userId;
        $newSubscription->plan_id = $newPlanId;
        $newSubscription->start_date = $today;
        $newSubscription->status = 'active';
        $newSubscription->save();

   
        $payment = new Payment();
        $payment->subscription_id = $newSubscription->id;
        $payment->amount = $finalPaymentAmount;
        $payment->payment_date = $today;
        $payment->payment_method = 'pix_simulado (troca)';
        $payment->save();

      
        return response()->json([
            'message' => 'Troca de plano realizada com sucesso!',
            'credit_applied' => round($credit, 2),
            'new_payment_amount' => round($finalPaymentAmount, 2),
            'new_subscription' => $newSubscription,
        ], 200);
    }
}