# Learner Feedback Feature - Setup Guide

## What Changed?

Removed all hardcoded values so the feedback system works properly for all users.

### ❌ Before (Hardcoded)

```php
public $showModal = true;     // Always showing
public $courseId = 5;          // Always course #5
public $implementerId = 1;     // Always implementer #1
'learner_id' => 1,            // Always user #1
```

### ✅ After (Dynamic)

```php
public $showModal = false;    // Starts closed
public $courseId;             // Set when opened
public $implementerId;        // Set when opened
'learner_id' => Auth::id(),   // Real logged-in user
```

---

## Files Changed

-   `app/Livewire/Learner/FeedbackModal.php` - Main component

---

## Setup Instructions

### 1. Database Migration

Run migrations if you haven't already:

```bash
php artisan migrate
```

This creates:

-   `course_feedbacks` table
-   `implementor_feedbacks` table

### 2. Replace the File

Replace your current `FeedbackModal.php` with the clean version.

### 3. Test the Modal

**Option A: Manual Test (Without Auth)**
Temporarily add this to test:

```php
// In FeedbackModal.php, add to openModal():
public function openModal($courseId, $implementerId)
{
    $this->courseId = $courseId;
    $this->implementerId = $implementerId;
    $this->showModal = true;
    $this->feedbackSubmitted = false;

    // TEMPORARY: For testing without auth
    if (!Auth::check()) {
        \Log::warning('Testing feedback without authentication');
    }
}

// In submitFeedback(), temporarily use a test user:
$learnerId = Auth::id() ?? 1; // Use user ID 1 for testing
```

**Option B: Test With Auth (Recommended)**
Make sure you have:

1. A user in the database
2. A course in the database
3. Login working

Then trigger the modal from somewhere (see "How to Use" below).

---

## How to Use

### Opening the Modal

From any Livewire component or Blade view where the user completes a course:

**From Livewire Component:**

```php
$this->dispatch('openFeedbackModal',
    courseId: $courseId,
    implementerId: $implementerId
);
```

**From Blade (with Alpine.js):**

```blade
<button @click="$wire.dispatch('openFeedbackModal', {
    courseId: {{ $courseId }},
    implementerId: {{ $implementerId }}
})">
    Give Feedback
</button>
```

### Example: Trigger After Course Completion

```php
// In your course completion logic:
public function completeCourse($courseId)
{
    // ... mark course as complete ...

    // Get the implementer_id from the course
    $course = Course::find($courseId);

    // Open feedback modal
    $this->dispatch('openFeedbackModal',
        courseId: $courseId,
        implementerId: $course->implementer_id
    );
}
```

---

## Requirements

### Must Have

-   [x] User authentication (`Auth::id()` must work)
-   [x] `courses` table with `implementer_id` column
-   [x] `users` table
-   [x] Migrations run

### Optional

-   Session flash messages configured (for error/success messages)

---

## Troubleshooting

### "You must be logged in to submit feedback"

**Problem:** Auth is not working  
**Solution:** Make sure:

1. User is logged in: `Auth::check()` returns true
2. Session middleware is active
3. User has a valid session

### "Missing required feedback data"

**Problem:** `courseId` or `implementerId` is null  
**Solution:** Make sure you're passing both values when opening the modal:

```php
$this->dispatch('openFeedbackModal',
    courseId: 5,           // ✅ Must be a number
    implementerId: 2       // ✅ Must be a number
);
```

### Modal doesn't open

**Problem:** `showModal` is still false  
**Solution:**

1. Make sure the modal component is included: `@livewire('learner.feedback-modal')`
2. Check browser console for JavaScript errors
3. Verify Alpine.js is loaded

### Database error when submitting

**Problem:** Foreign key constraint fails  
**Solution:** Make sure:

-   The `course_id` exists in `courses` table
-   The `implementer_id` exists in `users` table
-   The learner is actually enrolled in the course (if you have enrollment checks)

---

## For Testing Without Full Auth

If you want to test the modal before authentication is ready:

1. Create a test route:

```php
// routes/web.php
Route::get('/test-feedback', function () {
    return view('test-feedback');
});
```

2. Create a test view:

```blade
{{-- resources/views/test-feedback.blade.php --}}
@extends('layouts.layout2')

@section('content')
<div class="p-8">
    <h1>Test Feedback Modal</h1>

    <button
        class="bg-blue-500 text-white px-4 py-2 rounded"
        @click="$wire.dispatch('openFeedbackModal', {courseId: 1, implementerId: 1})">
        Open Feedback Modal
    </button>

    @livewire('learner.feedback-modal')
</div>
@endsection
```

3. Visit `/test-feedback` in your browser

---

## Integration Checklist

-   [ ] Replace `FeedbackModal.php` with clean version
-   [ ] Run migrations
-   [ ] Remove `@livewire('learner.feedback-modal')` from dashboard (it's hardcoded there)
-   [ ] Add modal to the appropriate page (course completion page)
-   [ ] Test opening the modal with real course/implementer IDs
-   [ ] Test submitting feedback while logged in
-   [ ] Verify data is saved to database

---

## Questions?

If something doesn't work:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Check browser console for JS errors
3. Use `dd()` to debug values in the component
