<?php

namespace App\Policies;

use App\Models\Grade;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GradePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Students can only view their own grades
        if ($user->hasRole('student')) {
            return true;
        }

        // Teachers and admins can view all grades
        return $user->hasAnyRole(['teacher', 'admin', 'staff']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Grade $grade): bool
    {
        // Students can only view their own grades
        if ($user->hasRole('student')) {
            return $grade->student_id === $user->id;
        }

        // Teachers can view grades for their subjects
        if ($user->hasRole('teacher')) {
            return $user->teachingSubjects()
                       ->where('id', $grade->subject_id)
                       ->exists();
        }

        // Admins and staff can view all grades
        return $user->hasAnyRole(['admin', 'staff']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only teachers can create grades for their subjects
        if ($user->hasRole('teacher')) {
            return true;
        }

        // Admins and staff can create grades
        return $user->hasAnyRole(['admin', 'staff']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Grade $grade): bool
    {
        // Prevent updating finalized grades unless admin
        if ($grade->is_final && !$user->hasRole('admin')) {
            return false;
        }

        // Teachers can update grades for their subjects
        if ($user->hasRole('teacher')) {
            return $user->teachingSubjects()
                       ->where('id', $grade->subject_id)
                       ->exists();
        }

        // Admins and staff can update any grade
        return $user->hasAnyRole(['admin', 'staff']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Grade $grade): bool
    {
        // Prevent deleting finalized grades unless admin
        if ($grade->is_final && !$user->hasRole('admin')) {
            return false;
        }

        // Teachers can delete grades for their subjects
        if ($user->hasRole('teacher')) {
            return $user->teachingSubjects()
                       ->where('id', $grade->subject_id)
                       ->exists();
        }

        // Only admins can delete grades
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can finalize the model.
     */
    public function finalize(User $user, Grade $grade): bool
    {
        // Teachers can finalize grades for their subjects
        if ($user->hasRole('teacher')) {
            return $user->teachingSubjects()
                       ->where('id', $grade->subject_id)
                       ->exists();
        }

        // Admins and staff can finalize any grade
        return $user->hasAnyRole(['admin', 'staff']);
    }

    /**
     * Determine whether the user can revert finalization of the model.
     */
    public function revertFinalization(User $user, Grade $grade): bool
    {
        // Only admins can revert finalization
        return $user->hasRole('admin');
    }
}
