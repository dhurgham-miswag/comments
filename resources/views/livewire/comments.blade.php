<div class="max-w-4xl mx-auto p-4" x-data="mentions_handler()">
    {{-- Add Comment Form --}}
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <form wire:submit="add_comment" class="space-y-4">
            <div>
                <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Add a comment</label>
                <div class="relative">
                    <textarea
                        wire:model="comment"
                        id="comment"
                        placeholder="Write your comment here... Use @ to mention users"
                        class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        rows="4"
                        x-on:input="handle_input($event, 'comment')"
                        x-on:keydown.escape="close_dropdown()"
                    ></textarea>
                    
                    <!-- Users Dropdown -->
                    <div
                        x-show="show_dropdown"
                        x-transition
                        class="absolute z-50 w-64 mt-1 bg-white rounded-md shadow-lg border border-gray-200"
                        style="display: none;"
                    >
                        <ul class="py-1 max-h-48 overflow-y-auto">
                            <template x-for="user in filtered_users" :key="user.id">
                                <li>
                                    <button
                                        type="button"
                                        class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100"
                                        x-on:click="select_user(user, 'comment')"
                                        x-text="user.name"
                                    ></button>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
                @error('comment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
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
                                <form wire:submit="add_reply" class="space-y-4">
                                    <div>
                                        <div class="relative">
                                            <textarea
                                                wire:model="reply_text"
                                                id="reply_text_{{ $comment->id }}"
                                                placeholder="Write your reply... Use @ to mention users"
                                                class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                                rows="3"
                                                x-on:input="handle_input($event, 'reply')"
                                                x-on:keydown.escape="close_dropdown()"
                                            ></textarea>
                                            
                                            <!-- Users Dropdown for Reply -->
                                            <div
                                                x-show="show_dropdown && active_field === 'reply'"
                                                x-transition
                                                class="absolute z-50 w-64 mt-1 bg-white rounded-md shadow-lg border border-gray-200"
                                                style="display: none;"
                                            >
                                                <ul class="py-1 max-h-48 overflow-y-auto">
                                                    <template x-for="user in filtered_users" :key="user.id">
                                                        <li>
                                                            <button
                                                                type="button"
                                                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100"
                                                                x-on:click="select_user(user, 'reply')"
                                                                x-text="user.name"
                                                            ></button>
                                                        </li>
                                                    </template>
                                                </ul>
                                            </div>
                                        </div>
                                        @error('reply_text') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="flex justify-end gap-2">
                                        <button
                                            type="button"
                                            wire:click="$set('replying_to', null)"
                                            class="px-3 py-1 text-xs bg-gray-200 text-gray-700 rounded hover:bg-gray-300"
                                        >
                                            Cancel
                                        </button>
                                        <button
                                            wire:click="add_reply"
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

    @push('scripts')
    <script>
        function mentions_handler() {
            return {
                show_dropdown: false,
                active_field: null,
                search_term: '',
                filtered_users: [],
                caret_position: 0,
                
                async handle_input(event, field) {
                    const textarea = event.target;
                    const text = textarea.value;
                    this.caret_position = textarea.selectionStart;
                    
                    // Find the word being typed
                    const before_caret = text.substring(0, this.caret_position);
                    const match = before_caret.match(/@(\w{2,})$/);
                    
                    if (match) {
                        this.search_term = match[1];
                        this.active_field = field;
                        await this.search_users();
                        this.show_dropdown = true;
                    } else {
                        this.close_dropdown();
                    }
                },
                
                async search_users() {
                    // Call your Livewire component method to search users
                    await @this.search_users(this.search_term);
                    this.filtered_users = await @this.get('users');
                },
                
                select_user(user, field) {
                    const textarea = document.getElementById(field === 'reply' ? `reply_text_${@this.replying_to}` : 'comment');
                    const text = textarea.value;
                    
                    // Find the last @ symbol before caret
                    const before_caret = text.substring(0, this.caret_position);
                    const last_at_pos = before_caret.lastIndexOf('@');
                    
                    // Replace the @mention with the selected user
                    const new_text = text.substring(0, last_at_pos) + 
                                  `**@${user.name}**` + 
                                  text.substring(this.caret_position);
                    
                    // Update the textarea
                    if (field === 'reply') {
                        @this.set('reply_text', new_text);
                    } else {
                        @this.set('comment', new_text);
                    }
                    
                    this.close_dropdown();
                },
                
                close_dropdown() {
                    this.show_dropdown = false;
                    this.active_field = null;
                    this.filtered_users = [];
                }
            }
        }
    </script>
    @endpush
</div>
