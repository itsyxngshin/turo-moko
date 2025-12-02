<?php

namespace App\Http\Controllers\Implementors;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function destroy(Module $module)
    {
        // Optional: add authorization check
        $this->authorize('delete', $module);

        // Delete module along with its lesson (if cascade is set)
        $module->delete();

        return redirect()->back()->with('success', 'Module deleted successfully.');
    }
}
