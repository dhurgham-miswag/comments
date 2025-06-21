<div class="max-w-4xl mx-auto p-4">
    {{-- Add Comment Form --}}
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <form wire:submit.prevent="submit" class="space-y-4">
            {{ $this->form }}
            <div class="flex justify-end">
                <button
                    type="submit"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium bg-primary-600 text-white rounded-md hover:bg-primary-700 transition"
                >
                    💬 Post Comment
                </button>
            </div>
        </form>
    </div>

    {{-- Comments List --}}
    <div class="space-y-6">
        @forelse($comments as $comment)
            <div class="bg-white rounded-lg shadow-sm p-6">
                {{-- Main Comment --}}
                <div class="flex items-start space-x-4">
                    @if($can_show_commentor_name)
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-600 font-medium">
                                    {{ substr($comment->user->{config('comments.user_model.display_name', 'name')} ?? 'User', 0, 1) }}
                                </span>
                            </div>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            @if($can_show_commentor_name)
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $comment->user->{config('comments.user_model.display_name', 'name')} ?? 'Anonymous' }}
                                </p>
                            @endif
                            <span class="text-sm text-gray-500">
                                {{ $comment->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <div class="mt-1 text-gray-700">{!! $comment->comment !!}</div>

                        {{-- Reply Button --}}
                        @if($can_reply)
                            <div class="mt-2">
                                <button
                                    wire:click="start_reply({{ $comment->id }})"
                                    class="text-xs text-primary-600 hover:underline"
                                >
                                    ↩ Reply
                                </button>
                            </div>
                        @endif

                        {{-- Reply Form --}}
                        @if($replying_to === $comment->id)
                            <div class="mt-4">
                                <form wire:submit.prevent="add_reply" class="space-y-4">
                                    {{ $this->getReplyForm() }}
                                    <div class="flex justify-end gap-2">
                                        <button
                                            type="button"
                                            wire:click="$set('replying_to', null)"
                                            class="px-3 py-1 text-xs bg-gray-200 text-gray-700 rounded hover:bg-gray-300"
                                        >
                                            Cancel
                                        </button>
                                        <button
                                            type="submit"
                                            class="px-3 py-1 text-xs bg-primary-600 text-white rounded hover:bg-primary-700"
                                        >
                                            Post Reply
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Replies --}}
                        @if($comment->replies->count() > 0)
                            <div class="mt-6 space-y-4">
                                @foreach($comment->replies as $reply)
                                    <div class="pl-6 border-l-2 border-gray-200">
                                        <div class="flex items-start space-x-4">
                                            @if($can_show_commentor_name)
                                                <div class="flex-shrink-0">
                                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                        <span class="text-gray-600 text-sm font-medium">
                                                            {{ substr($reply->user->{config('comments.user_model.display_name', 'name')} ?? 'User', 0, 1) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between">
                                                    @if($can_show_commentor_name)
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ $reply->user->{config('comments.user_model.display_name', 'name')} ?? 'Anonymous' }}
                                                        </p>
                                                    @endif
                                                    <span class="text-sm text-gray-500">
                                                        {{ $reply->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                <div class="mt-1 text-gray-700">{!! $reply->comment !!}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="text-gray-400">
                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No comments yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Be the first to share your thoughts!</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
