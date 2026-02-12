<?php

namespace App\Http\Controllers;

use App\Models\PmInterface;
use App\Models\PmInterfaceComponent;
use App\Models\PmInterfaceTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InterfaceManagementController extends Controller
{
    public function index()
    {
        $topics = PmInterfaceTopic::with([
            'interfaces' => function ($query) {
                $query->orderBy('order_no', 'asc')->orderBy('id', 'asc')->with('components');
            }
        ])
            ->where('status', 1)
            ->orderBy('order_no', 'asc') // Order topics by order_no
            ->get();
        return view('interfaceManagement.index', compact('topics'));
    }

    public function storeTopic(Request $request)
    {
        $request->validate([
            'topic_name' => 'required|string|max:100',
        ]);

        try {
            PmInterfaceTopic::create([
                'topic_name' => $request->topic_name,
                'menu_icon' => $request->menu_icon,
                'section_class' => $request->section_class,
                'status' => 1,
                'created_by' => 1, // Static for now, replace with Auth::user()->id later
                'updated_by' => 1,
                'remark1' => $request->remark1,
                'show_in_slidebar' => $request->has('show_in_slidebar') ? 1 : 0,
            ]);

            return response()->json(['success' => true, 'message' => 'Topic created successfully']);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['success' => false, 'message' => 'Failed to create topic']);
        }
    }

    public function updateTopic(Request $request)
    {
        $request->validate([
            'topic_name' => 'required|string|max:100',
        ]);

        try {
            $topic = PmInterfaceTopic::find($request->id);
            if ($topic) {
                $topic->update([
                    'topic_name' => $request->topic_name,
                    'menu_icon' => $request->menu_icon,
                    'section_class' => $request->section_class,
                    'updated_by' => \Illuminate\Support\Facades\Auth::id() ?? 1,
                    'show_in_slidebar' => $request->has('show_in_slidebar') ? 1 : 0,
                ]);
                return response()->json(['success' => true, 'message' => 'Topic updated successfully']);
            }
            return response()->json(['success' => false, 'message' => 'Topic not found']);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['success' => false, 'message' => 'Failed to update topic']);
        }
    }

    public function storeInterface(Request $request)
    {
        $request->validate([
            'interface_name' => 'required|string|max:100',
            'pm_interface_topic_id' => 'required|exists:pm_interface_topic,id',
        ]);

        try {
            PmInterface::create([
                'pm_interface_topic_id' => $request->pm_interface_topic_id,
                'interface_name' => $request->interface_name,
                'path' => $request->path,
                'icon_class' => $request->icon_class,
                'status' => 1,
                'created_by' => \Illuminate\Support\Facades\Auth::id() ?? 1,
                'updated_by' => \Illuminate\Support\Facades\Auth::id() ?? 1,
                'show_in_slidebar' => $request->has('show_in_slidebar') ? 1 : 0,
            ]);
            return response()->json(['success' => true, 'message' => 'Interface created successfully']);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['success' => false, 'message' => 'Failed to create interface']);
        }
    }

    public function updateInterface(Request $request)
    {
        $request->validate([
            'interface_name' => 'required|string|max:100',
        ]);

        try {
            $interface = PmInterface::find($request->id);
            if ($interface) {
                $interface->update([
                    'interface_name' => $request->interface_name,
                    'path' => $request->path,
                    'icon_class' => $request->icon_class,
                    'updated_by' => 1,
                    'show_in_slidebar' => $request->has('show_in_slidebar') ? 1 : 0,
                ]);
                return response()->json(['success' => true, 'message' => 'Interface updated successfully']);
            }
            return response()->json(['success' => false, 'message' => 'Interface not found']);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['success' => false, 'message' => 'Failed to update interface']);
        }
    }

    public function storeComponent(Request $request)
    {
        $request->validate([
            'components_name' => 'required|string|max:100',
            'pm_interface_id' => 'required|exists:pm_interfaces,id',
        ]);

        try {
            PmInterfaceComponent::create([
                'pm_interface_id' => $request->pm_interface_id,
                'components_name' => $request->components_name,
                'component_id' => $request->component_id,
                'status' => 1,
                'created_by' => \Illuminate\Support\Facades\Auth::id() ?? 1,
                'updated_by' => \Illuminate\Support\Facades\Auth::id() ?? 1,
            ]);
            return response()->json(['success' => true, 'message' => 'Component created successfully']);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['success' => false, 'message' => 'Failed to create component']);
        }
    }

    public function updateComponent(Request $request)
    {
        $request->validate([
            'components_name' => 'required|string|max:100',
        ]);

        try {
            $component = PmInterfaceComponent::find($request->id);
            if ($component) {
                $component->update([
                    'components_name' => $request->components_name,
                    'component_id' => $request->component_id,
                    'updated_by' => 1,
                ]);
                return response()->json(['success' => true, 'message' => 'Component updated successfully']);
            }
            return response()->json(['success' => false, 'message' => 'Component not found']);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['success' => false, 'message' => 'Failed to update component']);
        }
    }

    public function saveInterfaceOrder(Request $request)
    {
        $request->validate([
            'ordered_ids' => 'required|array',
            'ordered_ids.*' => 'exists:pm_interfaces,id',
        ]);

        try {
            DB::beginTransaction();
            foreach ($request->ordered_ids as $index => $id) {
                PmInterface::where('id', $id)->update(['order_no' => $index + 1]);
            }
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Order saved successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['success' => false, 'message' => 'Failed to save order']);
        }
    }

    public function saveTopicOrder(Request $request)
    {
        $request->validate([
            'ordered_ids' => 'required|array',
            'ordered_ids.*' => 'exists:pm_interface_topic,id',
        ]);

        try {
            DB::beginTransaction();
            foreach ($request->ordered_ids as $index => $id) {
                PmInterfaceTopic::where('id', $id)->update(['order_no' => $index + 1]);
            }
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Topic order saved successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['success' => false, 'message' => 'Failed to save topic order']);
        }
    }
}
