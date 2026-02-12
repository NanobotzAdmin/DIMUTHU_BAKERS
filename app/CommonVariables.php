<?php

namespace App;

class CommonVariables
{
    public static $Active = 1;

    public static $Inactive = 0;

    public static $logIn = 'Login';

    public static $logOut = 'Logout';

    // Purchase Order Status
    public static $pending = 0;

    public static $approved = 1;

    public static $sent = 2;

    public static $partiallyReceived = 3;

    public static $received = 4;

    public static $closed = 5;

    public static $cancelled = 6;

    // Stock In Quality Check
    public static $passedQuality = 0;

    public static $partiallyQuality = 1;

    public static $failedQuality = 2;

    // Order Types
    public static $orderTypePosPickup = 1;

    public static $orderTypeSpecialOrder = 2;

    public static $orderTypeScheduledProduction = 3;

    public static $orderTypeAgentOrder = 4;

    // Event Types
    public static $eventTypeWedding = 1;

    public static $eventTypeBirthday = 2;

    public static $eventTypeCorporate = 3;

    // Delivery Types
    public static $deliveryTypePickup = 1;

    public static $deliveryTypeDelivery = 2;

    // Payment Methods
    public static $paymentMethodCash = 1;

    public static $paymentMethodCard = 2;

    public static $paymentMethodBankTransfer = 3;

    // Recurrence Patterns
    public static $recurrencePatternDaily = 1;

    public static $recurrencePatternWeekly = 2;

    public static $recurrencePatternMonthly = 3;

    public static $UnitOfMeasurement = [
        '1' => 'g',
        '2' => 'ml',
        '3' => 'kg',
        '4' => 'l',
        '5' => 'piece',
    ];

    // Quotation Status
    public static $quotationStatusDraft = 1;

    public static $quotationStatusPendingApproval = 2;

    public static $quotationStatusApproved = 3;

    public static $quotationStatusSent = 4;

    public static $quotationStatusCustomerAccepted = 5;

    public static $quotationStatusCustomerRejected = 6;

    public static $quotationStatusExpired = 7;

    public static $quotationStatusConverted = 8;

    public static $quotationStatusCancelled = 9;

    // Production Schedule Status
    public static $productionScheduled = 1;

    public static $productionInProgress = 2;

    public static $productionCompleted = 3;

    public static $productionDelayed = 4;

    // Production Schedules Instructions Status
    public static $instructionPending = 0;

    public static $instructionInProgress = 1;

    public static $instructionCompleted = 2;

    public static $instructionDelayed = 3;

    // STM Stock Order Request Status
    public static $orderRequestPending = 0;

    public static $orderRequestInProgress = 1;

    public static $orderRequestDispatched = 2;

    public static $orderRequestCompleted = 3;

    public static $orderRequestCancelled = 4;

    // Agent Types
    public static $agentTypeSalaried = 1;

    public static $agentTypeCommissionOnly = 2;

    public static $agentTypeCreditBased = 3;

    // Agent Status
    public static $agentStatusActive = 1;

    public static $agentStatusInactive = 2;

    // Customer Types
    public static $customerTypeB2B = 1;

    public static $customerTypeB2C = 2;

    public static $customerTypePOS = 3;

    // B2B Customer Types
    public static $b2bTypeWholesale = 1;

    public static $b2bTypeRetail = 2;

    public static $b2bTypeRestaurant = 3;

    public static $b2bTypeHotel = 4;

    public static $b2bTypeAgent = 5;

    public static $b2bTypeOther = 6;

    // Payment Terms (Byte Values)
    public static $paymentTermsCash = 1;

    public static $paymentTermsCredit7Days = 2;

    public static $paymentTermsCredit15Days = 3;

    public static $paymentTermsCredit30Days = 4;

    public static $paymentTermsChecking = 5;

    // Visit Schedule
    public static $visitScheduleWeekly = 1;

    public static $visitScheduleBiWeekly = 2;

    public static $visitScheduleMonthly = 3;

    public static $visitScheduleOnDemand = 4;

    // Daily Load Status
    public static $dailyLoadStatusDraft = 0;

    public static $dailyLoadStatusLoaded = 1;

    public static $dailyLoadStatusCompleted = 2;

    // STM Order Request Status
    public static $orderRequestPendingApproval = 0;

    public static $orderRequestApproved = 1;

    public static $orderRequestRejected = 2;

    public static $orderRequestProductionStarted = 3;

    public static $orderRequestReadyToDispatch = 4;

    public static $orderRequestDispatchCompleted = 5;

    public static $orderRequestDispatchConfirmed = 6;

    public static $orderRequestCompleteSettled = 7;
}
