<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AiAssistantManagementController extends Controller
{
    public function aiAssistantIndex()
    {
    // Initial greeting message
    $initialMessages = [
        [
            'id' => 1,
            'role' => 'assistant',
            'content' => 'Hello! I am your **BakeryMate AI Assistant**. I can help you analyze waste trends, calculate recovery efficiency, or check inventory status. How can I help you today?',
            'isLoading' => false
        ]
    ];

    $quickPrompts = [
        "How much did we sell today?",
        "Which items are low in stock?",
        "What's the trial balance status?",
        "Show me day-end status",
        "How many journal entries are there?"
    ];

    return view('aiAssistant.chatPage', compact('initialMessages', 'quickPrompts'));
}
}
