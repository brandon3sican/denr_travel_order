<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeSignature;
use App\Models\User;
use Illuminate\Http\Request;

class SignatureManagementController extends Controller
{
    public function index(Request $request)
    {
        // Admin gate (simple boolean flag on user model)
        if (!auth()->user() || !auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        // Build users list joined with employees and signatures for display
        $query = User::query()
            ->join('employees', 'users.email', '=', 'employees.email')
            ->leftJoin('employee_signatures', 'employee_signatures.employee_id', '=', 'employees.id')
            ->select([
                'users.id as user_id',
                'users.email as user_email',
                'employees.id as employee_id',
                'employees.first_name',
                'employees.middle_name',
                'employees.last_name',
                'employees.suffix',
                'employees.position_name',
                'employees.assignment_name',
                'employee_signatures.id as signature_id',
                'employee_signatures.updated_at as signature_updated_at',
            ]);

        // Assignment filter (optional, mirror Role Management)
        if ($request->filled('assignment')) {
            $query->where('employees.assignment_name', $request->assignment);
        }

        // Search filter (name, email, or position)
        if ($request->filled('search')) {
            $term = '%' . $request->search . '%';
            $query->where(function ($q) use ($term) {
                $q->where('employees.first_name', 'like', $term)
                    ->orWhere('employees.middle_name', 'like', $term)
                    ->orWhere('employees.last_name', 'like', $term)
                    ->orWhere('employees.position_name', 'like', $term)
                    ->orWhere('employees.email', 'like', $term);
            });
        }

        $users = $query
            ->orderBy('employees.first_name')
            ->orderBy('employees.last_name')
            ->paginate(15)
            ->withQueryString();

        // Distinct assignments for dropdown
        $assignments = Employee::query()
            ->select('assignment_name')
            ->whereNotNull('assignment_name')
            ->distinct()
            ->orderBy('assignment_name')
            ->pluck('assignment_name');

        return view('signature-management.index', [
            'users' => $users,
            'assignments' => $assignments,
        ]);
    }

    public function reset(Request $request, Employee $employee)
    {
        if (!auth()->user() || !auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        // Validate confirmation inputs
        $request->validate([
            'confirm_email' => 'required|email',
            'ack_reset' => 'accepted',
        ]);

        // Ensure admin typed the correct user's email
        if (strcasecmp($employee->email, $request->input('confirm_email')) !== 0) {
            return back()->withErrors(['confirm_email' => 'The confirmation email does not match the selected employee.'])->withInput();
        }

        // Delete signature if exists
        $signature = $employee->signature; // via relation
        if ($signature) {
            $signature->delete();
        }

        return back()->with('success', 'Signature has been reset for ' . $employee->first_name . ' ' . $employee->last_name . '.');
    }
}
