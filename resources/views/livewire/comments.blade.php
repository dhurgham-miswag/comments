<div class="max-w-3xl mx-auto px-4 py-6 space-y-6">
    {{-- Add New Comment --}}
    <form wire:submit.prevent="addComment" class="bg-white p-4 border rounded-lg shadow-sm">
        <label for="new-comment" class="block text-sm font-medium text-gray-700 mb-1">Add a comment</label>
        <textarea id="new-comment" wire:model.defer="comment" rows="3" class="w-full border rounded-md p-2 focus:outline-none focus:ring focus:ring-indigo-300 resize-none" placeholder="Write your comment..."></textarea>
        @error('comment') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        <div class="mt-3 text-right">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">Post</button>
        </div>
    </form>

    {{-- Comments and Replies --}}
    @foreach($comments as $comment)
        <div class="bg-gray-50 p-4 rounded-md border space-y-3">
            {{-- Comment Display or Edit --}}
            @if ($editingComment === $comment->id)
                <div>
                    <textarea wire:model.defer="editingText" rows="2" class="w-full border p-2 rounded-md resize-none"></textarea>
                    @error('editingText') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    <div class="mt-2 space-x-2">
                        <button wire:click="updateComment" class="bg-green-600 text-white px-3 py-1 rounded">Update</button>
                        <button wire:click="$set('editingComment', null)" class="text-gray-500">Cancel</button>
                    </div>
                </div>
            @else
                <div class="text-gray-800">{{ $comment->comment }}</div>
                <div class="text-sm text-gray-500">by {{ $comment->user->name ?? 'Anonymous' }}</div>
                <div class="flex gap-4 mt-1 text-sm text-gray-600">
                    <button wire:click="startReply({{ $comment->id }})" class="hover:underline">Reply</button>
                    <button wire:click="startEdit({{ $comment->id }})" class="hover:underline">Edit</button>
                    <button wire:click="deleteComment({{ $comment->id }})" class="hover:underline text-red-500">Delete</button>
                </div>
            @endif

            {{-- Reply Form --}}
            @if ($replyingTo === $comment->id)
                <div class="mt-3">
                    <textarea wire:model.defer="replyText" rows="2" class="w-full border p-2 rounded-md resize-none" placeholder="Write a reply..."></textarea>
                    @error('replyText') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    <div class="mt-2 space-x-2">
                        <button wire:click="addReply" class="bg-blue-600 text-white px-3 py-1 rounded">Reply</button>
                        <button wire:click="$set('replyingTo', null)" class="text-gray-500">Cancel</button>
                    </div>
                </div>
            @endif

            {{-- Replies --}}
            @if ($comment->replies->count())
                <div class="mt-4 space-y-4 pl-6 border-l-2 border-gray-200">
                    @foreach ($comment->replies as $reply)
                        <div class="bg-white p-3 rounded-md border">
                            {{-- Edit Reply --}}
                            @if ($editingComment === $reply->id)
                                <div>
                                    <textarea wire:model.defer="editingText" rows="2" class="w-full border p-2 rounded-md resize-none"></textarea>
                                    @error('editingText') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                    <div class="mt-2 space-x-2">
                                        <button wire:click="updateComment" class="bg-green-600 text-white px-3 py-1 rounded">Update</button>
                                        <button wire:click="$set('editingComment', null)" class="text-gray-500">Cancel</button>
                                    </div>
                                </div>
                            @else
                                <div class="text-gray-800">{{ $reply->comment }}</div>
                                <div class="text-sm text-gray-500">by {{ $reply->user->name ?? 'Anonymous' }}</div>
                                <div class="flex gap-4 mt-1 text-sm text-gray-600">
                                    <button wire:click="startEdit({{ $reply->id }})" class="hover:underline">Edit</button>
                                    <button wire:click="deleteComment({{ $reply->id }})" class="hover:underline text-red-500">Delete</button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
</div>
