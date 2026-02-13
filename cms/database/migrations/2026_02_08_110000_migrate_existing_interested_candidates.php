<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Lead;
use App\Models\InterestedCandidate;

return new class extends Migration
{
    public function up()
    {
        // Migrate existing interested candidates from leads table
        $interestedLeads = Lead::where('condition_status', 'Intrested')->get();
        
        foreach ($interestedLeads as $lead) {
            InterestedCandidate::updateOrCreate(
                ['number' => $lead->number],
                [
                    'name' => $lead->name,
                    'email' => $lead->email,
                    'role' => $lead->role,
                    'resume' => $lead->resume,
                    'status' => 'interested',
                    'interested_at' => $lead->updated_at ?? now()
                ]
            );
        }
    }

    public function down()
    {
        // Remove migrated data
        InterestedCandidate::truncate();
    }
};