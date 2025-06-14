<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Http\Resources\MemberResource;
use App\Http\Resources\TaskResource;

class CardSingleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'user_id'           => $this->user_id,
            'title'             => $this->title,
            'workspace_id'      => $this->workspace_id,
            'description'       => $this->description,
            'deadline'          => [
                'format'            => Carbon::createFromFormat('Y-m-d', $this->deadline)->format('d M Y'),
                'unformatted'       => $this->deadline,
            ],
            'status'            => $this->status->value,
            'priority'          => $this->priority,
            'created_at'        => $this->created_at->format('d M Y'),
            'members'           => MemberResource::collection($this->members),
            'member_count'      => $this->members_count,
            'attachments'       => $this->attachments,
            'has_attachment'    => $this->attachments()->exists(),
            'tasks'             => TaskResource::collection($this->tasks),
            'has_task'          => $this->tasks()->exists(),         
        ];
    }
}
