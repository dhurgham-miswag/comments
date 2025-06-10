<div class="max-w-4xl mx-auto p-4">
    {{-- Add Comment Form --}}
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <form wire:submit="addComment" class="space-y-4">
            <div>
                <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Add a comment</label>
                <textarea
                    wire:model="comment"
                    id="comment"
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                    placeholder="Write your comment here..."
                ></textarea>
                @error('comment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="flex justify-end">
                <button
                    type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out"
                >
                    Post Comment
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
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-600 font-medium">
                                {{ substr($comment->user->name ?? 'User', 0, 1) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $comment->user->name ?? 'Anonymous' }}
                            </p>
                            <span class="text-sm text-gray-500">
                                {{ $comment->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p class="mt-1 text-gray-700">{{ $comment->comment }}</p>
                        
                        {{-- Reply Button --}}
                        <div class="mt-2">
                            <button
                                wire:click="startReply({{ $comment->id }})"
                                class="text-sm text-blue-600 hover:text-blue-800 focus:outline-none"
                            >
                                Reply
                            </button>
                        </div>

                        {{-- Reply Form --}}
                        @if($replyingTo === $comment->id)
                            <div class="mt-4">
                                <form wire:submit="addReply" class="space-y-4">
                                    <div>
                                        <textarea
                                            wire:model="replyText"
                                            rows="2"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                            placeholder="Write your reply..."
                                        ></textarea>
                                        @error('replyText') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="flex justify-end space-x-3">
                                        <button
                                            type="button"
                                            wire:click="$set('replyingTo', null)"
                                            class="px-4 py-2 text-sm text-gray-700 hover:text-gray-900 focus:outline-none"
                                        >
                                            Cancel
                                        </button>
                                        <button
                                            type="submit"
                                            class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out"
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
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <span class="text-gray-600 text-sm font-medium">
                                                        {{ substr($reply->user->name ?? 'User', 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $reply->user->name ?? 'Anonymous' }}
                                                    </p>
                                                    <span class="text-sm text-gray-500">
                                                        {{ $reply->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                <p class="mt-1 text-gray-700">{{ $reply->comment }}</p>
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
