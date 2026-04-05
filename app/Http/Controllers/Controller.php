<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function getAgentId()
    {
        $user = auth()->user();
        if (!$user) return null;

        if ($user->user_role_id == 8) { // Agent
            $agent = \App\Models\AdAgent::where('user_id', $user->id)->first();
            return $agent ? $agent->id : null;
        }

        if ($user->user_role_id == 10) { // Supervisor
            $supervisor = \App\Models\SmSuperviser::where('user_id', $user->id)->first();
            return $supervisor ? $supervisor->agent_id : null;
        }

        return null;
    }

    protected function getSupervisorId()
    {
        $user = auth()->user();
        if ($user && $user->user_role_id == 10) {
            $supervisor = \App\Models\SmSuperviser::where('user_id', $user->id)->first();
            return $supervisor ? $supervisor->id : null;
        }
        return null;
    }
}
